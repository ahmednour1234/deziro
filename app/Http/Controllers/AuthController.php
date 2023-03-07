<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;


class AuthController extends Controller
{

    public function index()
    {
        return view('admin.auth.viewLogin');
    }

    /**
     * Handle account login request
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        $this->checkTooManyFailedAttempts();

        $this->validate(request(), [
            'email'    => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::validate($credentials)) {
            session()->flash('fail', trans('These credentials do not match our records.'));
            return redirect()->to('login');
        }
        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        // if ($user->status == 0) {
        //     session()->flash('fail', trans('Your account has been disabled.'));
        //     return redirect()->to('login');
        // }

        if ($user->is_ban == 1) {
            session()->flash('fail', trans('Your account has been banned.'));
            return redirect()->to('login');
        }

        if ($user->is_active == 0) {
            session()->flash('fail', trans('Your account has been deactivated.'));
            return redirect()->to('login');
        }

        Auth::login($user);

        return redirect()->intended();
    }

    /**
     * Log out account user.
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function logout()
    {
        $user = Auth::user();

        Session::flush();

        Auth::logout();

        return redirect('login');
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower(request('email')) . '|' . request()->ip();
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     */
    public function checkTooManyFailedAttempts()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 2)) {
            return;
        }

        throw new Exception('IP address banned. Too many login attempts.');
    }
}
