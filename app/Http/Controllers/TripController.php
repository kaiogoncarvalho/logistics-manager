<?php

namespace App\Http\Controllers;

use App\Services\TripService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\{CreateTripRequest, UpdateDriverRequest, UpdateTripRequest};
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class TripController
 * @package App\Http\Controllers
 */
class TripController extends Controller
{
    /**
     * @param int $driver_id
     * @param TripService $tripService
     * @return Trip
     */
    public function getById(int $driver_id, TripService $tripService): Trip
    {
        return $tripService->getById($driver_id);
    }
    
    /**
     * @param TripService $tripService
     * @return LengthAwarePaginator
     */
    public function getAll(TripService $tripService, Request $request): LengthAwarePaginator
    {
        return $tripService->getAll(
            $request->get('perPage') ?? 10,
            $request->get('page') ?? 1
        );
    }
    
    /**
     * @param CreateTripRequest $request
     * @param TripService $tripService
     * @return JsonResponse
     */
    public function create(CreateTripRequest $request, TripService $tripService): JsonResponse
    {
        return new JsonResponse(
            $tripService->create(
                $request->all(
                    [
                        'driver_id',
                        'truck_id',
                        'loaded',
                        'origin',
                        'destiny',
                        'trip_date'
                    ]
                )
            ),
            Response::HTTP_CREATED
        );
    }
    
    /**
     * @param int $driver_id
     * @param TripService $tripService
     * @return JsonResponse
     */
    public function delete(int $driver_id, TripService $tripService): JsonResponse
    {
        $tripService->delete($driver_id);
        
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
    
    /**
     * @param int $driver_id
     * @param UpdateDriverRequest $request
     * @param TripService $tripService
     * @return Trip
     */
    public function update(int $driver_id, UpdateTripRequest $request, TripService $tripService): Trip
    {
        return $tripService->update(
            $driver_id,
            $request->all(
                [
                    'driver_id',
                    'truck_id',
                    'loaded',
                    'origin',
                    'destiny',
                    'trip_date'
                ]
            )
        );
    }
    
    /**
     * @param TripService $tripService
     * @param Request $request
     */
    public function getAllDeleted(TripService $tripService, Request $request)
    {
        return $tripService->getAllDeleted(
            $request->get('perPage') ?? 10,
            $request->get('page') ?? 1
        );
    }
    
    /**
     * @param int $driver_id
     * @param TripService $tripService
     * @return Trip
     */
    public function getDeletedById(int $driver_id, TripService $tripService): Trip
    {
        return $tripService->getDeletedById($driver_id);
    }
    
    
    /**
     * @param int $driver_id
     * @param TripService $tripService
     * @return Trip
     */
    public function recoverById(int $driver_id, TripService $tripService): Trip
    {
        return $tripService->recoverById($driver_id);
    }
}
