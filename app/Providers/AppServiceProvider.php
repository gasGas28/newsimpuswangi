<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\MaintenanceModeManager;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Routing\Redirector;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            'Illuminate\Contracts\Foundation\MaintenanceMode',
            fn($app) => $app->make(MaintenanceModeManager::class)
        );
    }

    public function boot(): void
    {
        if (str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        Request::macro('validate', function (
            array $rules,
            array $messages = [],
            array $attributes = []
        ) {
            return Validator::make(
                $this->all(),
                $rules,
                $messages,
                $attributes
            )->validate();
        });

                // FormRequest resolver
        $this->app->resolving(FormRequest::class, function ($request, $app) {
            $request = FormRequest::createFrom(
                $app['request'],
                $request
            );

            $request->setContainer($app)
                ->setRedirector($app->make(Redirector::class));
        });

        // Auto validate FormRequest
        $this->app->afterResolving(
            ValidatesWhenResolved::class,
            function ($resolved) {
                $resolved->validateResolved();
            }
        );

    }
}
