<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\AuthSigninRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class AuthController
{
    /**
     * Undocumented function
     *
     * @param AuthSigninRequest $authSigninRequest
     * @return \Illuminate\Http\Response
     */
    public function signin(AuthSigninRequest $authSigninRequest)
    {
        /** @var User */
        $user = User::query()
            ->where('email', $authSigninRequest->get('email'))
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        if (!Hash::check($authSigninRequest->get('password'), $user->password)) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        if (!$user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => __('auth.unverified'),
            ]);
        }

        $authSigninRequest->session()->regenerate();

        Auth::login($user, $authSigninRequest->get('remember'));

        return Response::redirectTo('/');
    }

    /**
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function signout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Response::redirectTo('/');
    }

    /**
     * @param AuthRegisterRequest $authRegisterRequest
     * @return \Illuminate\Http\Response
     */
    public function register(AuthRegisterRequest $authRegisterRequest)
    {
        /** @var User */
        $user = User::create(
            $authRegisterRequest->validated()
        );

        return Response::redirectTo('/')
            ->with('success', __('Successfully registered, please check your email for verification.'));
    }
}
