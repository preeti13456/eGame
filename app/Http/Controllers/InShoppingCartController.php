<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ShoppingCart;
use App\InShoppingCart;
use App\Article;
use Auth;

class InShoppingCartController extends Controller
{
    public function __construct()
    {
        $this->middleware('shoppingcart');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shopping_cart = $request->shopping_cart;

        $in_shopping_carts = InShoppingCart::where(
            'shopping_cart_id',
            $shopping_cart->id
        )->get();

        $article = Article::find($request->article_id);

        $exists = false;

        foreach ($in_shopping_carts as $in_shopping_cart) {
            //We go through all these instances to check if the article_id of the current selected article matches the article_id of any of the instances already existing in the cart.
            if ($in_shopping_cart->article_id == $request->article_id) {
                //If it exists, we increase the quantity field of that instance to 1 and set $ exists to true
                $in_shopping_cart->update([
                    'quantity' => $in_shopping_cart->quantity + 1,
                ]);
                $exists = true;
                $article->update(['quantity' => $article->quantity - 1]); //We also reduce the stock of said item by 1 again.
            }
        }

        if ($exists == false) {
            //If in the end it does not exist we create a new instance with that article.
            $response = InShoppingCart::create([
                'user_id' => null,
                'shopping_cart_id' => $shopping_cart->id,
                'article_id' => $request->article_id,
                'quantity' => 1,
            ]);
            $article->update(['quantity' => $article->quantity - 1]);
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shopping_cart_id = InShoppingCart::find($id)->shopping_cart_id;

        $in_shopping_cart = InShoppingCart::find($id);

        $article = Article::find($in_shopping_cart->article_id);

        if ($in_shopping_cart->quantity > 1) {
            $in_shopping_cart->update([
                'quantity' => $in_shopping_cart->quantity - 1,
            ]);
            $article->update(['quantity' => $article->quantity + 1]);
            return back();
        } else {
            if (
                InShoppingCart::where(
                    'shopping_cart_id',
                    $shopping_cart_id
                )->count() == 1
            ) {
                $in_shopping_cart->delete();
                $article->update(['quantity' => $article->quantity + 1]);
                return redirect('/');
            } else {
                $in_shopping_cart->delete();
                $article->update(['quantity' => $article->quantity + 1]);
                return back();
            }
        }
    }
}
