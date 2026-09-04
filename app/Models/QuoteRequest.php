<?php

namespace App\Models;

use App\Enums\QuoteRequestStatus;
use Database\Factories\QuoteRequestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $reference
 * @property string $first_name
 * @property string $last_name
 * @property string|null $company
 * @property string|null $role
 * @property string $email
 * @property string $phone
 * @property string|null $location
 * @property string $service_type
 * @property string|null $project_nature
 * @property string|null $estimated_budget
 * @property string|null $desired_timeline
 * @property string $description
 * @property QuoteRequestStatus $status
 * @property bool $consent
 * @property string|null $internal_notes
 * @property int|null $assigned_to
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string $full_name
 */
#[Fillable([
    'reference',
    'first_name',
    'last_name',
    'company',
    'role',
    'email',
    'phone',
    'location',
    'service_type',
    'project_nature',
    'estimated_budget',
    'desired_timeline',
    'description',
    'status',
    'consent',
    'internal_notes',
    'assigned_to',
])]
class QuoteRequest extends Model
{
    /** @use HasFactory<QuoteRequestFactory> */
    use HasFactory;

    use SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => QuoteRequestStatus::class,
            'consent' => 'boolean',
        ];
    }

    /**
     * Full name of the applicant.
     *
     * @return Attribute<string, never>
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => trim("{$this->first_name} {$this->last_name}"),
        );
    }

    /**
     * Commercial or admin user assigned to handle this lead.
     *
     * @return BelongsTo<User, $this>
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Scope for pending quote requests requiring action.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->whereIn('status', [
            QuoteRequestStatus::New,
            QuoteRequestStatus::Qualified,
            QuoteRequestStatus::UnderReview,
        ])->orderByDesc('created_at');
    }
}
