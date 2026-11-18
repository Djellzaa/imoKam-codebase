<?php

namespace Theme\Homzen\Providers;

use Theme\Homzen\Forms\ThemeCustomScriptsForm;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(ThemeCustomScriptsForm::class);
        $this->app->make(ThemeCustomScriptsForm::class);
    }
}