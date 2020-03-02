<?php

namespace App\Http\Controllers;

use App\Services\TruckService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\{CreateTruckRequest, SearchTruckRequest, UpdateTruckRequest};
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Enums\Paginate;

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
     * @param SearchTruckRequest $request
     * @return LengthAwarePaginator
     */
    public function getAll(TruckService $truckService, SearchTruckRequest $request): LengthAwarePaginator
    {
        return $truckService
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
     * @param SearchTruckRequest $request
     * @return LengthAwarePaginator
     */
    public function getAllDeleted(TruckService $truckService, SearchTruckRequest $request)
    {
        return $truckService->getDeleted(
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
    
    public function getWithTrips(TruckService $truckService)
    {
        return $truckService
            ->getWithTrips();
    }
}
