<?php

namespace Encore\Admin\LogViewer;

use Encore\Admin\Admin;

trait BootExtension
{
    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        static::registerRoutes();

        Admin::extend('log-viwer', __CLASS__);
    }

    /**
     * Register routes for laravel-admin.
     *
     * @return void
     */
    protected static function registerRoutes()
    {
        parent::routes(function ($router) {
            /* @var \Illuminate\Routing\Router $router */
            $router->get('logs', 'Encore\Admin\LogViewer\LogController@index');
            $router->get('logs/{file}', 'Encore\Admin\LogViewer\LogController@index');
            $router->get('logs/{file}/tail', 'Encore\Admin\LogViewer\LogController@tail');
        });
    }

    /**
     * {@inheritdoc}
     */
    public static function import()
    {
        parent::createMenu('Log viwer', 'logs', 'fa-database');

        parent::createPermission('Logs', 'ext.log-viwer', 'logs*');
    }
}