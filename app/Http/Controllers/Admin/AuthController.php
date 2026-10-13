<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $remember = $request->boolean('remember');
        unset($credentials['remember']);
        $user = User::query()
            ->where('email', $credentials['email'])
            ->where('is_admin', true)
            ->where('is_locked', false)
            ->first();

        if ($user && $user->verifyAndUpgradePassword($credentials['password']) && $user->hasPermission('admin.dashboard.view')) {
            Auth::login($user, $remember);
            $request->session()->regenerate();
            $request->session()->put('auth_session_version', $user->session_version);

            return redirect()->intended('/admin');
        }

        throw ValidationException::withMessages([
            'email' => __('Email or password is incorrect.'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
