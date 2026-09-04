<?php

namespace App\Models;

use Database\Factories\PublicProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $category
 * @property string|null $client_name
 * @property string|null $location
 * @property int|null $year
 * @property string|null $description
 * @property array<string, mixed>|null $key_figures
 * @property string|null $cover_image
 * @property array<int, string>|null $gallery
 * @property bool $is_featured
 * @property bool $is_published
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'slug',
    'title',
    'category',
    'client_name',
    'location',
    'year',
    'description',
    'key_figures',
    'cover_image',
    'gallery',
    'is_featured',
    'is_published',
    'published_at',
])]
class PublicProject extends Model
{
    /** @use HasFactory<PublicProjectFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'key_figures' => 'array',
            'gallery' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Scope for published public projects.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderByDesc('year')->orderByDesc('id');
    }

    /**
     * Scope for featured showcase projects.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_published', true)->where('is_featured', true)->orderByDesc('year');
    }
}
