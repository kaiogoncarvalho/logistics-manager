<?php

namespace App\Http\Controllers;

use App\Services\TruckService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\{CreateTruckRequest, UpdateTruckRequest};
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class TruckController
 * @package App\Http\Controllers
 */
class TruckController extends Controller
{
    /**
     * @param int $driver_id
     * @param TruckService $truckService
     * @return Truck
     */
    public function getById(int $driver_id, TruckService $truckService): Truck
    {
        return $truckService->getById($driver_id);
    }
    
    /**
     * @param TruckService $truckService
     * @return LengthAwarePaginator
     */
    public function getAll(TruckService $truckService, Request $request): LengthAwarePaginator
    {
        return $truckService->getAll(
            $request->get('perPage') ?? 10,
            $request->get('page') ?? 1
        );
    }
    
    /**
     * @param CreateTruckRequest $request
     * @param TruckService $truckService
     * @return JsonResponse
     */
    public function create(CreateTruckRequest $request, TruckService $truckService): JsonResponse
    {
        return new JsonResponse(
            $truckService->create(
                $request->all(
                    [
                        'name'
                    ]
                )
            ),
            Response::HTTP_CREATED
        );
    }
    
    /**
     * @param int $driver_id
     * @param TruckService $truckService
     * @return JsonResponse
     */
    public function delete(int $driver_id, TruckService $truckService): JsonResponse
    {
        $truckService->delete($driver_id);
        
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
    
    /**
     * @param int $driver_id
     * @param UpdateTruckRequest $request
     * @param TruckService $truckService
     * @return Truck
     */
    public function update(int $driver_id, UpdateTruckRequest $request, TruckService $truckService): Truck
    {
        return $truckService->update(
            $driver_id,
            $request->all(
                [
                    'name'
                ]
            )
        );
    }
    
    /**
     * @param TruckService $truckService
     * @param Request $request
     */
    public function getAllDeleted(TruckService $truckService, Request $request)
    {
        return $truckService->getAllDeleted(
            $request->get('perPage') ?? 10,
            $request->get('page') ?? 1
        );
    }
    
    /**
     * @param int $driver_id
     * @param TruckService $truckService
     * @return Truck
     */
    public function getDeletedById(int $driver_id, TruckService $truckService): Truck
    {
        return $truckService->getDeletedById($driver_id);
    }
    
    
    /**
     * @param int $driver_id
     * @param TruckService $truckService
     * @return Truck
     */
    public function recoverById(int $driver_id, TruckService $truckService): Truck
    {
        return $truckService->recoverById($driver_id);
    }
}
