<?php

namespace App\Http\Controllers;


use App\Http\Requests\BookCreateRequest;
use App\Http\Resources\BookResource;
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
    public function index() {
        return BookResource::collection(Book::all());
    }

    public function store(BookCreateRequest $request) {
        $userData =   $request->validated();

        $userData['slug'] = Transliterator::transliterate($userData['name']);
        return new BookResource(Book::create($userData));
    }

    public function destroy(Book $book) {
        $message = $book->delete() ? 'Книга успешно удалена': '?';
        return compact('message');
    }

    public function take(Book $book, User $user) {
        if ($book->user_id && $book->take_at) throw new BadRequestHttpException('Книга уже выдана');
        $book->user_id = $user->id;
        $book->take_at = Carbon::now();
        $book->reserved_at = null;
        $book->save();

        return new BookResource($book);
    }

    public function returnBook(Book $book) {
        $book->user_id = null;
        $book->take_at = null;
        $book->save();

        return new BookResource($book);
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

        return new BookResource(Book::query()->where($book)->get());
    }

    public function reserve(Book $book, User $user) {
        if ($book->user_id && ($book->reserved_at || $book->take_at)) {
            throw new BadRequestHttpException('Книга уже выдана или зарезервирована');
        }
        $book->user_id = $user->id;
        $book->reserved_at = Carbon::now();
        $book->save();

        return new BookResource($book);
    }

    public function free(Book $book) {
        $book->user_id = null;
        $book->reserved_at = null;
        $book->save();

        return new BookResource($book);
    }
}
