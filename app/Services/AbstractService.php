<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractService
{
    /**
     * @var string
     */
    protected string $modelClass = Model::class;
    /**
     * @var Model
     */
    private Model $model;
    
    
    /**
     * @return Model
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getModel(): Model
    {
        return $this->model;
    }
    
    public function __construct()
    {
        $this->createModel();
    }
    
    private function createModel()
    {
        $this->model = app()->make($this->modelClass);
    }
}
