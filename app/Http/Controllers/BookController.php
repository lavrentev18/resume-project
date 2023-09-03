<?php

namespace App\Http\Controllers;


use App\Jobs\RefreshReserv;
use App\Models\Book;
use App\Models\User;
use Behat\Transliterator\Transliterator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookController extends Controller
{
    public function index(Request  $request) {
        return Book::all();
    }

    public function create(Request $request) {
        if ($request->user()->isUser()) throw new NotFoundHttpException('Not Found');

        $userData =   $request->validate([
            'name'        => 'required',
            'author'      => 'required',
            'genre'       => ['required', Rule::in(Book::GENRES)],
            'publisher'   => 'required',
            'description' => 'required',
        ]);

        $userData['slug'] = Transliterator::transliterate($userData['name']);
        return Book::create($userData);
    }

    public function delete(Request $request, string $slug) {
        if ($request->user()->isUser()) throw new NotFoundHttpException('Not Found');

        $book = Book::whereSlug($slug)->first();
        if (!$book) throw new NotFoundHttpException('Такой книги не существует');

        $message = $book->delete() ? 'Книга успешно удалена': '?';
        return compact('message');
    }

    public function take(Request $request, string $slug, string $id ) {
        if ($request->user()->isUser()) throw new NotFoundHttpException('Not Found');

        $book = Book::whereSlug($slug)->first();
        if (!$book) throw new NotFoundHttpException('Такой книги не существует');
        if (!User::find($id)) throw new NotFoundHttpException('Такого пользователя не существует');

        if ($book->user_id && $book->take_at) throw new BadRequestHttpException('Книга уже выдана');
        $book->user_id = $id;
        $book->take_at = Carbon::now();
        $book->reserved_at = null;
        $book->save();

        return $book;
    }

    public function returnBook(Request $request, string $slug) {
        if ($request->user()->isUser()) throw new NotFoundHttpException('Not Found');

        $book = Book::whereSlug($slug)->first();
        if (!$book) throw new NotFoundHttpException('Такой книги не существует');

        $book->user_id = null;
        $book->take_at = null;
        $book->save();

        return $book;
    }

    public function search(Request $request) {

        $book = [
            'author'    => $request->get('author'),
            'publisher' => $request->get('publisher'),
            'genre'     => $request->get('genre'),
        ];

        if (!$book['author'] && !$book['publisher'] && !$book['genre']) {
            throw new BadRequestHttpException('Не указаны параметры поиска');
        }
        if ($book['genre'] && !in_array($book['genre'], Book::GENRES)) {
            throw new BadRequestHttpException('Такого жанра не существует');
        }

        foreach ($book as $key=>$elem) {
            if (!$elem) unset($book[$key]);
        }

        return Book::query()->where($book)->get();
    }

    public function reserve(Request $request, string $slug, string $id) {
        $book = Book::whereSlug($slug)->first();
        if (!$book) throw new NotFoundHttpException('Такой книги не существует');
        if (!User::find($id)) throw new NotFoundHttpException('Такого пользователя не существует');

        if ($book->user_id && ($book->reserved_at || $book->take_at)) {
            throw new BadRequestHttpException('Книга уже выдана или зарезервирована');
        }
        $book->user_id = $id;
        $book->reserved_at = Carbon::now();
        $book->save();

        RefreshReserv::dispatch($book->id)->delay(Carbon::now()->addSeconds(30));
        return $book;
    }

    public function free(Request $request, string $slug) {
        $book = Book::whereSlug($slug)->first();
        if (!$book) throw new NotFoundHttpException('Такой книги не существует');

        $book->user_id = null;
        $book->reserved_at = null;
        $book->save();

        return $book;
    }
}
