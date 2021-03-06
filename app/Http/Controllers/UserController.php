<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Auth;
use stdClass;

class UserController extends Controller
{
    //
    public function index() {
        $user = new stdClass();
        $user->user_id = Auth::user()->id;
        $user->user_name = Auth::user()->name;
        $user->user_email = Auth::user()->email;
        return view('user.user_setting', [
            'user' => $user
        ]);
    }
    public function update(Request $request) {
        $user = new stdClass();
        $user->user_id = Auth::user()->id;
        $user->user_name = $request->get('val-username');
        $user->user_email = $request->get('val-email');
        $user->user_password = bcrypt($request->get('val-password'));

        User::where(['id' => $user->user_id])
            ->update([
                'name'      => $user->user_name,
                'email'     => $user->user_email,
                'password'  => $user->user_password
            ]);
            
        $request->session()->flash('status', "Your information was updated successfully!");
        return view('user.user_setting', [
            'user' => $user
        ]);
    }
}
