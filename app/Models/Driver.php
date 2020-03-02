<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use App\Models\Interfaces\Filtered;

/**
 * App\Models\Driver
 *
 * @property int $id
 * @property string $name
 * @property int $cpf
 * @property \Illuminate\Support\Carbon $birth_date
 * @property string $gender
 * @property int $own_truck
 * @property string $cnh
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static Builder|Driver newModelQuery()
 * @method static Builder|Driver newQuery()
 * @method static \Illuminate\Database\Query\Builder|Driver onlyTrashed()
 * @method static Builder|Driver query()
 * @method static bool|null restore()
 * @method static Builder|Driver whereBirthDate($value)
 * @method static Builder|Driver whereCnh($value)
 * @method static Builder|Driver whereCpf($value)
 * @method static Builder|Driver whereCreatedAt($value)
 * @method static Builder|Driver whereDeletedAt($value)
 * @method static Builder|Driver whereGender($value)
 * @method static Builder|Driver whereId($value)
 * @method static Builder|Driver whereName($value)
 * @method static Builder|Driver whereOwnTruck($value)
 * @method static Builder|Driver whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Driver withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Driver withoutTrashed()
 * @mixin \Eloquent
 */
class Driver extends Model implements Filtered
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'cpf',
        'birth_date',
        'gender',
        'own_truck',
        'cnh'
    ];
    
    protected $casts = [
        'birth_date' => 'Y-m-d',
        'own_truck'  => 'boolean'
    ];
    
    protected $hidden = [
        'deleted_at',
        'pivot'
    ];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $filters = [
        'name'             => 'like',
        'start_birth_date' =>
            [
                'type' => '>=',
                'name' => 'birth_date'
            ],
        'end_birth_date'   => [
            'type' => '>=',
            'name' => 'birth_date'
        ],
        'gender'           => '=',
        'genders'          => [
            'type' => 'in',
            'name' => 'gender'
        ],
        'own_truck'        => '=',
        'cnh'              => '=',
        'cnhs'             => [
            'type' => 'in',
            'name' => 'cnh'
        ]
    ];
    
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
    
    public function getFilters(): array
    {
        return $this->filters;
    }
}
