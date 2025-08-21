<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // Added for logging
use App\Models\Article; // Imported Article model
use App\Models\Keyword; // Imported Keyword model
use Carbon\Carbon;

class ArticleController extends Controller
{
    /**
     * Display a listing of the user's fetched articles.
     * Corresponds to: GET /articles
     */
    public function index()
    {
        // Get all articles for the authenticated user, ordered by latest published_at
        $articles = Auth::user()->articles()->latest('published_at')->paginate(10);

        // Pass the articles to the view
        return view('articles.index', compact('articles'));
    }

    public function showFetchForm()
    {
        return view('articles.fetch-form');
    }


    public function fetchAndSave(Request $request)
    {
        // 1. Get keywords for the authenticated user
        $keywords = Auth::user()->keywords;

        // If no keywords are found, redirect back with an error message
        if ($keywords->isEmpty()) {
            return redirect()->route('articles.fetch.form')->with('error', 'You need to add some keywords first to fetch articles!');
        }

        // Retrieve API Key from .env file
        $apiKey = env('NEWS_API_KEY');
        $baseUrl = 'https://newsapi.org/v2/everything'; // News API 'everything' endpoint

        // Check if API key is missing or invalid
        if (empty($apiKey)) {
            Log::error('NEWS_API_KEY is not set in the .env file.');
            return redirect()->route('articles.fetch.form')->with('error', 'API Key is not configured. Please contact support.');
        }

        $articlesFound = 0; // Counter for articles saved

        // Loop through each of the user's keywords
        foreach ($keywords as $keyword) {
            try {
                // Make the API request to News API
                $response = Http::get($baseUrl, [
                    'q' => $keyword->keyword,
                    'language' => 'en',
                    'sortBy' => 'publishedAt',
                    'apiKey' => $apiKey,
                    'pageSize' => 10
                ]);


                if ($response->successful()) {
                    // Get the 'articles' array from the JSON response, default to empty array if not found
                    $articlesData = $response->json()['articles'] ?? [];

                    // Log if no articles were returned for a specific keyword
                    if (empty($articlesData)) {
                        Log::info('News API returned no articles for keyword: ' . $keyword->keyword);
                        continue; // Move to the next keyword
                    }

                    // Iterate through each article received from the API
                    foreach ($articlesData as $articleData) {
                        // Basic validation: ensure title and URL are present
                        if (!empty($articleData['title']) && !empty($articleData['url'])) {
                            // Check if an article with this URL already exists for the current user
                            $existingArticle = Auth::user()->articles()->where('url', $articleData['url'])->first();

                            // Only save if the article does not already exist

                            if (!$existingArticle) {
                                // Convert the published_at date to the correct format
                                $publishedAt = null;
                                if (!empty($articleData['publishedAt'])) {
                                    try {
                                        $publishedAt = Carbon::parse($articleData['publishedAt'])->format('Y-m-d H:i:s');
                                    } catch (\Exception $e) {
                                        Log::warning('Could not parse date: ' . $articleData['publishedAt']);
                                    }
                                }



                                Auth::user()->articles()->create([
                                    'title' => $articleData['title'],
                                    'summary' => $articleData['description'] ?? null,
                                    'url' => $articleData['url']?? 'null',
                                    'image_url' => $articleData['urlToImage'] ?? null,
                                    'source' => $articleData['source']['name'] ?? null,
                                    'published_at' => $publishedAt,
                                    'user_id' => Auth::id(),
                                    'keyword_id' => $keyword->id,
                                ]);
                                $articlesFound++;
                            }


                        } else {
                            Log::info('Skipped article from API due to missing title or URL: ' . json_encode($articleData));
                        }
                    }
                } else {
                    // Log API errors (e.g., invalid key, rate limit, etc.)
                    Log::error('News API error for keyword ' . $keyword->keyword . ': ' . $response->body());
                    // You could add a session flash here to show a user-friendly error for API issues.
                }
            } catch (\Exception $e) {
                // Catch any exceptions during the HTTP request or data processing
                Log::error('Exception during API call for keyword ' . $keyword->keyword . ': ' . $e->getMessage());
                // You might add a session flash here for a user-facing error.
            }
        }

        // Redirect to the articles index page after fetching and saving
        $message = ($articlesFound > 0) ? "{$articlesFound} new articles fetched and saved successfully!" : "No new articles found for your keywords.";
        return redirect()->route('articles.index')->with('success', $message);
    }

    public function destroy(Article $article)
    {
        // Authorization: Ensure the authenticated user owns this article
        if (Auth::id() !== $article->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $article->delete();

        return redirect()->route('articles.index')->with('success', 'Article deleted successfully!');
    }

}
