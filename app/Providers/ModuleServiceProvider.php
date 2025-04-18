<?php

namespace App\Providers;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        foreach (glob(base_path('modules/*'), GLOB_ONLYDIR) as $modulePath) {
            $moduleName = basename($modulePath);
            $langDir = $modulePath . '/resources/lang';
            if (is_dir($langDir)) {
                foreach (glob($langDir . '/*', GLOB_ONLYDIR) as $langPath) {
                    $this->loadTranslationsFrom($langDir, $moduleName);
                }
            }
        }
    }
}
