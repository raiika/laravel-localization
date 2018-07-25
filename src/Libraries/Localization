<?php

namespace App\Libraries;

use Exception;

class Localization
{
	public $currentLocale;

    public function defaultLocale()
    {
    	return config('localization.locale') ?? config('app.locale') ?? 'en';
    }

    public function localeFromUrl($locale = null)
    {
    	if (empty($locale) || !is_string($locale)) {
            $locale = request()->segment(1);
        }

        if ($this->isSupportedLocales($locale)) {
            return $locale;
        } else {
        	return null;
        }

        return null;
    }

    public function setLocale($locale = null)
    {
        $locale = $this->localeFromUrl($locale);

        if ($this->isSupportedLocales($locale)) {
            $this->currentLocale = $locale;
        } elseif ($this->hideDefaultLocaleInURL()) {
        	$locale = null;
            $this->currentLocale = $this->defaultLocale();
        }

        app()->setLocale($this->currentLocale);

        return $locale;
    }

    public function supportedLocales()
    {
        return collect(config('localization.supportedLocales') ?? $this->defaultSupportedLocales());
    }

    public function otherSupportedLocales()
    {
        return $this->supportedLocales()->except($this->currentLocale());
    }

    public function defaultSupportedLocales()
    {
        return ['en' => ['name' => 'English', 'script' => 'Latn', 'native' => 'English', 'regional' => 'en_GB']];
    }

    public function isSupportedLocales($key)
    {
    	return array_key_exists($key, $this->supportedLocales());
    }

    public function hideDefaultLocaleInUrl()
    {
    	return config('localization.hideDefaultLocaleInUrl') ?? false;
    }

    public function currentLocale()
    {
        if ($this->currentLocale) {
            return $this->currentLocale;
        }

        return config('app.locale');
    }

    public function translatedRouteName($locale = null, $route = null)
    {
    	if ($locale === null) {
			$locale = $this->currentLocale();
		}

		$currentRoute     = request()->route()->uri();
		$currentRoute     = ltrim($currentRoute, $this->currentLocale() . '/');
		$translatedRoutes = app('translator')->getLoader()->load($this->currentLocale(), $this->translatedRouteFileName());

		foreach ($translatedRoutes as $key => $route) {
			if ($route === $currentRoute) {
				$route = $key;
				break;
			}
		}

		return $route;
    }

    public function translatedRoutePrefix($locale = null)
    {
		return app('translator')->getLoader()->load($locale, $this->translatedRouteFileName())[$this->currentTranslatedRouteName($locale)] ?? null;
    }

    public function currentTranslatedRouteName()
    {
    	return $this->translatedRouteName($this->currentLocale());
    }

    public function currentTranslatedRoutePrefix()
    {
    	return $this->translatedRoutePrefix($this->currentLocale());
    }

    public function localizationKeyRoute($model, $locale)
    {
    	if (is_callable(config('localization.globalLocalizationKeyRoute'))) {
    		return config('localization.globalLocalizationKeyRoute')($model, $locale);
    	}

    	return $model = $model->id;
    }

    public function unBindParams($bindedParams, $locale = null)
    {
        if ($locale === null) {
            $locale = $this->currentLocale();
        }

        $urls     = explode('/', request()->decodedPath());
        $prefixes = explode('/', request()->route()->uri());
        
        $params = [];

        for ($i = 0; $i < count($urls); $i++) {
            if ($urls[$i] !== $prefixes[$i]) {
                $params[trim($prefixes[$i], '{}')] = $urls[$i];
            }
        }

        if (array_diff_key($bindedParams, $params) === [] && array_diff_key($params, $bindedParams) === []) {
            foreach ($bindedParams as $key => &$bindParam) {
                if (method_exists($bindParam, 'localizationKeyRoute')) {
                    $bindParam = $bindParam->localizationKeyRoute($locale);
                } else {
                    $bindParam = $this->localizationKeyRoute($bindParam, $locale);
                }
            }    
        }

        return $bindedParams;
    }

    public function unBindedParams($locale = null)
    {
        if (!$this->bindedParams) {
            return null;
        }

        $bindedParams = $this->bindedParams();

        if ($locale === null) {
            $locale = $this->currentLocale();
        }

        $urls     = explode('/', request()->decodedPath());
        $prefixes = explode('/', request()->route()->uri());
        
        $params = [];

        for ($i = 0; $i < count($urls); $i++) {
            if ($urls[$i] !== $prefixes[$i]) {
                $params[trim($prefixes[$i], '{}')] = $urls[$i];
            }
        }

        if (array_diff_key($bindedParams, $params) === [] && array_diff_key($params, $bindedParams) === []) {
            foreach ($bindedParams as $key => &$bindParam) {
                if (method_exists($bindParam, 'localizationKeyRoute')) {
                    $bindParam = $bindParam->localizationKeyRoute($locale);
                } else {
                    $bindParam = $this->localizationKeyRoute($bindParam, $locale);
                }
            }    
        }

        return $bindedParams;
    }

    public function localized($locale = null)
    {
    	if ($locale === null) {
			$locale = $this->currentLocale();
		}

		$route = $this->translatedRoutePrefix($locale);

	    $bindedParams  = $this->bindedParams();

	    $unBindedParams = $this->unBindParams($bindedParams, $locale);

		foreach ($unBindedParams as $key => $value) {
		    $route = str_replace('{'.$key.'}', $value, $route);
		    $route = str_replace('{'.$key.'?}', $value, $route);
		}

		// delete empty optional arguments that are not in the $attributes array
		$route = preg_replace('/\/{[^)]+\?}/', '', $route);

		if ($locale !== $this->defaultLocale() || !$this->hideDefaultLocaleInURL()) {
			$route = $locale . '/' . $route;
		}

		return app('url')->to($route);
    }

    public function bindedParams()
    {
    	return request()->route()->parameters();
    }

    public function trans($routeName)
    {
    	$completeRouteName = "{$this->translatedRouteFileName()}.{$routeName}";
    	$routePrefix = app('translator')->trans($completeRouteName);

    	if ($routePrefix === $completeRouteName) {
    		throw new Exception ('Translated route is not found in the resources/lang/' . $this->translatedRouteFileName() . '.php');
    	}

        return $routePrefix;
    }

    public function redirectCode()
    {
        return config('localization.redirectCode') ?? 302;
    }

    public function translatedRouteFileName()
    {
    	return config('localization.translatedRoutesFileName') ?? 'routes';
    }
}
