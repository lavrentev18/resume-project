<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    //
    public function index() {
        return UserResource::collection(User::all());
    }

    public function destroy(User $user) {
        $message = $user->delete() ? 'Пользователь успешно удален': 'Пользователя с таким id не существует';
        return compact('message');
    }

    public function store(UserCreateRequest $request) {
        $userData = $request->validated();

        $userData['password'] = Hash::make($userData['password']);

        return new UserResource(User::create($userData));
    }

    public function update(UserUpdateRequest $request, User $user) {
        $userData = $request->validated();
        $user->password = Hash::make($userData['password']);
        $user->save();
        return new UserResource($user);
    }

    public function forgot(Request $request, string $email) {
        return 1;
    }
}
