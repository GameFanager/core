# Project LiveCMS - CORE

# How To Install :

1. Create Laravel Project (5.2.*)
    ````
         composer create-project laravel/laravel liveCMS --prefer-dist 
    ````

2. Edit composer.json
    Change  :
    ````    
        "config": {
            "preferred-install": "dist"
        }
    ````

    with :
    
    ````
        "minimum-stability": "dev",
        "prefer-stable": true
    ````


3. After finish, add livecms core in your project
    ````
         cd liveCMS 
         composer require livecms/core "dev-master"
    ````

4. open file config/app.php
    Before :
    ````
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
    ````

    add this :
    ````
        Mrofi\LaravelSharedHostingPackage\LaravelSharedHostingPackageServiceProvider::class,
        Barryvdh\Debugbar\ServiceProvider::class,
        Yajra\Datatables\DatatablesServiceProvider::class,
        UxWeb\SweetAlert\SweetAlertServiceProvider::class,
        LiveCMS\Support\Thumbnailer\ThumbnailerServiceProvider::class,
        RoketId\ImageMax\ImageMaxServiceProvider::class,
        LiveCMS\Providers\LiveCMSServiceProvider::class,
    ````
    and add to 'aliases'
    ````
        'Debugbar'  => Barryvdh\Debugbar\Facade::class,
        'Datatables'=> Yajra\Datatables\Datatables::class,
        'Form'      => Collective\Html\FormFacade::class,
        'Html'      => Collective\Html\HtmlFacade::class,
        'Alert'     => UxWeb\SweetAlert\SweetAlert::class,
        'Thumb'     => LiveCMS\Support\Thumbnailer\Facades\Thumbnailer::class,
        'ImageMax'  => RoketId\ImageMax\ImageMaxFacade::class,
    ````

4. Publish vendor :
    ````
         php artisan vendor:publish --force
    ````

5. Set folder permissions :
    ````
        chmod +w -R public/files/
        chmod +w -R public/uploads/
        chmod +w -R public/users/
    ````

6. Open app/Http/Kernel.php and edit :

    add this line to :
    ```` 
        protected $middleware = [
            ...

            \LiveCMS\Middleware\GlobalParamsMiddleware::class,
            \LiveCMS\Middleware\HttpsMiddleware::class,
        ];
    ````

    change :
    ````
        protected $routeMiddleware = [
            'auth' => \App\Http\Middleware\Authenticate::class,
            change to :
            'auth' => \LiveCMS\Middleware\Authenticate::class,

            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            change to :
            'guest' => \LiveCMS\Middleware\RedirectIfAuthenticated::class,

            add this line :
            'model' => \LiveCMS\Middleware\ModelMiddleware::class,
        ];
    ````

7. Open config/auth.php
    Edit :
    ````
        'providers' => [
            'users' => [
                'driver' => 'eloquent',
                'model' => LiveCMS\Models\Users\User::class,
            ],

        .......

        'passwords' => [
            'users' => [
                'provider' => 'users',
                'email' => 'livecms::auth.emails.password',
                'table' => 'password_resets',
                'expire' => 60,
            ],
        ],
    ````

8. Update resources/lang/id/validation.php
    Edit :
    ````
        'attributes' => require(base_path('resources/lang/vendor/livecms/id/livecms.php')),
    ````

9. Update your .env
    update based on what your site url:
    ````
        APP_URL=yourdomain.com
    ````

10. Do Migrate
    ````
        php artisan migrate --seed
    ````

11. Login
    visit : http://yourdomain/login

    default username / password 
    
    1. Admin :
        email : admin@livecms.org
        password : admin

    2. Super Admin :
        email : super@livecms.org
        password : admin

Visit https://github.com/livecms/LiveCMS for more info.

