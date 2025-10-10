<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardDevContactController;
use App\Http\Controllers\DashboardDevController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\Dev\UserController;
use App\Http\Controllers\Backend\Dev\FieldController;
use App\Http\Controllers\Backend\Dev\GorController;
use App\Http\Controllers\Backend\Dev\ScheduleController;
use App\Http\Controllers\Backend\Dev\OrderController as DevOrderController;

use App\Http\Controllers\Backend\Admin\GorController as AdminGorController;
use App\Http\Controllers\Backend\Admin\FieldController as AdminFieldController;
use App\Http\Controllers\Backend\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Backend\Admin\ScheduleController as AdminScheduleController;

use App\Http\Controllers\Api\MidtransController;

Route::get('/login-required', function () {
    // Simpan URL halaman sebelumnya (halaman detail gor) agar bisa kembali setelah login
    session(['url.intended' => URL::previous()]);
    // Redirect ke halaman login dengan pesan notifikasi
    return redirect()->route('login')->with('loginError', 'Anda harus login terlebih dahulu untuk dapat memesan lapangan!');
})->name('login.required');


Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->middleware('guest')->name('login');
    Route::post('/login', 'loginAuth')->middleware('guest');
    Route::get('/register', 'register')->middleware('guest');
    Route::post('/register', 'registerStore')->middleware('guest');
    Route::post('/logout', 'logout')->middleware('auth');
});

//Home
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/gor', 'listGor');
    Route::get('/gor/{gor:slug_gor}', 'show');
    Route::get('/search', 'search')->name('search');
    Route::get('/api/search-filters', 'getSearchFilters');
});

//Profil
Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'index')->middleware('user');
});

Route::post('/orderStore', [OrderController::class, 'orderStore']);

//Contact

Route::get('/contact', function () {
    return view('frontend.contact', [
        'title' => 'Zofuta | Contact Kami'
    ]);
})->name('contact.index');
Route::post('/contact', [ContactController::class, 'StoreContact'])->name('form-contact');




Route::middleware(['auth'])->group(function () {
    // User Profile Routes (ProfileController menangani halaman profil)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Order Routes 
    Route::controller(OrderController::class)->group(function () {
        Route::post('/order/store', 'orderStore')->name('order.store');
        //Route::patch('/order/{order}/upload-proof', 'uploadProof')->name('order.upload_proof');
        Route::get('/order/{order}/pay', 'showPaymentPage')->name('order.pay'); 
        Route::get('/order/{order}/invoice', 'showInvoice')->name('order.invoice');
        Route::get('/payment/success/{order}', 'paymentSuccess')->name('payment.success');
        
    });
});

//Dashboard Dev
Route::middleware(['auth', 'dev'])->prefix('dashboarddev')->name('dev.')->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardDevController::class, 'index'])->name('index'); // Pastikan rute dashboard utama ada
    Route::get('/contact', [App\Http\Controllers\DashboardDevContactController::class, 'index'])->name('contact.index');
    Route::get('/contact/{contact}', [App\Http\Controllers\DashboardDevContactController::class, 'show'])->name('contact.show');
    Route::delete('/contact/{contact}', [App\Http\Controllers\DashboardDevContactController::class, 'destroy'])->name('contact.destroy');

    // Rute untuk Manajemen User
    Route::resource('users', App\Http\Controllers\Backend\Dev\UserController::class);

    // Rute untuk Manajemen Jadwal
    Route::get('schedules', [App\Http\Controllers\Backend\Dev\ScheduleController::class, 'index'])->name('schedules.index');

    // Rute untuk Manajemen GOR
    Route::resource('gors', App\Http\Controllers\Backend\Dev\GorController::class);
    Route::delete('gors/image/', [App\Http\Controllers\Backend\Dev\GorController::class, 'deleteImage'])->name('gors.image.delete'); // Route untuk hapus gambar spesifik
    Route::resource('gors.fields', App\Http\Controllers\Backend\Dev\FieldController::class);
    Route::resource('gors.fields', App\Http\Controllers\Backend\Dev\FieldController::class);

    Route::resource('orders', DevOrderController::class)->except(['create', 'store', 'destroy']);
    Route::patch('orders/{order}/status', [DevOrderController::class, 'updateStatus'])->name('orders.updateStatus');
});



//Dashboard Admin
Route::middleware(['auth', 'admin'])->prefix('dashboardadmin')->name('admin.')->group(function () {
    Route::get('/', [DashboardAdminController::class, 'index'])->name('dashboard'); // Nama rute dashboard admin

    // Manajemen GOR Miliknya
    Route::get('gor/edit', [AdminGorController::class, 'edit'])->name('gor.edit');
    Route::put('gor', [AdminGorController::class, 'update'])->name('gor.update');
    Route::post('gor/images', [AdminGorController::class, 'uploadImage'])->name('gor.images.upload'); // Untuk tambah gambar baru
    Route::delete('gor/image/{gorImage}', [AdminGorController::class, 'deleteImage'])->name('gor.image.delete'); // Hapus gambar spesifik
    Route::get('gor/edit', [AdminGorController::class, 'edit'])->name('gor.edit');
    Route::put('gor', [AdminGorController::class, 'update'])->name('gor.update');
    Route::post('gor/images', [AdminGorController::class, 'uploadImage'])->name('gor.images.upload');
    Route::delete('gor/image/{gorImage}', [AdminGorController::class, 'deleteImage'])->name('gor.image.delete');
    Route::resource('fields', AdminFieldController::class);

    // Manajemen Lapangan
    Route::resource('fields', AdminFieldController::class);

    // Manajemen Pesanan 
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'edit', 'update']); // Admin bisa update status
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'edit', 'update']);
    
    // Manajemen Jadwal 
    Route::get('schedules', [AdminScheduleController::class, 'index'])->name('schedules.index');
    Route::patch('schedules/{schedule}/cancel', [AdminScheduleController::class, 'cancelSchedule'])->name('schedules.cancel');
    

    // konfirmasi dan tolak pembayaran oleh Admin
    Route::patch('orders/{order}/confirm-payment', [AdminOrderController::class, 'confirmPayment'])->name('orders.confirm_payment');
    Route::patch('orders/{order}/reject-payment', [AdminOrderController::class, 'rejectPayment'])->name('orders.reject_payment');
});

Route::post('/midtrans/notification', [MidtransController::class, 'notificationHandler']);

Route::get('/api/kecamatan/{wilayah}', [GorController::class, 'getKecamatan']);


