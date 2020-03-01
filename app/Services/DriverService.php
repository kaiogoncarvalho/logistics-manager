<?php

namespace App\Services;

use App\Models\Driver;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class DriverService
 * @package App\Services
 */
class DriverService implements CrudService
{
    /**
     * @var Driver
     */
    private Driver $driver;
    
    /**
     * DriverService constructor.
     * @param Driver $driver
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }
    
    /**
     * @param int $id
     * @return Driver
     */
    public function getById(int $id): Driver
    {
        return $this->driver->findOrFail($id);
    }
    
    public function getAll(int $perPage = 10, int $page = 1): LengthAwarePaginator
    {
        return $this->driver->paginate($perPage, '*', 'page', $page);
    }
    
    public function create(array $fields): Driver
    {
        return $this->driver->create(
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
        $this->driver->findOrFail($id)->delete();
    }
    
    public function update(int $id, array $fields = []): Driver
    {
        $driver = $this
            ->driver
            ->findOrFail($id);
        
        $driver->name = $fields['name'] ?? $driver->name;
        $driver->cpf = $fields['cpf'] ?? $driver->cpf;
        $driver->birth_date = $fields['birth_date'] ?? $driver->birth_date;
        $driver->gender = $fields['gender'] ?? $driver->gender;
        $driver->own_truck = $fields['own_truck'] ?? $driver->own_truck;
        $driver->cnh = $fields['cnh'] ?? $driver->cnh;
        
        $driver->save();
 
        return $driver;
    }
    
    public function getAllDeleted(int $perPage = 10, int $page = 1): LengthAwarePaginator

    {
        return $this
            ->driver
            ->onlyTrashed()
            ->paginate(
                $perPage,
                '*',
                'page',
                $page
            );
            
    }
    
    public function getDeletedById(int $driver_id): Driver
    {
        return $this
            ->driver
            ->onlyTrashed()
            ->findOrFail($driver_id);
    }
    
    public function recoverById(int $driver_id): Driver
    {
        $driver = $this->getDeletedById($driver_id);
        $driver->restore();
        return $driver;
    }
    
}
