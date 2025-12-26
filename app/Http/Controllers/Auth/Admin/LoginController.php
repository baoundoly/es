<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm()
    {
        $data = ['url' => route('admin.login'), 'title'=>'Admin'];
        return view('auth.admin.login', $data);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'user'   => 'required',
            'password' => 'required'
        ]);

        if(strlen($request->user) == '11'){
            $user = 'mobile_no';
            $request->merge(['mobile_no' => $request->user]);
        }else{
            $user = 'email';
            $request->merge(['email' => $request->user]);
        }

        $exist_user = Admin::where($user,$request->user)->first();
        if($exist_user && $exist_user->status == 1){
            if (Auth::attempt($request->only([$user,'password']), $request->get('remember'))){
                return redirect()->route('admin.dashboard');
            }
        }elseif($exist_user && $exist_user->status == 0){
            return redirect()->back()->with('login_error','Your are Inactive. So that,You are not allow to login');
        }

        return redirect()->back()->with('login_error','Your user information or password is incorrect');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->route('admin.login');
    }

}
