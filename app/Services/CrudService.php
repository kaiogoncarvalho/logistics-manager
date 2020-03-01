<?php


namespace App\Services;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CrudService
{
    /**
     * Get all
     *
     * @param int $perPage
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function getAll(int $perPage = 10, int $page = 1): LengthAwarePaginator;
    
    /**
     * Create entity
     *
     * @param array $params
     * @return array Entity as array
     */
    public function create(array $params): Model;
    
    /**
     * Update entity
     *
     * @param array $params
     * @return void
     */
    public function update(int $id, array $params): Model;
    
    /**
     * Delete entity
     *
     * @param array @params
     * @return void
     */
    public function delete(int $id): void;
    
}
