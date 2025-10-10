<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function notificationHandler(Request $request)
    {
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        
        $notification = new \Midtrans\Notification();

        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status;

        $order = Order::find($orderId);
        if (!$order) {
            Log::warning("Midtrans Notification: Order with ID {$orderId} not found.");
            return response()->json(['message' => 'Order not found.'], 404);
        }

        // Validasi signature key (keamanan)
        $signature = hash('sha512', $orderId . $notification->status_code . $notification->gross_amount . config('midtrans.server_key'));
        if ($notification->signature_key !== $signature) {
            Log::error("Midtrans Notification: Invalid signature for Order ID {$orderId}.");
            return response()->json(['message' => 'Invalid signature.'], 403);
        }

        DB::beginTransaction();
        try {
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    // Pembayaran berhasil dan dianggap aman
                    $order->status = Order::STATUS_PAYMENT_CONFIRMED;
                    $this->updateScheduleStatus($order, Schedule::STATUS_BOOKED);
                }
            } else if ($transactionStatus == 'settlement') {
                // Pembayaran berhasil
                $order->status = Order::STATUS_PAYMENT_CONFIRMED;
                $this->updateScheduleStatus($order, Schedule::STATUS_BOOKED);

            } else if ($transactionStatus == 'pending') {
                // Pembayaran tertunda (misal: transfer bank, cstore)
                $order->status = Order::STATUS_PENDING_CONFIRMATION;
            } else if (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                // Pembayaran ditolak, dibatalkan, atau kadaluwarsa
                $order->status = Order::STATUS_FAILED;
                $this->updateScheduleStatus($order, Schedule::STATUS_CANCELLED);
            }

            $order->save();
            DB::commit();

            Log::info("Midtrans Notification: Status for Order ID {$orderId} updated to {$order->status}.");
            return response()->json(['message' => 'Notification processed successfully.']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Midtrans Notification Error: Failed to update order status for Order ID {$orderId}. Error: " . $e->getMessage());
            return response()->json(['message' => 'Server error while processing notification.'], 500);
        }
    }

    private function updateScheduleStatus(Order $order, string $newStatus)
    {
        if ($order->schedule) {
            $order->schedule->status = $newStatus;
            $order->schedule->save();
        }
    }
}