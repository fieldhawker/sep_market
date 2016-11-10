<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(
          \App\Repositories\UsersRepositoryInterface::class,
          function ($app) {
              return new \App\Repositories\UsersRepository(
                new \App\Models\User
              );
          }
        );
        $this->app->bind(
          \App\Repositories\OperationLogsRepositoryInterface::class,
          function ($app) {
              return new \App\Repositories\OperationLogsRepository(
                new \App\Models\OperationLogs
              );
          }
        );
        $this->app->bind(
          \App\Repositories\ExclusivesRepositoryInterface::class,
          function ($app) {
              return new \App\Repositories\ExclusivesRepository(
                new \App\Models\Exclusives
              );
          }
        );
        
    }
}
