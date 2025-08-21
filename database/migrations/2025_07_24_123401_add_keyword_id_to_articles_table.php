<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */

    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Add the keyword_id column
            $table->foreignId('keyword_id')
                ->nullable() // Make it nullable in case an article isn't directly tied to a single keyword
                ->constrained() // Creates a foreign key constraint to the 'keywords' table
                ->onDelete('cascade'); // This is the magic! If a keyword is deleted, articles linked to it are also deleted.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropConstrainedForeignId('keyword_id');
            // Then drop the column
            $table->dropColumn('keyword_id');
        });
    }
};
