# laravel-localization

Thanks to https://github.com/mcamara/laravel-localization
For giving me a way to open my eyes that actually laravel could make a dynamic prefix on the first url parameter

IT ONLY SUPPORTS PHP 7.0++ because I use a shorthand of "??"

# Setup
I haven't made a composer yet, so you could install it manually by downloading the file and put it anywhere 
But remember to write it on your kernel to use the middleware and app config to use the class
Mine was like this

Kernel.php

    'localizationRedirect' => \App\Http\Middleware\LocalizationRedirect::class,
    
config/app.php

    'Localization' => App\Libraries\Facades\Localization::class,
    
routes/web.php

    Route::prefix(Localization::setLocale())
         ->middleware(['localizationRedirect'])
       

# Using translated URL

routes/web.php

    Route::get(Localization::trans('routes.about'))
    
resources/lang/en/routes.php

    
    return [
        "about" => "about",
    ];
    
resources/lang/ja/routes.php
    
    return [
        "about" => "約",
    ];

# A few methods you might need

    Localization::currentLocale();
    Localization::supportedLocale();
    Localization::otherSupportedLocale();
    Localization::defaultLocale();
    Localization::currentTranslatedRouteName();
    Localization::currentTranslatedRoutePrefix();
    Localization::translatedRouteName($locale);
    Localization::translatedRoutePrefix($locale);

# available config
config/localization.php

    return [
        'filename' => 'routes',
        'hideDefaultLocaleInUrl' => true,
        'defaultTranslatedRoutes' => null,
        'locale' => 'en',
        'globalLocalizationKeyRoute' => function ($model, $locale) {
            // this is a custom slug to detect the current translated routes, mine uses dimsav translatable so it looks like this
            return $model->translate($locale)->slug;
        },
        'hideDefaultLocaleInUrl' => true,
        'translatedRoutesFileName' => 'routes',
        
        //for middleware
        'redirectCode' => 302, 
        
        //imported from mcamara
        'supportedLocales' => [
            'en' => ['name' => 'English', 'script' => 'Latn', 'native' => 'English', 'regional' => 'en_GB'],
    


# why remake mcamara ?

I found many bugs there, and I think mcamara quit from updating his repo.
That is why I decided to read his code and do it "my way".
Please feel free to star or ask me to add some more features.
