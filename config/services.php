<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],


    // 文章生成任务 API
    'article_task_base_url'            => env('ARTICLE_TASK_BASE_URL', 'http://101.33.118.104:8028'),
    'article_task_access_key'          => env('ARTICLE_TASK_ACCESS_KEY', env('ARTICLE_TASK_API_KEY', '')),
    'article_task_token_url'           => env('ARTICLE_TASK_TOKEN_URL', '/api/token'),
    'article_task_token_field'         => env('ARTICLE_TASK_TOKEN_FIELD', 'access_key'),
    'article_task_token_cache_ttl'     => (int) env('ARTICLE_TASK_TOKEN_CACHE_TTL', 82800),
    'article_task_email'               => env('ARTICLE_TASK_EMAIL', ''),
    'article_task_password'            => env('ARTICLE_TASK_PASSWORD', ''),
    'article_task_project_id'          => env('ARTICLE_TASK_PROJECT_ID', 0),
    'article_task_default_category'    => env('ARTICLE_TASK_DEFAULT_CATEGORY', 'seo'),
    'article_task_default_language'    => env('ARTICLE_TASK_DEFAULT_LANGUAGE', 'English'),
    'article_task_default_force_refresh' => (bool) env('ARTICLE_TASK_DEFAULT_FORCE_REFRESH', false),
    'article_task_default_include_cover' => (bool) env('ARTICLE_TASK_DEFAULT_INCLUDE_COVER', true),
    'article_task_default_image_count'   => (int) env('ARTICLE_TASK_DEFAULT_IMAGE_COUNT', 3),
    'article_task_connect_timeout'       => (int) env('ARTICLE_TASK_CONNECT_TIMEOUT', 10),
    'article_task_timeout'               => (int) env('ARTICLE_TASK_TIMEOUT', 60),
    'article_task_result_timeout'        => (int) env('ARTICLE_TASK_RESULT_TIMEOUT', 180),
    'article_task_daily_limit'           => (int) env('ARTICLE_TASK_DAILY_LIMIT', 0),  // 0 = 不限制

    // TinyPNG 图片压缩
    'tinify' => [
        'key' => env('TINIFY_API_KEY'),
    ],

    // Azure OpenAI GPT Image 文章配图
    'article_image' => [
        'api_key' => env('AZURE_OPENAI_API_KEY'),
        'auth_mode' => env('AZURE_OPENAI_IMAGE_AUTH_MODE', 'api-key'),
        'model' => env('AZURE_OPENAI_IMAGE_MODEL', 'gpt-image-1.5'),
        'api_url' => env('AZURE_OPENAI_IMAGE_API_URL', ''),
        'cover_size' => env('AZURE_OPENAI_IMAGE_COVER_SIZE', env('AZURE_OPENAI_IMAGE_SIZE', '1536x1024')),
        'content_size' => env('AZURE_OPENAI_IMAGE_CONTENT_SIZE', env('AZURE_OPENAI_IMAGE_SIZE', '1024x1024')),
        'quality' => env('AZURE_OPENAI_IMAGE_QUALITY', 'medium'),
        'output_format' => env('AZURE_OPENAI_IMAGE_FORMAT', 'png'),
        'background' => env('AZURE_OPENAI_IMAGE_BACKGROUND', ''),
        'output_compression' => (int) env('AZURE_OPENAI_IMAGE_OUTPUT_COMPRESSION', 90),
        'timeout' => (int) env('AZURE_OPENAI_IMAGE_TIMEOUT', 120),
    ],

];
