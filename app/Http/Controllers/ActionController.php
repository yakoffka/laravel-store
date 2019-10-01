<?php

namespace App\Http\Controllers;

use App\{Action, Product, User, Order};

class ActionController extends Controller
{

    // расставить разрешения!!!
    public function __construct() {
        // $this->middleware(['auth', 'permission:view_actions']);
        $this->middleware('auth');
    }


    /**
     * Display a listing of the action all users.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if ( auth()->user()->cannot('view_actions'), 403 );
        $actions = Action::orderBy('created_at', 'desc')->paginate();
        return view('dashboard.adminpanel.actions.index', compact('actions'));
    }
    /**
     * Display a listing of the action all users.
     * @return \Illuminate\Http\Response
     */
    public function show(Action $action)
    {
        abort_if ( auth()->user()->cannot('view_actions'), 403 );
        return view('dashboard.adminpanel.actions.show', compact('action'));
    }


    /**
     * Display a listing of the action users (type === user).
     * @return \Illuminate\Http\Response
     */
    public function users()
    {
        abort_if ( auth()->user()->cannot('view_users'), 403 );

        $actions = Action::where('type', 'user')
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('dashboard.adminpanel.actions.users', compact('actions'));
    }
    /**
     * Display a listing of the action of user.
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function user(User $user)
    {
        abort_if ( auth()->user()->cannot('view_users') and auth()->user()->id != $user->id, 403 );

        $actions = Action::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('dashboard.adminpanel.actions.user', compact('user', 'actions'));
    }


    /**
     * Show list of actions with all orders.
     * @return \Illuminate\Http\Response
     */
    public function orders()
    {
        $actions = Action::where('type', 'order')
            ->orderBy('created_at', 'desc');
            
        if ( auth()->user()->cannot('view_orders') ) {
            $actions = $actions->where('user_id', auth()->user()->id );
        }

        $actions = $actions->paginate();

        return view('dashboard.adminpanel.actions.orders', compact('actions'));
    }
    /**
     * Show list of actions with order.
     * @param  \App\order  $order
     * @return \Illuminate\Http\Response
     */
    public function order(Order $order)
    {
        abort_if ( auth()->user()->cannot('view_orders') and auth()->user()->id != $order->user_id, 403 );

        $actions = Action::where('type', 'order')
            ->where('type_id', $order->id)
            ->orderBy('created_at', 'desc');

        if ( auth()->user()->cannot('view_orders') ) {
            $actions = $actions->where('user_id', auth()->user()->id );
        }

        $actions = $actions->paginate();

        return view('dashboard.adminpanel.actions.order', compact('order', 'actions'));
    }


    /**
     * Show list of actions with all products.
     * @return \Illuminate\Http\Response
     */
    public function products()
    {
        abort_if ( auth()->user()->cannot('view_products'), 403 );

        $actions = Action::where('type', 'product')
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('dashboard.adminpanel.actions.products', compact('actions'));
    }
    /**
     * Show list of actions with product.
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function product(product $product)
    {
        // $actions = Action::where('type', 'product')
        //     ->where('type_id', $product->id)
        //     ->orderBy('created_at', 'desc');
        // if ( auth()->user()->cannot('view_products') ) {
        //     $actions = $actions->where('user_id', auth()->user()->id );
        // }
        // $actions = $actions->paginate();

        abort_if ( auth()->user()->cannot('view_products'), 403 );

        $actions = Action::where('type', 'product')
            ->where('type_id', $product->id)
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('dashboard.adminpanel.actions.product', compact('product', 'actions'));
    }


    /**
     * Show list of actions with all categories.
     * @return \Illuminate\Http\Response
     */
    public function categories()
    {
        // $actions = Action::orderBy('created_at', 'desc');
        // if ( auth()->user()->cannot('view_categories') ) {
        //     $actions = $actions->where('user_id', auth()->user()->id );
        // }
        // $actions = $actions->paginate();

        abort_if ( auth()->user()->cannot('view_categories'), 403 );

        $actions = Action::where('type', 'category')
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('dashboard.adminpanel.actions.categories', compact('actions'));
    }
    /**
     * Show list of actions with category.
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function category(category $category)
    {
        // $actions = Action::where('type', 'category')
        //     ->where('type_id', $category->id)
        //     ->orderBy('created_at', 'desc');
        // if ( auth()->user()->cannot('view_categories') ) {
        //     $actions = $actions->where('user_id', auth()->user()->id );
        // }
        // $actions = $actions->paginate();

        abort_if ( auth()->user()->cannot('view_categories'), 403 );

        $actions = Action::where('type', 'category')
            ->where('type_id', $category->id)
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('dashboard.adminpanel.actions.category', compact('category', 'actions'));
    }


    /**
     * Show list of change settings.
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        abort_if ( auth()->user()->cannot('view_settings'), 403 );

        $actions = Action::where('type', 'setting')
            ->orderBy('created_at', 'desc')
            ->paginate();
            
        return view('dashboard.adminpanel.actions.categories', compact('actions'));
    }


    /**
     * Show list of registrations users.
     * @return \Illuminate\Http\Response
     */
    public function registrations()
    {
        abort_if ( auth()->user()->cannot('view_users'), 403 );

        $actions = Action::where('type_id', 'user' )
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('dashboard.adminpanel.actions.categories', compact('actions'));
    }

}
