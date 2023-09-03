<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    //
    public function index(Request $request) {

        if (!$request->user()->isAdmin()) throw new NotFoundHttpException('Not Found');
        return User::all();
    }

    public function delete(Request $request, string $id) {
        if (!$request->user()->isAdmin()) throw new NotFoundHttpException('Not Found');

        $message = User::destroy($id) ? 'Пользователь успешно удален': 'Пользователя с таким id не существует';
        return compact('message');
    }

    public function create(Request $request) {
        if (!$request->user()->isAdmin()) throw new NotFoundHttpException('Not Found');

        $userData =   $request->validate([
            'name'     => 'required',
            'email'    => 'required|email:rfc,dns',
            'password' => 'required|min:8',
            'role'     => ['required', Rule::in([1,2,3])]
        ]);

        $userData['password'] = Hash::make($userData['password']);

        return User::create($userData);
    }

    public function update(Request $request, string $id) {
        if (!$request->user()->isAdmin()) throw new NotFoundHttpException('Not Found');

        $user = User::find($id);
        $userData = $request->validate(['password' => 'required|min:8',]);
        $user->password = Hash::make($userData['password']);
        $user->save();
        return $user;
    }

    public function forgot(Request $request, string $email) {
        return 1;
    }
}
