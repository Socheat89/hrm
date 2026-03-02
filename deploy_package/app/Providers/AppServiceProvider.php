<?php

namespace App\Providers;

use App\Models\CompanySetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // ensure generated URLs use the current app.url and HTTPS when appropriate
        // especially useful when testing via a tunnel service (ngrok) that provides an HTTPS URL.
        if ($this->app->runningInConsole() === false) {
            // when serving through a proxy/tunnel like ngrok the request host will be
            // the external URL (https://...).  Force URL generator to use that so all
            // assets/routes/forms are generated with the correct scheme and hostname.
            $root = request()->getSchemeAndHttpHost();
            if ($root) {
                \URL::forceRootUrl($root);
                \URL::forceScheme(request()->getScheme());
            } else {
                // fallback to configured app.url if no request available
                \URL::forceRootUrl(config('app.url'));
                if (str_starts_with(config('app.url'), 'https://')) {
                    \URL::forceScheme('https');
                }
            }
        }

        View::composer('*', function ($view): void {
            $setting = Cache::remember('ui_company_setting', 300, static function () {
                return CompanySetting::query()->first();
            });

            $view->with('uiCompanySetting', $setting);
        });
    }
}
