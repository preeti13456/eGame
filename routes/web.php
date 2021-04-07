<?php

use App\Article;
use App\CollaborativeFiltering;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'MainController@home')->name('home');

Route::get('/search', 'MainController@search');

Route::get('/advancedSearch', 'MainController@advancedSearch');

Route::post('/admin/{weigths}', 'MainController@update');

Route::get('/recommendations', function () {
    /*This route will only be used by the administrator user from the Admin panel to observe the differences between the recommendation systems.*/

    $articlesByContentBasedFiltering = Article::filteredByUserPurchases();
    $articlesByCollaborativeFiltering = CollaborativeFiltering::getRecommendations();

    return view(
        'recommended_article.home',
        compact([
            'articlesByContentBasedFiltering',
            'articlesByCollaborativeFiltering',
        ])
    );
})->name('recommendations');

Route::get('{crud}/crud_search', 'MainController@crudSearch');

/*Route created automatically when generating the login and registration views with migrations.*/
Auth::routes();

Route::resource('users', 'UserController');

Route::resource('ratings', 'RatingController');

Route::resource(
    'articles',
    'ArticleController'
); /*By declaring the path as Resource, we get access to the following paths:
GET /articles => index
POST /articles => store
GET /articles/create => Form to create article
GET /articles/:id => Show an item with ID
GET /articles/:id/edit
PUT/PATCH /articles/:id  (Update)
DELETE /articles/:id (Destroy)
*/

Route::get('/cart', 'ShoppingCartController@index');

Route::post('/cart', 'ShoppingCartController@checkout')->middleware(
    'auth'
); /*With the middleware we make sure that before paying (by clicking on the CHECKOUT button in the cart) the invited user must log in.*/
Route::post('/delete_cart_item', 'InShoppingCartController@destroy');

Route::get('/delivery', 'ShoppingCartController@deliveryOptions')
    ->name('delivery')
    ->middleware('auth');

Route::post(
    '/payment_method',
    'ShoppingCartController@deliveryOptionsStore'
)->name('payment_method');

Route::get(
    '/payments/store',
    'PaymentController@store'
); /*This route is the one that Paypal returns to us automatically after accepting the payment*/

Route::post('/cancel_order/{id}', 'OrderController@cancelOrder');

Route::resource('orders', 'OrderController');

Route::resource('in_shopping_carts', 'InShoppingCartController', [
    'only' => ['store', 'destroy'],
]);

Route::resource('shopping', 'OrderController', [
    'only' => ['show'],
]); /*Route of the permanent link generated after payment of a purchase*/

Route::get('articles/images/{filename}', function ($filename) {
    /*With this path we make our images in the storage folder dump to the public folder so that they are visible from the web.*/
    $path = storage_path(
        "app/images/$filename"
    ); /*storage_path is a Laravel helper that refers to where our images folder is.*/
   
    if (!\File::exists($path)) {
        abort(404);
    } //If the image does NOT exist, a 404 error is sent.

    /*If it exists, we obtain the file with get () and the type of the file with mimeType () and then indicate it to the browser and open the image viewer and not the .PDF viewer for example.*/
    $file = \File::get($path);

    $type = \File::mimeType($path);

    $response = Response::make($file, 200); //Code 200 indicates that everything went well.

    $response->header('Content-Type', $type); //Request header.

    return $response;
});

Route::get('your_ratings', 'UserController@userRatings')->name('user_ratings');

Route::get('your_orders', 'UserController@ordersByUser')->name('user_orders');

Route::get('platform/{platform}', 'ArticleController@showByPlatform');

Route::get('account', 'UserController@account')->name('account');

Route::get(
    'profile/{id}/edit',
    'UserController@editProfile'
); /*Edit user profile*/

Route::get('rate_your_order/{id}', 'RatingController@rateYourOrder');

Route::get('payment_with_card', 'PaymentController@payWithStripe')->name(
    'stripform'
);

Route::post(
    'payment_with_card',
    'PaymentController@postPaymentWithStripe'
)->name('paywithstripe');

Route::get('searchYourOrder', 'UserController@searchYourOrder');
