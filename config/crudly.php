<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CRUDLY Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the Crudly CRUD generator package
    |
    */

    // Route prefix
    'route_prefix' => env('CRUDLY_ROUTE_PREFIX', 'crudly'),

    // Middleware for generated routes
    'middleware' => env('CRUDLY_MIDDLEWARE', ['web']),

    // Columns to exclude from all CRUD operations
    'global_filters' => [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ],

    // Table-specific column filters
    'table_filters' => [
        // Example:
        // 'users' => ['password_reset_token'],
    ],

    // Tables to exclude from generation
    'exclude_tables' => [
        'migrations',
        'failed_jobs',
        'password_resets',
        'password_reset_tokens',
        'personal_access_tokens',
        'sessions',
    ],

    // Pagination items per page
    'pagination' => env('CRUDLY_PAGINATION', 15),

    // CSS framework
    'css_framework' => env('CRUDLY_CSS_FRAMEWORK', 'tailwind'),

    // View paths
    'views' => [
        'index' => 'crudly::crud.index',
        'create' => 'crudly::crud.create',
        'edit' => 'crudly::crud.edit',
        'show' => 'crudly::crud.show',
    ],

    // Namespaces
    'model_namespace' => 'App\\Models',
    'controller_namespace' => 'App\\Http\\Controllers',

    // Auto-generate routes
    'auto_routes' => env('CRUDLY_AUTO_ROUTES', false),

    // Generate factories for testing
    'generate_factories' => env('CRUDLY_GENERATE_FACTORIES', true),

    // Generate migrations
    'generate_migrations' => env('CRUDLY_GENERATE_MIGRATIONS', false),

    // Image upload configuration
    'image' => [
        'disk' => 'public',
        'path' => 'uploads',
        'allowed_mimes' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        'max_size' => 2048, // KB
    ],
];
