<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\Admin\TopController as AdminTopController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

/** user screen */
Route::get('/', [TopController::class, 'index'])->name('top');
Route::get('/{prefecture_name_alpha}/hotellist', [HotelController::class, 'showList'])->name('hotelList');
Route::get('/hotel/{hotel_id}', [HotelController::class, 'showDetail'])->name('hotelDetail');

/** admin screen */
Route::get('/admin', [AdminTopController::class, 'index'])->name('adminTop');

// Admin Hotel routes
Route::get('/admin/hotel/search', [AdminHotelController::class, 'showSearch'])->name('adminHotelSearchPage');
Route::match(['get', 'post'], '/admin/hotel/search/result', [AdminHotelController::class, 'searchResult'])->name('adminHotelSearchResult');
Route::get('/admin/hotel/create', [AdminHotelController::class, 'showCreate'])->name('adminHotelCreatePage');
Route::post('/admin/hotel/create', [AdminHotelController::class, 'create'])->name('adminHotelCreateProcess');
Route::get('/admin/hotel/edit', [AdminHotelController::class, 'showEdit'])->name('adminHotelEditPage');
Route::post('/admin/hotel/edit/confirm', [AdminHotelController::class, 'showEditConfirm'])->name('adminHotelEditConfirm');
Route::post('/admin/hotel/edit', [AdminHotelController::class, 'edit'])->name('adminHotelEditProcess');
Route::get('/admin/hotel/edit/complete', [AdminHotelController::class, 'showEditComplete'])->name('adminHotelEditCompletePage');
Route::post('/admin/hotel/delete', [AdminHotelController::class, 'delete'])->name('adminHotelDeleteProcess');

// Admin Booking routes (optional feature)
Route::get('/admin/booking/search', [AdminBookingController::class, 'showSearch'])->name('adminBookingSearchPage');
Route::match(['get', 'post'], '/admin/booking/search/result', [AdminBookingController::class, 'searchResult'])->name('adminBookingSearchResult');

