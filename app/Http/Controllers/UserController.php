<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Auth;
use stdClass;

class UserController extends Controller
{
    protected function getCompanies()
    {
        if (Auth::user()->role == 1) {
            return Company::get()->all();
        } else {
            return Company::where('id', '=', Auth::user()->company_id)->get()->all();
        }
    }

    protected function getRoles()
    {
        if (Auth::user()->role == 1) {
            return Role::orderBy('id', 'DESC')->get()->all();
        } else {
            return Role::where('id', '>', 2)->get()->all();
        }
    }

    protected function getUsers()
    {
        if (Auth::user()->role == 1) {
            return User::with('company')->with('permissions');
        } else {
            return User::with('company')->with('permissions')
                        ->where('role', '>', 2)
                        ->where('company_id', '=', Auth::user()->company_id);
        }
    }

    protected function isGetableUser($user)
    {
        if (Auth::user()->role == 1) {
            return true;
        }

        if (Auth::user()->role > 2 || Auth::user()->company_id != $user->company_id) {
            return false;
        }

        return true;
    }

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

    public function list(Request $request)
    {
        $this->authorize('manage-user');

        $roles = $this->getRoles();
        $companies = $this->getCompanies();
        $users = $this->getUsers();

        if ($request->has('user-name')) {
            $user_name = $request->input('user-name');
            $users->where('name', 'like', '%' . $user_name . '%');
        } else {
            $user_name = "";
        }

        if ($request->has('user-email')) {
            $user_email = $request->input('user-email');
            $users->where('email', 'like', '%' . $user_email . '%');
        } else {
            $user_email = "";
        }

        if ($request->has('user-company') && !empty($request->input('user-company'))) {
            $user_company = $request->input('user-company');
            $users->where('company_id', '=', $user_company);
        } else {
            $user_company = "";
        }

        if ($request->has('user-role') && !empty($request->input('user-role'))) {
            $user_role = $request->input('user-role');
            $users->where('role', '=', $user_role);
        } else {
            $user_role = "";
        }

        $users = $users->get();
                
        return view('user.list', compact('roles', 'companies', 'users', 'user_name', 'user_email', 'user_company', 'user_role'));
    }

    public function getAddPage(Request $request)
    {
        $this->authorize('manage-user');
        
        $companies = $this->getCompanies();
        $roles = $this->getRoles();
        
        return view('user.user', compact('companies', 'roles'));
    }

    public function saveUser(Request $request)
    {
        $this->authorize('manage-user');

        if (!$request->has('name') || 
            !$request->has('email') || 
            !$request->has('password') || 
            !$request->has('confirm-password') || 
            !$request->has('company') || 
            !$request->has('role')) {
            $request->session()->flash('error', "Sorry, your input not validation! Please check your input.");
            return back()->withInput();
        }

        $id = $request->input('id');
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $confirm_password = $request->input('confirm-password');
        $company = $request->input('company');
        $role = $request->input('role');

        if ($id) {
            $existed_user = User::where('email', '=', $email)->get()->first();
            $user = User::find($id);

            if ($existed_user && $existed_user->id != $user->id) {
                $request->session()->flash('error', "Sorry, email already exists.");
                return back()->withInput();
            }

            $user->name = $name;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->company_id = $company;
            $user->role = $role;
            $user->save();

            $request->session()->flash('success', "User was updated successfull!");
        } else {
            $user = User::where('email', '=', $email)->get()->first();
            if ($user) {
                $request->session()->flash('error', "Sorry, email already exists.");
                return back()->withInput();
            }

            if ($password != $confirm_password) {
                $request->session()->flash('error', "Sorry, password does not match.");
                return back()->withInput();
            }

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'company_id' => $company,
                'role' => $role
            ]);
    
            $request->session()->flash('success', "New user account was created for " . $email);
        }
        
        return $this->list($request);
    }

    public function getUser(Request $request)
    {
        $this->authorize('manage-user');

        $id = $request->input('id');
        $user = User::with('roles')->with('company')->with('permissions')->find($id);

        if (!$this->isGetableUser($user)) {
            return response()->json([
                'type' => 'error',
                'message' => 'You can\'t get the user info.'
            ]);
        }

        return response()->json([
            'type' => 'success',
            'user' => $user
        ]);
    }

    public function editUser(Request $request)
    {
        $this->authorize('manage-user');

        $id = $request->route()->parameter('id');

        $companies = $this->getCompanies();
        $roles = $this->getRoles();
        
        $user = User::with('company')->with('roles')->find($id);

        if (!$this->isGetableUser($user)) {
            abort(401);
        }
        
        return view('user.user', compact('user', 'companies', 'roles'));
    }

    public function removeUser(Request $request)
    {
        $this->authorize('manage-user');

        $id = $request->route()->parameter('id');

        $user = User::find($id);

        if (!$this->isGetableUser($user)) {
            abort(401);
        }
        
        $res = $user->delete();
        if ($res) {
            $request->session()->flash('success', 'User removed successfully. (ID: ' . $id . ')');
        } else {
            $request->session()->flash('error', 'Can\'t remove this user at this time. (ID: ' . $id . ') Please retry later.');
        }
        return $this->list($request);
    }
}
