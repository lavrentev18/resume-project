<?php

namespace App\Http\Controllers;


use App\Http\Requests\UserForgotPasswordRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class AuthController extends Controller
{
    public function register(UserRegisterRequest $request){
       $userData = $request->validated();

       $userData['password'] = Hash::make($userData['password']);

       return new UserResource(User::create($userData));
    }

    public function login(UserLoginRequest $request) {
        $userData =   $request->validated();

        $user = User::where('email', '=', $userData['email'])->first();

        if (!$user || !Hash::check($userData['password'], $user->password)) {
            throw new NotFoundHttpException('Проверьте правильность введенных данных');
        }
        $token = $user->createToken('api');

        return ['token' => $token->plainTextToken];

    }

    public function me(Request $request){
        return new UserResource($request->user());
    }

    public function forgotPassword(UserForgotPasswordRequest $request) {
//        \Mail::raw('Text to e-mail', function($message) {
//            $message->from('us@example.com', 'Laravel');
//
//            $message->to('foo@example.com')->cc('bar@example.com');
//        });
        $request->validated();
        $urlToReset = null;
//
        $status = \Password::sendResetLink(
            $request->only('email'),
            function ($user, $token) use (&$urlToReset) {
                // TODO: Сделать письмо через класс Mail
                // Записать в переменную $urlToReset (пример: "{url из конфига}/api/reset-password/{token}")
            }
        );

        return ['url' => $urlToReset];
//
//        return $status === \Password::RESET_LINK_SENT
//            ? back()->with(['status' => __($status)])
//            : back()->withErrors(['email' => __($status)]);
    }
}
