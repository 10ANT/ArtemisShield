<?php
// config/ffmpeg.php

return [

    /*
    |--------------------------------------------------------------------------
    | FFmpeg Binary Path
    |--------------------------------------------------------------------------
    |
    | This value defines the absolute path to your FFmpeg binary.
    | It is loaded from your .env file to allow different paths for
    | development (Windows) and production (Linux).
    |
    | The fallback 'ffmpeg' assumes it's globally available in the system's PATH.
    |
    */
    'path' => env('FFMPEG_PATH', 'ffmpeg'),

];