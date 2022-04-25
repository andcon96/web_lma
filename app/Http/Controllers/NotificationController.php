<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function notifread(Request $req)
    {
        auth()->user()
            ->unreadNotifications
            ->when($req->input('id'), function ($query) use ($req) {
                return $query->where('id', $req->input('id'));
            })
            ->markAsRead();

        // DB::table('notifications')->where('id', '=', $req->input('id'))->delete();

        return response()->noContent();
    }

    public function notifreadall(Request $req)
    {

        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        //DB::table('notifications')->where('id', '=', $req->input('id'))->delete();

        return response()->noContent();
    }

    public function changedomain(Request $req){
        $req->session()->put('domain',$req->domain);

        return true;
    }
}
