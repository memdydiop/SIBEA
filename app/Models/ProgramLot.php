<?php

namespace App\Models;

use App\Enums\LotStatus;
use Database\Factories\ProgramLotFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $program_id
 * @property string $reference
 * @property float|null $surface
 * @property float|null $price
 * @property LotStatus $status
 * @property bool $is_viabilise
 * @property string|null $juridical_status
 * @property string|null $plan_pdf_path
 * @property float|null $latitude
 * @property float|null $longitude
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
#[Fillable(['program_id', 'reference', 'surface', 'price', 'status', 'is_viabilise', 'juridical_status', 'plan_pdf_path', 'latitude', 'longitude', 'published_at'])]
class ProgramLot extends Model
{
    /** @use HasFactory<ProgramLotFactory> */
    use HasFactory;

    use SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'surface' => 'decimal:2',
            'price' => 'decimal:2',
            'status' => LotStatus::class,
            'is_viabilise' => 'boolean',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'published_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Program, $this>
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
