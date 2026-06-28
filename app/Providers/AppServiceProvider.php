<?php

namespace App\Providers;

use App\Models\Tenants\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Laravel\Pennant\Feature;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // bind user tenant ke auth
        $this->app->bind(Authenticatable::class, User::class);

        // ide helper hanya local
        if (
            $this->app->environment(['local', 'development']) &&
            class_exists(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class)
        ) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    public function boot(): void
    {
        /**
         * ----------------------------------------
         * QUERY FILTER MACRO
         * ----------------------------------------
         */
        Builder::macro('filter', function (Request $request) {

            $columns = $request->filters ?? null;

            if (! $columns) {
                return $this;
            }

            $query = $this;

            foreach ($columns as $filterColumn) {

                $column = $filterColumn['column'] ?? null;
                $conditionType = $filterColumn['condition'] ?? '=';
                $value = $filterColumn['value'] ?? null;

                if (! $column || $value === null) {
                    continue;
                }

                $condition = $conditionType === 'equals'
                    ? '='
                    : $conditionType;

                if ($conditionType === 'like') {
                    $value = "%{$value}%";
                }

                $query = $query->where($column, $condition, $value);
            }

            return $query;
        });

        /**
         * ----------------------------------------
         * TENANT MIGRATION LOADER
         * ----------------------------------------
         */
        if (! empty(config('tenancy.central_domains')[0])) {
            $mainPath = database_path('migrations');
            $directories = glob($mainPath . '/*', GLOB_ONLYDIR);

            $this->loadMigrationsFrom($directories);
        }

        /**
         * ----------------------------------------
         * PENNANT FEATURE
         * ----------------------------------------
         */
        Feature::resolveScopeUsing(fn () => null);
        Feature::discover();
    }
}