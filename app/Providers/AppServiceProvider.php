<?php

namespace App\Providers;

use App\Repositories\Contracts\EvaluationPeriodRepositoryInterface;
use App\Repositories\Contracts\OpdRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\EloquentEvaluationPeriodRepository;
use App\Repositories\EloquentOpdRepository;
use App\Repositories\EloquentRoleRepository;
use App\Repositories\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(OpdRepositoryInterface::class, EloquentOpdRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, EloquentRoleRepository::class);
        $this->app->bind(EvaluationPeriodRepositoryInterface::class, EloquentEvaluationPeriodRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
