<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Repositories\Contracts\CourseRepositoryInterface::class,
            \App\Repositories\CourseRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\StudentRepositoryInterface::class,
            \App\Repositories\StudentRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\TeacherRepositoryInterface::class,
            \App\Repositories\TeacherRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\SubjectRepositoryInterface::class,
            \App\Repositories\SubjectRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\EnrollmentRepositoryInterface::class,
            \App\Repositories\EnrollmentRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
