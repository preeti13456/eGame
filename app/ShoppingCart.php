<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    protected $fillable = ['status'];

    public function inShoppingCarts()
    {
        return $this->hasMany('App\InShoppingCart');
    }

    public function order()
    {
        return $this->hasOne('App\Order')->first();
    }

    public function articles()
    {
        return $this->belongsToMany('App\Article', 'in_shopping_carts');
    }

    public function articlesSize()
    {
        return $this->inShoppingCarts()
            ->get()
            ->sum('quantity');
    }

    public function total()
    {
        $sum = 0;
        $in_shopping_carts = $this->inShoppingCarts()->get();
        $articles = $this->articles()->get();

        for ($i = 0; $i < $articles->count(); $i++) {
            $sum =
                $sum + $in_shopping_carts[$i]->quantity * $articles[$i]->price;
        }

        return $sum;
    }

    public function totalEUR()
    {
        return $this->total() / 100;
    }

    public static function findOrCreateBySessionID($shopping_cart_id)
    {
        if ($shopping_cart_id) {
            return ShoppingCart::findBySession($shopping_cart_id);
        } else {
            return ShoppingCart::createWithoutSession();
        }
    }

    public static function findBySession($shopping_cart_id)
    {
        return ShoppingCart::find($shopping_cart_id);
    }

    public static function createWithoutSession()
    {
        return ShoppingCart::create([
            'status' => 'incompleted',
        ]);
    }
}
