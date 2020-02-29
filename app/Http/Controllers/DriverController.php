<?php

namespace App\Http\Controllers;

use App\Services\DriverService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\CreateDriverRequest;
use App\Http\Requests\UpdateDriverRequest;

class DriverController extends Controller
{
    public function getById(int $driver_id, DriverService $driverService)
    {
        return $driverService->getById($driver_id);
    }
    
    public function get(DriverService $driverService)
    {
        return $driverService->get();
    }
    
    public function create(CreateDriverRequest $request, DriverService $driverService)
    {
        return new JsonResponse(
            $driverService->create(
                $request->all(
                    [
                        'name',
                        'cpf',
                        'birth_date',
                        'gender',
                        'own_truck',
                        'cnh'
                    ]
                )
            ),
            Response::HTTP_CREATED
        );
    }
    
    public function delete(int $driver_id, DriverService $driverService)
    {
        $driverService->delete($driver_id);
        
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
    
    public function update(int $driver_id, UpdateDriverRequest $request, DriverService $driverService)
    {
        return $driverService->update(
            $driver_id,
            $request->all(
                [
                    'name',
                    'cpf',
                    'birth_date',
                    'gender',
                    'own_truck',
                    'cnh'
                ]
            )
        );
    }
}
