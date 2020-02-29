<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};

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
class Truck extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name'
    ];
    
    protected $hidden = [
        'deleted_at', 'pivot'
    ];
    
    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
