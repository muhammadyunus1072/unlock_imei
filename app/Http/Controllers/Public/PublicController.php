<?php

namespace App\Http\Controllers\Public;

use App\Events\TransactionPaidProcessed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Transaction\Transaction;
use App\Repositories\Transaction\Transaction\TransactionRepository;

class PublicController extends Controller
{
    public function index()
    {
        // return RomanConverter::toRoman(12);
        // return XenditService::createInvoice();
        // https://app.kuystudio.id/booking/review/530673e4-d3e8-48b4-ad35-bae73f3b32b8
        // https://app.kuystudio.id/booking/review/2217f6c6-7874-4ce2-803d-d5e5c639fd0f
        return view('app.public.product.index');
    }
    
    public function product_booking(Request $request)
    {
        return view('app.public.product-booking.detail', ["objId" => $request->id]);
    }
    
    public function booking_review(Request $request)
    {
        $bookingSession = session('booking_data');
        if(!$bookingSession) {
            return redirect()->route('public.index');
        }
        return view('app.public.booking-review.detail', ["objId" => $request->id]);
    }

    public function handleCallback(Request $request)
    {
        try {
            // ✅ Log all incoming requests
            Log::info('Xendit Callback Received:', $request->all());
            Log::info('Xendit Callback Received: '.$request->external_id);

            // ✅ Verify request signature (Optional: Implement HMAC verification for security)
            if (!$this->isValidSignature($request)) {
                Log::warning('Invalid Xendit callback signature');
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            DB::beginTransaction();
            // ✅ Ensure idempotency: Check if callback was already processed
            $invoiceExternalId = $request->external_id;

            $transaction = TransactionRepository::findBy([
                // ['external_id' => $invoiceExternalId],
                ['external_id' => 'STUDIO/00001/XX/III/2025'],
            ]);
            Log::info('Transaction : ', $transaction->toArray());
            if (!$transaction) {
                throw new \Exception("Transaction not found for Invoice: $invoiceExternalId");
            }

            if (in_array($transaction->status, ['paid'])) {
                throw new \Exception("Duplicate callback ignored for Invoice: $invoiceExternalId");
            }

            DB::beginTransaction();

            switch ($request->status) {
                case 'EXPIRED':
                    if ($transaction->status !== Transaction::STATUS_PAID) {
                        $transaction->status = Transaction::STATUS_EXPIRED;
                        $transaction->save();
                        Log::info("Transaction expired: $invoiceExternalId", ['transaction_id' => $transaction->id]);
                    }
                    break;
        
                case 'PAID':
                    // If the payment is late (after expiry), update it to paid
                    if ($transaction->status === Transaction::STATUS_EXPIRED) {
                        Log::info("Late payment received for expired invoice: $invoiceExternalId", ['transaction_id' => $transaction->id]);
                    }
        
                    $transaction->status = Transaction::STATUS_PAID;
                    $transaction->save();
        
                    // Trigger event for further processing (e.g., email confirmation)
                    event(new TransactionPaidProcessed($transaction));
                    break;
            }
            Log::info("Transaction updated: Invoice ID $invoiceExternalId, Status: $transaction->status");

            DB::commit();
            

            return response()->json(['message' => 'Callback received successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Xendit callback error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing callback'], 500);
        }
    }

    private function isValidSignature(Request $request)
    {
        $expectedToken = env('XENDIT_CALLBACK_TOKEN');
        Log::info("TOKEN ".$expectedToken);
        Log::info("XTOKEN ".$request->header('X-CALLBACK-TOKEN'));
        return $request->header('X-CALLBACK-TOKEN') === $expectedToken;
    }
}
