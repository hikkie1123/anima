<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Item;
use App\User;
use App\Follow;
use App\Notification;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::check()) {
                $notification = new Notification();
                $notifications_count = $notification->checkUserNotifications(Auth::id());
                $request->session()->put('notifications_count', $notifications_count);
            }
            return $next($request);
        });
    }

    /**
     * Search by keywords
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $request->flash();

        $item = new Item();
        $user = new User();
        $items = array();
        $users = array();

        $items = $item->getSearchByItem($keyword);
        $items_count = $item->getSearchByItemCount($keyword);
        $users = $user->getSearchByUser($keyword);
        $users_count = $user->getSearchByUserCount($keyword);

        if(count($users) > 0){
            foreach ($users as $user) {
                $follow = new Follow();
                $my_follow = $follow->getMyFollow($user->id);

                if(count($my_follow) > 0){
                    $user->follow_id = $my_follow[0]->id;
                    $user->follow_status = "active";
                }else{
                    $user->follow_id = "";
                    $user->follow_status = "";
                }
            }
        }

        return view('search', [
            'items' => $items,
            'items_count' => $items_count,
            'users' => $users,
            'users_count' => $users_count,
            'keyword' => $keyword,
        ]);
    }
}
