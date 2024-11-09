<?php

namespace App\Http\Controllers;

use App\Enums\TripOrder\Status;
use App\Http\Queries\TripOrderQuery;
use App\Http\Requests\TripOrder\CreateRequest;
use App\Http\Requests\TripOrder\UpdateRequest;
use App\Http\Resources\TripOrderResource;
use App\Models\TripOrder;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class TripOrderController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly Factory $auth)
    {
    }

    public function index(Request $request, TripOrderQuery $query): JsonResource
    {
        $this->authorize('viewAny', [TripOrder::class]);

        return TripOrderResource::collection(
            $query
                ->whereBelongsTo($this->auth->user())
                ->simplePaginate($request->get('limit', config('app.pagination_limit')))
                ->appends($request->query()),
        );
    }

    public function show(TripOrderQuery $query, TripOrder $tripOrder): JsonResource
    {
        $this->authorize('view', [TripOrder::class, $tripOrder]);

        return new TripOrderResource(
            $query
                ->where('id', $tripOrder->id)
                ->whereBelongsTo($this->auth->user())
                ->firstOrFail(),
        );
    }

    public function store(CreateRequest $request): JsonResource
    {
        $this->authorize('create', [TripOrder::class]);

        return new TripOrderResource(
            $this->auth->user()
                ->tripOrders()
                ->create($request->validated()),
        );
    }

    public function update(UpdateRequest $request, TripOrder $tripOrder): JsonResource
    {
        $this->authorize('update', [TripOrder::class, $tripOrder]);

        $tripOrder->update($request->validated());

        return new TripOrderResource($tripOrder->refresh());
    }

    public function delete(TripOrder $tripOrder): JsonResponse
    {
        $this->authorize('delete', [TripOrder::class, $tripOrder]);

        $tripOrder->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    public function approve(TripOrder $tripOrder): JsonResource
    {
        $this->authorize('update', [TripOrder::class, $tripOrder]);

        $tripOrder->update(['status' => Status::APPROVED]);

        return new TripOrderResource($tripOrder->refresh());
    }

    public function cancel(TripOrder $tripOrder): JsonResource
    {
        $this->authorize('update', [TripOrder::class, $tripOrder]);

        $tripOrder->update(['status' => Status::CANCELED]);

        return new TripOrderResource($tripOrder->refresh());
    }
}
