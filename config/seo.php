<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default SEO Title
    |--------------------------------------------------------------------------
    |
    | The default title of your application, used when no specific title
    | is provided for a page.
    |
    */
    'title' => env('APP_NAME', 'Parkir2077'),

    /*
    |--------------------------------------------------------------------------
    | Title Separator
    |--------------------------------------------------------------------------
    |
    | The character or string used to separate the page title and the site
    | title.
    |
    */
    'title_separator' => ' - ',

    /*
    |--------------------------------------------------------------------------
    | Default SEO Description
    |--------------------------------------------------------------------------
    |
    | The default description for your application. This is used in meta
    | description and Open Graph/Twitter descriptions.
    |
    */
    'description' => 'Industrial Standard Parking Management System.',

    /*
    |--------------------------------------------------------------------------
    | Default Keywords
    |--------------------------------------------------------------------------
    */
    'keywords' => 'parking, management, system, parkir2077',

    /*
    |--------------------------------------------------------------------------
    | Default Author
    |--------------------------------------------------------------------------
    */
    'author' => 'kenndeclouv',

    /*
    |--------------------------------------------------------------------------
    | Default Shared Image
    |--------------------------------------------------------------------------
    |
    | The default image used for Open Graph and Twitter cards.
    |
    */
    'image' => '/images/seo-banner.png',

    /*
    |--------------------------------------------------------------------------
    | Twitter specific settings
    |--------------------------------------------------------------------------
    */
    'twitter' => [
        'site' => '@parkir2077',
        'creator' => '@kenndeclouv',
    ],

    /*
    |--------------------------------------------------------------------------
    | Open Graph specific settings
    |--------------------------------------------------------------------------
    */
    'og' => [
        'type' => 'website',
    ],
];
