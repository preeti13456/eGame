<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\InShoppingCart;
use App\ShoppingCart;
use App\PayPal;
use App\Order;
use Auth;

class ShoppingCartController extends Controller
{
    public function __construct()
    {
        $this->middleware('shoppingcart');
    }

    public function index(Request $request)
    {
        $shopping_cart = $request->shopping_cart;

        $articles = $shopping_cart->articles()->get();

        $in_shopping_carts = $shopping_cart->inShoppingCarts()->get();

        $total = 0;

        if ($articles->count() != 0) {
            return view('shopping_cart.index', [
                'articles' => $articles,
                'in_shopping_carts' => $in_shopping_carts,
                'total' => $total,
            ]);
        } else {
            return back();
        }
    }

    public function checkout(Request $request)
    {
        $shopping_cart = $request->shopping_cart;

        $shopping_cart
            ->where('id', $shopping_cart->id)
            ->update([
                'user_id' => Auth::user()->id,
            ]); /*Con ésto, sabemos que usuario ha pagado que carrito*/ /*MIGRACION 2019_09_07_195544_add_user_id_column_to_shopping_carts*/ /*Añadimos el user_id del usuario autenticado antes de realizar el pago*/

        $paypal = new PayPal($request->shopping_cart);

        $payment = $paypal->generate();

        return redirect($payment->getApprovalLink());
    }

    public function show($id)
    {
    }

    public function deliveryOptions()
    {
        $order = new Order();
        return view('shopping_cart.delivery_options', compact('order'));
    }

    public function deliveryOptionsStore(Request $request)
    {
        $shopping_cart = $request->shopping_cart;

        $this->validate($request, [
            'line1' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|integer|max:99999',
            'country_code' => 'required|string',
            'state' => 'required|string',
        ]);

        $order = Order::create([
            'recipient_name' => $request['recipient_name'],
            'line1' => $request['line1'],
            'line2' => $request['line2'],
            'city' => $request['city'],
            'postal_code' => $request['postal_code'],
            'state' => $request['state'],
            'country_code' => $request['country_code'],
            'total' => $shopping_cart->total(),
            'email' => Auth::user()->email,
            'user_id' => Auth::user()->id,
        ]);

        return view('shopping_cart.payment_method', compact('order'));
    }
}
