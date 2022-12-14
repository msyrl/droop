<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\AuthSigninRequest;
use App\Http\Requests\AuthVerifyRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
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

        event(new Registered($user));

        return Response::redirectTo('/auth/signin')
            ->with('success', __('Successfully registered, please check your email for verification.'));
    }

    /**
     * @param AuthVerifyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function verify(AuthVerifyRequest $request)
    {
        /** @var User */
        $user = User::query()
            ->where('email', $request->get('email'))
            ->firstOrFail();

        abort_unless(
            $user->checkVerificationHash(
                $request->get('hash')
            ),
            403
        );

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        Auth::login($user);

        return Response::redirectTo('/');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function verifyNotification()
    {
        return Response::view('auth.verify-notification');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function resendVerifyNotification(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'exists:users,email',
            ],
        ]);

        /** @var User */
        $user = User::query()
            ->where('email', $request->get('email'))
            ->firstOrFail();

        $user->sendEmailVerificationNotification();

        return Response::redirectTo('/auth/verify-notification')
            ->with('status', __('Success resend verify notification'));
    }
}
