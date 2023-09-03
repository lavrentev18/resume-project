<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Book
 *
 * @property int $id
 * @property string $name
 * @property string $author
 * @property string $slug
 * @property string $genre
 * @property string $publisher
 * @property string $description
 * @property int|null $user_id
 * @property string|null $reserved_at
 * @property string|null $take_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Book newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book query()
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereGenre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book wherePublisher($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereReservedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereTakeAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereUserId($value)
 * @mixin \Eloquent
 */
class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'author',
        'genre',
        'publisher',
        'description',
        'slug',
    ];

    public const GENRES = [
        'novel',
        'detective',
        'fantastic',
        'opus'
    ];
}
