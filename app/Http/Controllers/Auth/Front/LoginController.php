<?php

namespace App\Http\Controllers\Auth\Front;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::ADMIN_HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @return Factory|View
     */
    protected function showAdminLoginForm()
    {
        return view('auth.login');
    }

//    /**
//     * @return Factory|View
//     */
//    protected function showLoginForm()
//    {
//        return view('web.auth.login');
//    }

    /**
     * @return Factory|View
     */
    public function showLoginForm(): View
    {
        storeIntendedUrlFromPrevious();

        return view('front_web.auth.login');
    }

    /**
     * @return Factory|View
     */
    protected function employeeLogin()
    {
        storeIntendedUrlFromPrevious();

        return view('front_web.auth.login');
    }

    /**
     * @return Factory|View
     */
    protected function candidateLogin()
    {
        storeIntendedUrlFromPrevious();

        return view('front_web.auth.login');
    }

    protected function sendLoginResponse(Request $request): RedirectResponse
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if (Auth::user()->hasRole('Employer')) {
            $this->redirectTo = RouteServiceProvider::EMPLOYER_HOME;
        } elseif (Auth::user()->hasRole('Candidate')) {
            $this->redirectTo = RouteServiceProvider::CANDIDATE_HOME;
        } else {
            Auth::logout();

            return redirect()->route('front.user.login')->withInput()->withErrors([
                'error' => __('auth.failed'),
            ]);
        }

        $redirectUrl = resolveIntendedRedirectUrl($this->redirectPath(), Auth::user());

        if (isset($request->remember)) {
            return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->to($redirectUrl)
                    ->withCookie(\Cookie::make('email', $request->email, 3600))
                    ->withCookie(\Cookie::make('password', $request->password, 3600))
                    ->withCookie(\Cookie::make('remember', 1, 3600));
        }

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->to($redirectUrl)
                ->withCookie(\Cookie::forget('email'))
                ->withCookie(\Cookie::forget('password'))
                ->withCookie(\Cookie::forget('remember'));
    }
}
