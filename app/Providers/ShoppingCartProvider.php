<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\ShoppingCart;
use App\Article;

class ShoppingCartProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            $shopping_cart_id = \Session::get('shopping_cart_id');
            $shopping_cart = ShoppingCart::findOrCreateBySessionID(
                $shopping_cart_id
            );

            \Session::put('shopping_cart_id', $shopping_cart->id);

            $view->with('articlesCount', $shopping_cart->articlesSize());

            $view->with('articlesCollection', Article::All());
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
