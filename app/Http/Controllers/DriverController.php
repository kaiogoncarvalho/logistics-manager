<?php

namespace App\Http\Controllers;

use App\Services\DriverService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\{CreateDriverRequest, SearchDriverRequest, UpdateDriverRequest};
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Enums\Paginate;

/**
 * Class DriverController
 * @package App\Http\Controllers
 */
class DriverController extends Controller
{
    /**
     * @param int $driver_id
     * @param DriverService $driverService
     * @return Driver
     */
    public function getById(int $driver_id, DriverService $driverService): Driver
    {
        return $driverService->getById($driver_id);
    }
    
    /**
     * @param DriverService $driverService
     * @return LengthAwarePaginator
     */
    public function getAll(DriverService $driverService, SearchDriverRequest $request): LengthAwarePaginator
    {
        return $driverService
            ->get(
                $request->except(
                    [
                        'order',
                        'per_page',
                        'page',
                        'orders'
                    ]
                ),
                $request->get('order') ?? $request->get('orders')
            )->paginate(
                ...Paginate::get($request->get('per_page'), $request->get('page'))
            );
    }
    
    /**
     * @param CreateDriverRequest $request
     * @param DriverService $driverService
     * @return JsonResponse
     */
    public function create(CreateDriverRequest $request, DriverService $driverService): JsonResponse
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
    
    /**
     * @param int $driver_id
     * @param DriverService $driverService
     * @return JsonResponse
     */
    public function delete(int $driver_id, DriverService $driverService): JsonResponse
    {
        $driverService->delete($driver_id);
        
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
    
    /**
     * @param int $driver_id
     * @param UpdateDriverRequest $request
     * @param DriverService $driverService
     * @return Driver
     */
    public function update(int $driver_id, UpdateDriverRequest $request, DriverService $driverService): Driver
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
    
    /**
     * @param DriverService $driverService
     * @param Request $request
     */
    public function getAllDeleted(DriverService $driverService, SearchDriverRequest $request)
    {
        return $driverService->getDeleted(
            $request->except(
                [
                    'order',
                    'per_page',
                    'page',
                    'orders'
                ]
            ),
            $request->get('order') ?? $request->get('orders')
        )->paginate(
            ...Paginate::get($request->get('per_page'), $request->get('page'))
        );
    }
    
    /**
     * @param int $driver_id
     * @param DriverService $driverService
     * @return Driver
     */
    public function getDeletedById(int $driver_id, DriverService $driverService): Driver
    {
        return $driverService->getDeletedById($driver_id);
    }
    
    
    /**
     * @param int $driver_id
     * @param DriverService $driverService
     * @return Driver
     */
    public function recoverById(int $driver_id, DriverService $driverService): Driver
    {
        return $driverService->recoverById($driver_id);
    }
    
    public function getByTripEmpty(Request $request, DriverService $driverService)
    {
        return
            $driverService->getByTripEmpty(
                $request->get('startDate'),
                $request->get('endDate')
            )->paginate(
                ...Paginate::get($request->get('per_page'), $request->get('page'))
            );
    }
    
    public function getTotalByOwnTruck(DriverService $driverService)
    {
        return new JsonResponse(['total' => $driverService->get(['own_truck' => true])->count()]);
    }
}
