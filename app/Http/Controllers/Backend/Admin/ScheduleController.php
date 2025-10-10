<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Gor;
use App\Models\Schedule;
use App\Models\Field;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    private function getMyGor()
    {
        return Gor::where('user_id', Auth::id())->firstOrFail();
    }

    public function index(Request $request)
    {
        $gor = $this->getMyGor();
        $this->authorize('viewAny', [Schedule::class, $gor]);
        $query = Schedule::where('gor_id', $gor->id)
                         ->with(['user', 'field', 'order' => function($q){
                            $q->select('id', 'tanggal_main', 'jam_mulai', 'jam_selesai', 'durasi', 'status as order_status');
                         }])
                         ->latest('id');

        if ($request->filled('field_id_search')) {
            $field_id = $request->field_id_search;
            if($field_id !== 'all'){
                $query->where('field_id', $field_id);
            }
        }
        if ($request->filled('date_search')) {
            $query->whereHas('order', function ($q) use ($request) {
                $q->where('tanggal_main', $request->date_search);
            });
        }
        if ($request->filled('status_search')) {
             $status = $request->status_search;
             if($status !== 'all'){
                $query->where('status', $status);
             }
        } else {
            $query->whereIn('status', ['Booked', 'On Progress']);
        }

        $schedules = $query->paginate(15);
        $fields = Field::where('gor_id', $gor->id)->orderBy('nama_lapangan')->get();
        $scheduleStatuses = Schedule::where('gor_id', $gor->id)->distinct()->pluck('status')->sort()->values()->all();
        $title = 'Jadwal';
        return view('backend.admin.schedules.index', compact('gor', 'schedules', 'fields', 'scheduleStatuses', 'title'));
    }

    public function cancelSchedule(Schedule $schedule, Request $request)
    {
        $this->authorize('update', $schedule);
        $gor = $this->getMyGor();
        if ($schedule->gor_id !== $gor->id) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($schedule->status, ['Booked', 'On Progress'])) {
             return back()->with('error', 'This schedule cannot be cancelled (current status: ' . $schedule->status . ').');
        }

        // Alasan pembatalan (opsional, bisa dari input form)
        $cancellationReason = $request->input('cancellation_reason', 'Cancelled by GOR Admin.');

        DB::beginTransaction();
        try {
            $schedule->status = 'Cancelled';
            // $schedule->cancellation_notes = $cancellationReason; // Jika ada kolomnya
            $schedule->save();

            if ($schedule->order) {
                $schedule->order->status = 'Cancelled';
                // $schedule->order->cancellation_reason = $cancellationReason; // Jika ada kolomnya
                $schedule->order->save();
            }

            DB::commit();
            return back()->with('success', 'Schedule and associated order have been cancelled.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error cancelling schedule for admin: " . $e->getMessage());
            return back()->with('error', 'Failed to cancel schedule. Please try again.');
        }
    }

    // Anda bisa menambahkan metode blockTime di sini jika diperlukan
    // public function createBlock(Request $request) { ... }
    // public function storeBlock(Request $request) { ... }
}