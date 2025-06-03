<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private $testMode;
    private $storeId;
    private $storePassword;
    private $baseUrl;

    public function __construct()
    {
        $this->testMode = config('services.sslcommerz.test_mode');
        $this->storeId = config('services.sslcommerz.store_id');
        $this->storePassword = config('services.sslcommerz.store_password');
        $this->baseUrl = $this->testMode
            ? 'https://sandbox.sslcommerz.com'
            : 'https://securepay.sslcommerz.com';
    }

    public function initiatePayment(Order $order)
    {
        try {
            $transactionId = 'ORGANIC' . uniqid();
            
            $postData = [
                'store_id' => $this->storeId,
                'store_passwd' => $this->storePassword,
                'total_amount' => $order->total,
                'currency' => 'BDT',
                'tran_id' => $transactionId,
                'success_url' => route('customer.payment.success'),
                'fail_url' => route('customer.payment.fail'),
                'cancel_url' => route('customer.payment.cancel'),
                'ipn_url' => route('customer.payment.ipn'),
                'cus_name' => $order->first_name . ' ' . $order->last_name,
                'cus_email' => $order->email,
                'cus_phone' => $order->phone,
                'cus_add1' => $order->address,
                'cus_city' => $order->city,
                'cus_postcode' => $order->postal_code,
                'shipping_method' => $order->delivery_method,
                'product_name' => 'Organic Food Products',
                'product_category' => 'Food',
                'product_profile' => 'physical-goods',
            ];

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'transaction_id' => $transactionId,
                'amount' => $order->total,
                'status' => 'pending',
            ]);

            // Update order status
            $order->update([
                'payment_id' => $payment->id,
                'status' => 'payment_pending',
            ]);

            // Initiate SSLCommerz payment
            $response = Http::post($this->baseUrl . '/gwprocess/v4/api.php', $postData);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['status'] === 'SUCCESS') {
                    return redirect($data['GatewayPageURL']);
                }
                
                throw new \Exception($data['failedreason'] ?? 'Payment initiation failed');
            }
            
            throw new \Exception('Could not connect to payment gateway');

        } catch (\Exception $e) {
            Log::error('Payment initiation failed: ' . $e->getMessage());
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'Payment initiation failed. Please try again.');
        }
    }

    public function success(Request $request)
    {
        try {
            $payment = Payment::where('transaction_id', $request->tran_id)->firstOrFail();
            $order = $payment->order;

            // Validate payment
            $validationResponse = Http::post($this->baseUrl . '/validator/api/validationserverAPI.php', [
                'store_id' => $this->storeId,
                'store_passwd' => $this->storePassword,
                'val_id' => $request->val_id,
            ]);

            if ($validationResponse->successful()) {
                $data = $validationResponse->json();
                
                if ($data['status'] === 'VALID' || $data['status'] === 'VALIDATED') {
                    // Update payment record
                    $payment->update([
                        'status' => 'completed',
                        'payment_data' => $data,
                    ]);

                    // Update order status
                    $order->update(['status' => 'processing']);

                    return redirect()->route('customer.orders.show', $order)
                        ->with('success', 'Payment completed successfully!');
                }
            }

            throw new \Exception('Payment validation failed');

        } catch (\Exception $e) {
            Log::error('Payment success handling failed: ' . $e->getMessage());
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    public function fail(Request $request)
    {
        try {
            $payment = Payment::where('transaction_id', $request->tran_id)->firstOrFail();
            $order = $payment->order;

            // Update payment status
            $payment->update([
                'status' => 'failed',
                'payment_data' => $request->all(),
            ]);

            // Update order status
            $order->update(['status' => 'payment_failed']);

            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'Payment failed. Please try again.');

        } catch (\Exception $e) {
            Log::error('Payment fail handling failed: ' . $e->getMessage());
            return redirect()->route('customer.cart.index')
                ->with('error', 'Payment failed. Please try again.');
        }
    }

    public function cancel(Request $request)
    {
        try {
            $payment = Payment::where('transaction_id', $request->tran_id)->firstOrFail();
            $order = $payment->order;

            // Update payment status
            $payment->update([
                'status' => 'cancelled',
                'payment_data' => $request->all(),
            ]);

            // Update order status
            $order->update(['status' => 'payment_cancelled']);

            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'Payment was cancelled.');

        } catch (\Exception $e) {
            Log::error('Payment cancel handling failed: ' . $e->getMessage());
            return redirect()->route('customer.cart.index')
                ->with('error', 'Payment was cancelled.');
        }
    }

    public function ipn(Request $request)
    {
        try {
            $payment = Payment::where('transaction_id', $request->tran_id)->firstOrFail();
            
            // Validate payment through SSLCommerz
            $validationResponse = Http::post($this->baseUrl . '/validator/api/validationserverAPI.php', [
                'store_id' => $this->storeId,
                'store_passwd' => $this->storePassword,
                'val_id' => $request->val_id,
            ]);

            if ($validationResponse->successful()) {
                $data = $validationResponse->json();
                
                if ($data['status'] === 'VALID' || $data['status'] === 'VALIDATED') {
                    // Update payment record
                    $payment->update([
                        'status' => 'completed',
                        'payment_data' => $data,
                    ]);

                    // Update order status
                    $payment->order->update(['status' => 'processing']);
                }
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('IPN handling failed: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
} 