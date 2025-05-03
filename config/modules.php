<?php

use App\Console\Commands\PageMakeCommand;
use Nwidart\Modules\Activators\FileActivator;
use Nwidart\Modules\Providers\ConsoleServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | Default module namespace.
    |
    */
    'namespace' => 'Modules',

    /*
    |--------------------------------------------------------------------------
    | Module Stubs
    |--------------------------------------------------------------------------
    |
    | Default module stubs.
    |
    */
    'stubs' => [
        'enabled' => true,
        // 'path' => base_path('vendor/nwidart/laravel-modules/src/Commands/stubs'),
        'path' => base_path('stubs/nwidart'),
        'files' => [
            'routes/web' => 'routes/web.php',
            'routes/api' => 'routes/api.php',
            // 'views/index' => 'resources/views/index.blade.php',
            // 'views/master' => 'resources/views/layouts/master.blade.php',
            'scaffold/config' => 'config/config.php',
            'composer' => 'composer.json',
            'assets/js/app' => 'resources/assets/js/app.js',
            // 'assets/sass/app' => 'resources/assets/sass/app.scss',
            'vite' => 'vite.config.js',
            'package' => 'package.json',

            // Custom generated file
            'views/app' => 'resources/views/app.blade.php',
            'assets/js/bootstrap' => 'resources/assets/js/bootstrap.js',
            'assets/css/app' => 'resources/assets/css/app.css',
            'assets/js/composables/useAuth' => 'resources/assets/js/composables/useAuth.js',
            'assets/js/composables/useCan' => 'resources/assets/js/composables/useCan.js',
            'assets/js/composables/useRole' => 'resources/assets/js/composables/useRole.js',
            'assets/js/composables/useFlash' => 'resources/assets/js/composables/useFlash.js',
            'assets/js/composables/useFormContext' => 'resources/assets/js/composables/useFormContext.js',
            'assets/js/composables/useFormErrors' => 'resources/assets/js/composables/useFormErrors.js',
            'assets/js/plugins/Translations' => 'resources/assets/js/plugins/Translations.js',
        ],
        'replacements' => [
            'routes/web' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'CONTROLLER_NAMESPACE'],
            'routes/api' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'CONTROLLER_NAMESPACE'],
            'vite' => ['LOWER_NAME', 'STUDLY_NAME'],
            'assets/js/app' => ['LOWER_NAME', 'STUDLY_NAME'],
            'assets/js/bootstrap' => ['LOWER_NAME', 'STUDLY_NAME'],
            'assets/css/app' => ['LOWER_NAME', 'STUDLY_NAME'],
            'json' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'PROVIDER_NAMESPACE'],
            'views/app' => ['LOWER_NAME', 'STUDLY_NAME'],
            'views/index' => ['LOWER_NAME'],
            'views/master' => ['LOWER_NAME', 'STUDLY_NAME'],
            'scaffold/config' => ['STUDLY_NAME'],
            'composer' => [
                'LOWER_NAME',
                'STUDLY_NAME',
                'VENDOR',
                'AUTHOR_NAME',
                'AUTHOR_EMAIL',
                'MODULE_NAMESPACE',
                'PROVIDER_NAMESPACE',
                'APP_FOLDER_NAME',
            ],
        ],
        'gitkeep' => true,
    ],
    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Modules path
        |--------------------------------------------------------------------------
        |
        | This path is used to save the generated module.
        | This path will also be added automatically to the list of scanned folders.
        |
        */
        'modules' => base_path('Modules'),

        /*
        |--------------------------------------------------------------------------
        | Modules assets path
        |--------------------------------------------------------------------------
        |
        | Here you may update the modules' assets path.
        |
        */
        'assets' => public_path('modules'),

        /*
        |--------------------------------------------------------------------------
        | The migrations' path
        |--------------------------------------------------------------------------
        |
        | Where you run the 'module:publish-migration' command, where do you publish the
        | the migration files?
        |
        */
        'migration' => base_path('database/migrations'),

        /*
        |--------------------------------------------------------------------------
        | The app path
        |--------------------------------------------------------------------------
        |
        | app folder name
        | for example can change it to 'src' or 'App'
        */
        'app_folder' => 'app/',

        /*
        |--------------------------------------------------------------------------
        | Generator path
        |--------------------------------------------------------------------------
        | Customise the paths where the folders will be generated.
        | Setting the generate key to false will not generate that folder
        */
        'generator' => [
            // app/
            'actions' => ['path' => 'app/Actions', 'generate' => false],
            'casts' => ['path' => 'app/Casts', 'generate' => false],
            'channels' => ['path' => 'app/Broadcasting', 'generate' => false],
            'class' => ['path' => 'app/Classes', 'generate' => false],
            'command' => ['path' => 'app/Console', 'generate' => false],
            'component-class' => ['path' => 'app/View/Components', 'generate' => false],
            'emails' => ['path' => 'app/Emails', 'generate' => false],
            'event' => ['path' => 'app/Events', 'generate' => false],
            'enums' => ['path' => 'app/Enums', 'generate' => false],
            'exceptions' => ['path' => 'app/Exceptions', 'generate' => false],
            'jobs' => ['path' => 'app/Jobs', 'generate' => false],
            'helpers' => ['path' => 'app/Helpers', 'generate' => false],
            'interfaces' => ['path' => 'app/Interfaces', 'generate' => false],
            'listener' => ['path' => 'app/Listeners', 'generate' => false],
            'model' => ['path' => 'app/Models', 'generate' => false],
            'notifications' => ['path' => 'app/Notifications', 'generate' => false],
            'observer' => ['path' => 'app/Observers', 'generate' => false],
            'policies' => ['path' => 'app/Policies', 'generate' => false],
            'provider' => ['path' => 'app/Providers', 'generate' => true],
            'repository' => ['path' => 'app/Repositories', 'generate' => false],
            'resource' => ['path' => 'app/Transformers', 'generate' => false],
            'route-provider' => ['path' => 'app/Providers', 'generate' => true],
            'rules' => ['path' => 'app/Rules', 'generate' => false],
            'services' => ['path' => 'app/Services', 'generate' => true],
            'scopes' => ['path' => 'app/Models/Scopes', 'generate' => false],
            'traits' => ['path' => 'app/Traits', 'generate' => false],

            // app/Http/
            'controller' => ['path' => 'app/Http/Controllers', 'generate' => true],
            'filter' => ['path' => 'app/Http/Middleware', 'generate' => false],
            'request' => ['path' => 'app/Http/Requests', 'generate' => false],

            // config/
            'config' => ['path' => 'config', 'generate' => true],

            // database/
            'factory' => ['path' => 'database/factories', 'generate' => true],
            'migration' => ['path' => 'database/migrations', 'generate' => true],
            'seeder' => ['path' => 'database/seeders', 'generate' => true],

            // lang/
            'lang' => ['path' => 'lang', 'generate' => false],

            // resource/
            'assets' => ['path' => 'resources/assets', 'generate' => true],
            'component-view' => ['path' => 'resources/views/components', 'generate' => false],
            'views' => ['path' => 'resources/views', 'generate' => false],
            'page' => ['path' => 'resources/assets/js/pages', 'generate' => true],
            'component' => ['path' => 'resources/assets/js/components', 'generate' => false],
            'layout' => ['path' => 'resources/assets/js/layouts', 'generate' => false],
            'plugin' => ['path' => 'resources/assets/js/plugins', 'generate' => false],
            'composable' => ['path' => 'resources/assets/js/composables', 'generate' => false],

            // routes/
            'routes' => ['path' => 'routes', 'generate' => true],

            // tests/
            'test-feature' => ['path' => 'tests/Feature', 'generate' => true],
            'test-unit' => ['path' => 'tests/Unit', 'generate' => true],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto Discover of Modules
    |--------------------------------------------------------------------------
    |
    | Here you configure auto discover of module
    | This is useful for simplify module providers.
    |
    */
    'auto-discover' => [
        /*
        |--------------------------------------------------------------------------
        | Migrations
        |--------------------------------------------------------------------------
        |
        | This option for register migration automatically.
        |
        */
        'migrations' => true,

        /*
        |--------------------------------------------------------------------------
        | Translations
        |--------------------------------------------------------------------------
        |
        | This option for register lang file automatically.
        |
        */
        'translations' => false,

    ],

    /*
    |--------------------------------------------------------------------------
    | Package commands
    |--------------------------------------------------------------------------
    |
    | Here you can define which commands will be visible and used in your
    | application. You can add your own commands to merge section.
    |
    */
    'commands' => ConsoleServiceProvider::defaultCommands()
        ->merge([
            // New commands go here
            PageMakeCommand::class,
        ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Scan Path
    |--------------------------------------------------------------------------
    |
    | Here you define which folder will be scanned. By default will scan vendor
    | directory. This is useful if you host the package in packagist website.
    |
    */
    'scan' => [
        'enabled' => false,
        'paths' => [
            base_path('vendor/*/*'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Composer File Template
    |--------------------------------------------------------------------------
    |
    | Here is the config for the composer.json file, generated by this package
    |
    */
    'composer' => [
        'vendor' => env('MODULE_VENDOR', 'indra'),
        'author' => [
            'name' => env('MODULE_AUTHOR_NAME', 'Indra Muliana'),
            'email' => env('MODULE_AUTHOR_EMAIL', 'indra26.work@gmail.com'),
        ],
        'composer-output' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Choose what laravel-modules will register as custom namespaces.
    | Setting one to false will require you to register that part
    | in your own Service Provider class.
    |--------------------------------------------------------------------------
    */
    'register' => [
        'translations' => true,
        /**
         * load files on boot or register method
         */
        'files' => 'register',
    ],

    /*
    |--------------------------------------------------------------------------
    | Activators
    |--------------------------------------------------------------------------
    |
    | You can define new types of activators here, file, database, etc. The only
    | required parameter is 'class'.
    | The file activator will store the activation status in storage/installed_modules
    */
    'activators' => [
        'file' => [
            'class' => FileActivator::class,
            'statuses-file' => base_path('modules_statuses.json'),
        ],
    ],

    'activator' => 'file',
];
