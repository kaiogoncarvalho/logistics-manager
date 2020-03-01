<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Grimzy\LaravelMysqlSpatial\Types\Point;

/**
 * App\Models\Trip
 *
 * @property int $id
 * @property int $driver_id
 * @property int $truck_id
 * @property bool $loaded
 * @property mixed $origin
 * @property mixed $destiny
 * @property \Illuminate\Support\Carbon $trip_date
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Driver $driver
 * @property-read \App\Models\Truck $truck
 * @method static bool|null forceDelete()
 * @method static Builder|Trip newModelQuery()
 * @method static Builder|Trip newQuery()
 * @method static \Illuminate\Database\Query\Builder|Trip onlyTrashed()
 * @method static Builder|Trip query()
 * @method static bool|null restore()
 * @method static Builder|Trip whereDeletedAt($value)
 * @method static Builder|Trip whereDestiny($value)
 * @method static Builder|Trip whereDriverId($value)
 * @method static Builder|Trip whereId($value)
 * @method static Builder|Trip whereLoaded($value)
 * @method static Builder|Trip whereOrigin($value)
 * @method static Builder|Trip whereTripDate($value)
 * @method static Builder|Trip whereTruckId($value)
 * @method static \Illuminate\Database\Query\Builder|Trip withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Trip withoutTrashed()
 * @mixin \Eloquent
 */
class Trip extends Model
{
    use SoftDeletes, SpatialTrait;
    
    protected $fillable = [
        'driver_id',
        'truck_id',
        'loaded',
        'origin',
        'destiny',
        'trip_date'
    ];
    
    protected $casts = [
        'trip_date' => 'datetime',
        'loaded'    => 'boolean'
    ];
    
    protected $hidden = [
        'deleted_at', 'pivot'
    ];
    
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    
    protected $spatialFields = [
        'destiny',
        'origin'
    ];
    
    public function driver()
    {
        return $this->hasOne(Driver::class);
    }
    
    public function truck()
    {
        return $this->hasOne(Truck::class);
    }
    
    /**
     * @param $fields
     */
    public function setOriginAttribute($fields)
    {
        $this->attributes['origin'] = new Point($fields['lat'], $fields['lon']);
    }
    
    /**
     * @param $fields
     */
    public function setDestinyAttribute($fields)
    {
        $this->attributes['destiny'] = new Point($fields['lat'], $fields['lon']);
    }
}
