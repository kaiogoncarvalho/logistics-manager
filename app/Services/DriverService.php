<?php

namespace App\Services;

use App\Models\Driver;

/**
 * Class DriverService
 * @package App\Services
 * @method Driver getModel() : Model
 */
class DriverService extends AbstractService
{
    protected string $modelClass = Driver::class;
    
    public function getById(int $id): Driver
    {
        return $this->getModel()->findOrFail($id);
    }
    
    public function get()
    {
        return $this->getModel()->all();
    }
    
    public function create(array $fields): Driver
    {
        return $this->getModel()->create(
            [
                'name'       => $fields['name'],
                'cpf'        => $fields['cpf'],
                'birth_date' => $fields['birth_date'],
                'gender'     => $fields['gender'],
                'own_truck'  => $fields['own_truck'],
                'cnh'        => $fields['cnh']
            ]
        );
    }
    
    public function delete(int $id): void
    {
        $this->getModel()->destroy($id);
    }
    
    public function update(int $id, array $fields = []): Driver
    {
        $driver = $this
            ->getModel()
            ->findOrFail($id);
        
        $driver->name ??= $fields['name'];
        $driver->cpf ??= $fields['cpf'];
        $driver->birth_date ??= $fields['birth_date'];
        $driver->gender ??= $fields['gender'];
        $driver->own_truck ??= $fields['own_truck'];
        $driver->cnh ??= $fields['cnh'];
        
        $driver->save();
 
        return $driver;
    }
    
}
