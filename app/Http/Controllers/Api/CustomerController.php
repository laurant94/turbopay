<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Services\CustomerService;

class CustomerController extends Controller
{

    public function __construct(protected CustomerService $service)
    {
        // middleware auth.apikey already applied in routes
    }

    public function index(Request $request)
    {
        $merchant = $request->user();
        $perPage = (int) $request->query('per_page', 15);
        $filters = $request->only(['q','email','phone','sort_by','sort_dir']);

        $paginator = $this->service->list($merchant, $filters, $perPage);

        return CustomerResource::collection($paginator)->additional([
            'success' => true,
            'meta' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage()
            ]
        ]);
    }

    public function store(StoreCustomerRequest $request)
    {
        $merchant = $request->user();

        $data = $request->only(['email','firstname', 'lastname', 'phone']);

        $customer = $this->service->create($merchant, $data);

        return (new CustomerResource($customer))->additional([
            'success' => true
        ])->response()->setStatusCode(201);
    }

    public function show(Request $request, $id)
    {
        $merchant = $request->user();
        $customer = $this->service->findOrFail($merchant, $id);

        return (new CustomerResource($customer))->additional(['success' => true]);
    }

    public function update(UpdateCustomerRequest $request, $id)
    {
        $merchant = $request->user();
        $customer = $this->service->findOrFail($merchant, $id);

        $data = $request->only(['firstname','lastname','phone']);
        $customer = $this->service->update($merchant, $customer, $data);

        return (new CustomerResource($customer))->additional(['success' => true]);
    }

    public function destroy(Request $request, $id)
    {
        $merchant = $request->user();
        $customer = $this->service->findOrFail($merchant, $id);

        $this->service->delete($merchant, $customer);

        return response()->json(['success' => true, 'message' => 'Customer deleted'], 204);
    }
}
