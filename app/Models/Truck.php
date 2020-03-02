<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use App\Models\Interfaces\Filtered;

/**
 * App\Models\Truck
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static Builder|Truck newModelQuery()
 * @method static Builder|Truck newQuery()
 * @method static \Illuminate\Database\Query\Builder|Truck onlyTrashed()
 * @method static Builder|Truck query()
 * @method static bool|null restore()
 * @method static Builder|Truck whereCreatedAt($value)
 * @method static Builder|Truck whereDeletedAt($value)
 * @method static Builder|Truck whereId($value)
 * @method static Builder|Truck whereName($value)
 * @method static Builder|Truck whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Truck withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Truck withoutTrashed()
 * @mixin \Eloquent
 */
class Truck extends Model implements Filtered
{
    use SoftDeletes;
    
    protected $fillable = [
        'name'
    ];
    
    protected $hidden = [
        'deleted_at',
        'pivot'
    ];
    
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    
    protected $filters = [
        'name' => 'like',
    ];
    
    public function trips()
    {
        return $this->hasMany(Trip::class, 'truck_id');
    }
    
    public function getFilters(): array
    {
        return $this->filters;
    }
}
