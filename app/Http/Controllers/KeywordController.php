<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class KeywordController extends Controller
{
    public function index()
    {

        $keywords = Auth::user()->keywords()->latest()->get();

        // Pass the keywords to the view
        return view('keywords.index', compact('keywords'));
    }


    public function store(Request $request)
    {
        // 1. Validate the incoming request data
        $request->validate([
            // Ensure keyword is required, string, max 255 chars, and unique per user
            'keyword' => [
                'required',
                'string',
                'max:255',
                Rule::unique('keywords')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                }),
            ],
        ]);

        // 2. Create the keyword and associate it with the authenticated user
        Auth::user()->keywords()->create([
            'keyword' => $request->keyword,
        ]);

        // 3. Redirect back to the keywords index with a success message
        return redirect()->route('articles.fetch.form')->with('success', 'Keyword added successfully!');
    }

    public function edit(Keyword $keyword)
    {
        // IMPORTANT: Authorization Check!
        // Ensure the authenticated user owns this keyword
        if (Auth::id() !== $keyword->user_id) {
            abort(403, 'Unauthorized action.'); // Returns a 403 Forbidden response
        }

        return view('keywords.edit', compact('keyword'));
    }


    public function update(Request $request, Keyword $keyword)
    {
        // IMPORTANT: Authorization Check!
        // Ensure the authenticated user owns this keyword
        if (Auth::id() !== $keyword->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // 1. Validate the incoming request data
        $request->validate([
            // Rule to ensure keyword is unique *for this user*, excluding the current keyword being updated
            'keyword' => [
                'required',
                'string',
                'max:255',
                Rule::unique('keywords')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })->ignore($keyword->id), // Ignore the current keyword's ID
            ],
        ]);

        // 2. Update the keyword
        $keyword->update([
            'keyword' => $request->keyword,
        ]);

        // 3. Redirect back to the keywords index with a success message
        return redirect()->route('articles.fetch.form')->with('success', 'Keyword updated successfully!');
    }

    /**
     * Remove the specified keyword from storage.
     * Corresponds to: DELETE /keywords/{keyword}
     *
     * @param  \App\Models\Keyword  $keyword
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Keyword $keyword)
    {
        // IMPORTANT: Authorization Check!
        // Ensure the authenticated user owns this keyword
        if (Auth::id() !== $keyword->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $keyword->delete();

        return redirect()->route('keywords.index')->with('success', 'Keyword deleted successfully!');
    }
}
