<?php

    namespace Modules\FullSuscription\Providers;

    use Config;
    use Illuminate\Database\Eloquent\Factory;
    use Illuminate\Support\ServiceProvider;

    class SuscriptionServiceProvider extends ServiceProvider
    {
        /**
         * Boot the application events.
         *
         * @return void
         */
        public function boot()
        {
            $this->registerTranslations();
            $this->registerConfig();
            $this->registerViews();
            $this->registerFactories();
            $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        }

        /**
         * Register translations.
         *
         * @return void
         */
        public function registerTranslations()
        {
            $langPath = resource_path('lang/modules/full_suscription');

            if (is_dir($langPath)) {
                $this->loadTranslationsFrom($langPath, 'full_suscription');
            } else {
                $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'full_suscription');
            }
        }

        /**
         * Register config.
         *
         * @return void
         */
        protected function registerConfig()
        {
            $this->publishes([
                __DIR__ . '/../Config/config.php' => config_path('full_suscription.php'),
            ], 'config');
            $this->mergeConfigFrom(
                __DIR__ . '/../Config/config.php', 'full_suscription'
            );
        }

        /**
         * Register views.
         *
         * @return void
         */
        public function registerViews()
        {
            $viewPath = resource_path('views/modules/full_suscription');

            $sourcePath = __DIR__ . '/../Resources/views';

            $this->publishes([
                $sourcePath => $viewPath
            ], 'views');

            $this->loadViewsFrom(array_merge(array_map(function ($path) {
                return $path . '/modules/full_suscription';
            }, Config::get('view.paths')), [$sourcePath]), 'full_suscription');
        }

        /**
         * Register an additional directory of factories.
         *
         * @return void
         */
        public function registerFactories()
        {
            if (!app()->environment('production') && $this->app->runningInConsole()) {
                app(Factory::class)->load(__DIR__ . '/../Database/factories');
            }
        }

        /**
         * Register the service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->app->register(RouteServiceProvider::class);
        }

        /**
         * Get the services provided by the provider.
         *
         * @return array
         */
        public function provides()
        {
            return [];
        }
    }
