<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class AuthenticationController extends Controller
{

    public function showLogin()
    {
        return view('authentication.login');
    }

    public function login(Request $req)
    {
        $this->validate($req, [
            'email'     => 'required',
            'password'  => 'required|min:8'
        ]);

        $account = DB::table('users')
            ->where(function ($query) use ($req) {
                $query->where('email', $req->email)
                    ->orWhere('username', $req->email);
            })->first();

        if ($account) {
            if (Hash::check($req->password, $account->password)) {
                session()->put('id', $account->id);
                session()->put('name', $account->name);
                session()->put('role', $account->role);
                session()->put('email', $account->email);
                $redirectTo = $account->role === 'customers' ? route('customers.index') : route('dashboard');

                return response()->json([
                    'message' => 'success',
                    'text'    => 'Welcome Back ' . ($account->fullname ?? $account->name) . ' !',
                    'redirect_to' => $redirectTo
                ], 200);
            } else {
                return response()->json([
                    'message' => 'error',
                    'text'    => 'Wrong password, please try again !'
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'error',
                'text'    => 'E-Mail or Username isn\'t registered yet'
            ], 500);
        }
    }
    public function logout()
    {
        session()->flush();
        return redirect('/')->with("success", "Logged Out");
    }
}
