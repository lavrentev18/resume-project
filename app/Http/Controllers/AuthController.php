<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class AuthController extends Controller
{
    public function register(Request $request){

       $userData =   $request->validate([
            'name'     => 'required',
            'email'    => 'required|email:rfc,dns',
            'password' => 'required|min:8'
        ]);

        $userData['password'] = Hash::make($userData['password']);


        return User::create($userData);
    }

    public function login(Request $request) {
        $userData =   $request->validate([
            'email'    => 'required|email:rfc,dns',
            'password' => 'required|min:8'
        ]);

        $user = User::where('email', '=', $userData['email'])->first();


        if (!$user || Hash::check($user->password, $userData['password'])) {
            throw new NotFoundHttpException('user not found');
        }
        $token = $user->createToken('api');

        return ['token' => $token->plainTextToken];

    }

    public function me(Request $request){
        return $request->user();
    }

}
