<?php

namespace App\Models;

use App\Enums\ContractType;
use App\Enums\EmployeeStatus;
use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $user_id
 * @property int|null $department_id
 * @property string $registration_number
 * @property string $first_name
 * @property string $last_name
 * @property string|null $email
 * @property string|null $phone
 * @property string $job_title
 * @property ContractType $contract_type
 * @property EmployeeStatus $status
 * @property Carbon|null $hire_date
 * @property Carbon|null $end_date
 * @property float|null $hourly_cost_rate
 * @property float|null $daily_cost_rate
 * @property string|null $emergency_contact_name
 * @property string|null $emergency_contact_phone
 * @property string|null $notes
 * @property bool $is_public
 * @property string|null $avatar_url
 * @property int $vitrine_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string $full_name
 */
#[Fillable([
    'user_id',
    'department_id',
    'registration_number',
    'first_name',
    'last_name',
    'email',
    'phone',
    'job_title',
    'contract_type',
    'status',
    'hire_date',
    'end_date',
    'hourly_cost_rate',
    'daily_cost_rate',
    'emergency_contact_name',
    'emergency_contact_phone',
    'notes',
    'is_public',
    'avatar_url',
    'vitrine_order',
])]
class Employee extends Model
{
    /** @use HasFactory<EmployeeFactory> */
    use HasFactory;

    use SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => EmployeeStatus::class,
            'contract_type' => ContractType::class,
            'hire_date' => 'date',
            'end_date' => 'date',
            'hourly_cost_rate' => 'decimal:2',
            'daily_cost_rate' => 'decimal:2',
            'is_public' => 'boolean',
            'avatar_url' => 'string',
            'vitrine_order' => 'integer',
        ];
    }

    /**
     * Full name attribute.
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
     * Optional user account associated with this employee.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Department the employee belongs to.
     *
     * @return BelongsTo<Department, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Teams the employee is a member of.
     *
     * @return BelongsToMany<Team, $this>
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_members')
            ->withPivot(['role_in_team', 'joined_at', 'left_at', 'is_active'])
            ->withTimestamps();
    }

    /**
     * Direct team membership records.
     *
     * @return HasMany<TeamMember, $this>
     */
    public function teamMemberships(): HasMany
    {
        return $this->hasMany(TeamMember::class);
    }

    /**
     * Teams led by this employee.
     *
     * @return HasMany<Team, $this>
     */
    public function ledTeams(): HasMany
    {
        return $this->hasMany(Team::class, 'leader_id');
    }

    /**
     * Departments managed by this employee.
     *
     * @return HasMany<Department, $this>
     */
    public function managedDepartments(): HasMany
    {
        return $this->hasMany(Department::class, 'manager_id');
    }

    /**
     * Scope for active employees.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', EmployeeStatus::Active);
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true)->where('status', EmployeeStatus::Active);
    }
}
