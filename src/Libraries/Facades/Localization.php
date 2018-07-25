<?php

namespace App\Libraries\Facades;

use Illuminate\Support\Facades\Facade;
use App\Libraries\Localization as LocalizationChild;

class Localization extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return LocalizationChild::class;
    }
}
