<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //

    private $transaction_model;

    public function __construct()
    {
        $this->transaction_model = new \App\Models\TransactionModel;
    }

    public function login()
    {
        return view('login.login');
    }

    public function validate_login(LoginRequest $request)
    {
        $credentials = $request->getCredentials();

        if(!Auth::validate($credentials)):
        // dd($credentials);
            return redirect()->to('/')->with("error", "Wrong credentials..!");
        endif;

        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        Auth::login($user);

        return $this->authenticated($request, $user);
    }

    protected function authenticated(Request $request, $user)
    {
        return redirect()->intended();
    }






    public function register()
    {
        return view('login.register');
    }



    public function register_user(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        dd($user);

        auth()->login($user);

        return redirect('/home')->with('success', "Account successfully registered.");
    }
}
