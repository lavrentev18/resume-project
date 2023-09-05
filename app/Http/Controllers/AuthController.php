<?php

namespace App\Http\Controllers;


use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

}
