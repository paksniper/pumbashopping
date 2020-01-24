<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        View::composer(['home',
            'project_rest_layout.view_all_products',
            'project_rest_layout.view_all_search_products',
            'project_rest_layout.product_detail','admin.admin_registration',
            'admin.admin_login','admin.admin_forgot_password_view','admin.admin_change_password_view',
            'customer_service','terms_and_conditions','return_policy'],
            'App\Http\View\Composers\HomeComposer');

        View::composer(
            ['project_rest_layout.view_all_products',
            'project_rest_layout.view_all_search_products'],
            'App\Http\View\Composers\RestComposer');

        View::composer(
            [
                'layout.admin_app','admin.products',
                'admin.home'
            ],'App\Http\View\Composers\AdminHomeComposer'
        );
    }
}
