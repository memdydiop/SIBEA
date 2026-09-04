<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $collection
 * @property string|null $model_type
 * @property int|null $model_id
 * @property string $file_name
 * @property string $file_path
 * @property string|null $mime_type
 * @property int|null $size
 * @property string|null $alt
 * @property int $order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['collection', 'model_type', 'model_id', 'file_name', 'file_path', 'mime_type', 'size', 'alt', 'order'])]
class Media extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'size' => 'integer',
        ];
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): string
    {
        return str_starts_with($this->file_path, '/storage/') ? $this->file_path : '/storage/'.$this->file_path;
    }
}
