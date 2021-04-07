<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Order;
use App\Rating;
use App\Article;
use App\OrderedArticle;
use Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin', [
            'except' => [
                'ordersByUser',
                'userRatings',
                'account',
                'editProfile',
                'update',
                'searchYourOrder',
            ],
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id', 'DESC')->paginate(5);
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User();
        return view('user.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string|max:70',
            'last_name' => 'required|string|max:80',
            'email' => 'required|string|email|max:50|unique:Users',
            'password' => 'required|string|min:6|confirmed',
            'address' => 'required|string|max:150',
            'city' => 'required|string|max:80',
            'postal_code' => 'required|integer|max:99999',
            'telephone' => 'required|integer',
            'role' => 'required',
        ]);

        User::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'address' => $request['address'],
            'city' => $request['city'],
            'postal_code' => $request['postal_code'],
            'telephone' => $request['telephone'],
            'role' => $request['role'],
        ]);

        return redirect('users')->with(
            'success',
            'User created succsessfully!'
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role == 'Admin') {
            $this->validate($request, [
                'first_name' => 'required|string|max:70',
                'last_name' => 'required|string|max:80',
                'email' => 'required|string|email|max:50',
                'password' => 'required|string|min:6|confirmed',
                'address' => 'required|string|max:150',
                'city' => 'required|string|max:80',
                'postal_code' => 'required|integer|max:99999',
                'telephone' => 'required|integer',
                'role' => 'required',
            ]);

            User::find($id)->update([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
                'address' => $request['address'],
                'city' => $request['city'],
                'postal_code' => $request['postal_code'],
                'telephone' => $request['telephone'],
                'role' => $request['role'],
            ]);

            return redirect('users')->with(
                'success',
                'User updated successfully!'
            );
        } else {
            $this->validate($request, [
                'first_name' => 'required|string|max:70',
                'last_name' => 'required|string|max:80',
                'email' => 'required|string|email|max:50',
                'password' => 'required|string|min:6|confirmed',
                'address' => 'required|string|max:150',
                'city' => 'required|string|max:80',
                'postal_code' => 'required|integer|max:99999',
                'telephone' => 'required|integer',
            ]);

            User::find($id)->update([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
                'address' => $request['address'],
                'city' => $request['city'],
                'postal_code' => $request['postal_code'],
                'telephone' => $request['telephone'],
            ]);

            return redirect('account')->with(
                'success',
                'Profile updated successfully!'
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        if (Auth::user()->role == 'Admin') {
            return redirect('users')->with(
                'delete',
                'User deleted successfully!'
            );
        } else {
            return redirect('/')->with(
                'delete',
                'User account deleted successfully!'
            );
        }
    }

    public function account()
    {
        $user = Auth::user();
        $ratings = Rating::userRatings();
        $comments = Rating::userComments();
        $orders = Order::ordersByUser();
        $totalRatings = Rating::totalUserRatings();
        $totalOrders = Order::totalUserOrders();
        return view(
            'user.account',
            compact(
                'user',
                'orders',
                'ratings',
                'comments',
                'totalRatings',
                'totalOrders'
            )
        );
    }

    public function editProfile($id)
    {
        $user = User::find($id);
        return view('user.edit_profile', compact('user'));
    }

    public function userRatings()
    {
        $ratings = Rating::userRatings();
        $total = Rating::totalUserRatings();
        return view('user.your_ratings', compact('ratings', 'total'));
    }

    public function ordersByUser()
    {
        $ordersByUser = Order::ordersByUser();

        $articlesIdByOrder = [];

        $orderIdByUser = [];

        foreach ($ordersByUser as $orderByUser) {
            $articlesByOrder = Order::articlesByOrder($orderByUser->id);
            $orderIdByUser[] = $orderByUser->id;
            foreach ($articlesByOrder as $articleByOrder) {
                $articlesIdByOrder[] = $articleByOrder->article_id;
            }
        }

        $articlesIdByOrder = array_unique($articlesIdByOrder);
        $articles = Article::wherein('id', $articlesIdByOrder)->get();

        $orderedArticles = OrderedArticle::wherein(
            'order_id',
            $orderIdByUser
        )->get();
        //print_r($articles);

        return view(
            'user.your_orders',
            compact(['total', 'ordersByUser', 'articles', 'orderedArticles'])
        );
    }

    public function searchYourOrder(Request $request)
    {
        $total = Order::totalUserOrders();

        $search = $request->get('searchYourOrder');

        $ordersByUser = Order::where(
            'custom_id',
            'like',
            '%' . $search . '%'
        )->first();

        $orderedArticles = OrderedArticle::all();

        $articles = Article::all();

        if ($ordersByUser != null) {
            if ($ordersByUser->user_id == Auth::user()->id) {
                $ordersByUser = Order::where(
                    'custom_id',
                    'like',
                    '%' . $search . '%'
                )->paginate(1);
                return view(
                    'user.your_orders',
                    compact(
                        'total',
                        'ordersByUser',
                        'articles',
                        'orderedArticles'
                    )
                );
            } else {
                \Session::put(
                    'error',
                    'There is no results with "' . $search . '"'
                );
                return back();
            }
        } else {
            \Session::put(
                'error',
                'There is no results with "' . $search . '"'
            );
            return back();
        }
    }
}
