<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Stichoza\GoogleTranslate\GoogleTranslate;

if (!function_exists('gtrans')) {
    /**
     * Translates the given text using Google Translate and caches the result.
     *
     * @param string $text The text to translate.
     * @return string The translated text.
     */
    function gtrans(string $text): string
    {
        // Get the target language from the Laravel application locale
        $targetLocale = App::getLocale();

        // Don't translate if the target is English (or your default language)
        if ($targetLocale == 'en') {
            return $text;
        }

        // Create a unique cache key for this text and target language
        $cacheKey = 'translation.' . $targetLocale . '.' . sha1($text);

        // Use Laravel's cache to store the translation forever.
        // If it's in the cache, it returns the value immediately.
        // If not, it runs the closure, stores the result, and then returns it.
        return Cache::rememberForever($cacheKey, function () use ($text, $targetLocale) {
            try {
                // Instantiate the translator
                $tr = new GoogleTranslate();
                // Set the target language
                $tr->setTarget($targetLocale);
                // Translate and return the text
                return $tr->translate($text);
            } catch (\Exception $e) {
                // If Google blocks us or there's an error,
                // log the error and return the original text gracefully.
                Log::error('Google Translate failed: ' . $e->getMessage());
                return $text;
            }
        });
    }
}
