<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Enums\TransactionStatusEnum;
use App\Http\Requests\ProcessTransactionRequest;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Trait\ResolveCustomer;
use App\Services\AntiFraud\AntiFraudService;
use App\Services\CustomerService;
use App\Services\Payments\PaymentService;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    use ResolveCustomer;

    public function __construct(protected TransactionService $service)
    {

    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $merchant = $request->user();
        $perPage = (int) $request->query('per_page', 15);
        $filters = $request->only(['q','description','id', 'amount', 'status', 'sort_by','sort_dir']);

        $paginator = $this->service->list($merchant, $filters, $perPage);

        return TransactionResource::collection($paginator)->additional([
            'success' => true,
            'meta' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage()
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $merchant = $request->user();

        $data = $request->only(["description", "amount", "currency", "callback_url", 
            "custom_data", "customer"
        ]);

        $transaction = $this->service->create($merchant, $data);


        /**
         *      ANTI-FRAUD VERIFICATION
         */
        // $antiFraud = app(AntiFraudService::class);
        // $result = $antiFraud->evaluate($transaction);

        // if ($result['status'] === 'blocked') {
        //     $transaction->status = 'failed';
        //     $transaction->reason = $result['reason'];
        //     $transaction->save();

        //     return response()->json([
        //         'success' => false,
        //         'message' => $result['reason']
        //     ], 422);
        // }

        // if ($result['status'] === 'review') {
        //     $transaction->status = 'manual_review';
        //     $transaction->reason = $result['reason'];
        //     $transaction->save();

        //     return response()->json([
        //         'success' => false,
        //         'manual_review' => true,
        //         'message' => $result['reason']
        //     ], 202);
        // }
        // Fin anti-fraud

        return (new TransactionResource($transaction) )->additional([
            'success'=> true,
        ])->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $merchant = $request->user();
        $transaction = $this->service->findOrFail($merchant, $id);

        return (new TransactionResource($transaction))->additional(['success' => true]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, string $id)
    {
        $merchant = $request->user();
        $transaction = $this->service->findOrFail($merchant, $id);

        if($transaction->status != TransactionStatusEnum::Pending){
            return response()->json([
                'success' => false,
                'message' => "Impossible de mettre a jour la transaction",
            ], 422);
        }

        $data = $request->only(['description','amount','callback_url', 'custom_data']);
        $transaction = $this->service->update($merchant, $transaction, $data);

        return (new TransactionResource($transaction))->additional(['success' => true]);
   
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $merchant = $request->user();
        $customer = $this->service->findOrFail($merchant, $id);

        $this->service->delete($merchant, $customer);

        return response()->json(['success' => true, 'message' => 'Customer deleted'], 204);
    }


    public function token(Request $request, string|int $id)
    {
        $merchant = $request->user();

        $transaction = Transaction::where('merchant_id', $merchant->id)
            ->where('id', $id)
            ->firstOrFail();

        $data = $this->service->generatePaymentToken($transaction);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function process(ProcessTransactionRequest $request)
    {

        $service = app(PaymentService::class);
        $data = $service->processMobilePayment(
            $request->token,
            $request->customer,
            $request->provider
        );

        return response()->json($data, $data['success'] ? 200 : 422);

    }

    public function verify(Request $request, string $id){

        $service = app(PaymentService::class);
        $data = $service->verifyPayment( $id);

        return response()->json($data, $data['success'] ? 200 : 422);

    }

}
