<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\InShoppingCart;
use App\OrderedArticle;
use Auth;

class Order extends Model
{
    protected $fillable = ['recipient_name', 'line1', 'line2', 'city', 'country_code', 'state', 'postal_code', 'payment_method', 'email', 'user_id', 'status', 'custom_id', 'total'];

    public static function articles(){
        
        return $this->belongsToMany('App\Article', 'ordered_articles');
    }

    public function scopeLatest($query){  
        return $query->orderID()->monthly();
    }

    public function scopeOrderID($query){
        return $query->orderBy('id', 'desc');
    }

    public function scopeMonthly($query){
        return $query->whereMonth('created_at','=', date('m'));   
    }
    /*
    public function scopeArticlesOrder($query){    
        $articles = InShoppingCart::where('shopping_cart_id', $this->$shopping_cart_id)->get();

        return $articles;
    }
    */
    public function approve(){
        $this->updateCustomIDAndStatus();
    }

    public function updateCustomIDAndStatus(){
        $this->custom_id = $this->generateCustomID();
        $this->status = 'Approved';
        $this->save();
    }

    public function generateCustomID(){
        return md5("$this->id  $this->updated_at"); 
    }

    public function address(){
        return "$this->line1 $this->line2";
    }

    public static function  totalIncomes(){   
        return Order::sum('total');
    }

    public static function  totalMonth(){   
        return Order::monthly()->sum('total');
    }

    public static function  totalMonthCount(){  
        return Order::monthly()->count();
    }

    public static function createFormPayPalResponse($response, $shopping_cart){

    	$payer = $response->payer;

    	$orderData = (array) $payer->payer_info->shipping_address; 
    	/*[
    		"line1" => "Calla La Rosa 5",
    		"recipient_name" => "Fran"
    		"postal_code" => "21456"
    	]
		*/
        $orderData = $orderData[key($orderData)];

		
		$orderData["email"]   = $payer->payer_info->email;
        $orderData["user_id"] = Auth::user()->id;   
        //$order = Order::where('user_id', Auth::user()->id)->get()->last();  
        $order = Order::orderBy('id', 'desc')->first();  
        
        $order->update([
            'recipient_name' => $orderData['recipient_name'],
            'line1' => $orderData['line1'],
            'city' => $orderData['city'],
            'postal_code' => $orderData['postal_code'],
            'state' => $orderData['state'],
            'country_code' => $orderData['country_code'],
            'email' => $orderData['email'], 
            'user_id' => $orderData['user_id']
        ]);

    	return $order;
    }

    public static function totalUserOrders(){
        return count(Order::where('user_id', Auth::user()->id)->get());
    } 

    public static function ordersByUser(){
        $ordersByUser = Order::orderBy('created_at', 'DESC')->where('user_id', Auth::user()->id)->paginate(4);
        return $ordersByUser;
    }

    public static function articlesByOrder($id){
        $articlesByOrder = OrderedArticle::where('order_id', $id)->get();
        return $articlesByOrder;
    }
}
