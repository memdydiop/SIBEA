<?php

namespace App\Models;

use Database\Factories\ProgramFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $city
 * @property string|null $municipality
 * @property string|null $district
 * @property float|null $total_area
 * @property string|null $excerpt
 * @property string|null $description
 * @property string|null $cover_path
 * @property int $total_lots
 * @property bool $is_published
 * @property Carbon|null $published_at
 * @property int $order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
#[Fillable(['slug', 'title', 'meta_title', 'meta_description', 'city', 'municipality', 'district', 'total_area', 'excerpt', 'description', 'cover_path', 'total_lots', 'is_published', 'published_at', 'order'])]
class Program extends Model
{
    /** @use HasFactory<ProgramFactory> */
    use HasFactory;

    use SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'order' => 'integer',
            'total_lots' => 'integer',
            'total_area' => 'decimal:2',
        ];
    }

    /**
     * @return HasMany<ProgramLot, $this>
     */
    public function lots(): HasMany
    {
        return $this->hasMany(ProgramLot::class);
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->orderBy('order');
    }
}
