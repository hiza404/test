<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Booking;

class BookingController extends Controller
{
    /**
     * Display the booking search screen (auto-displaying booking list with pagination)
     *
     * @param Request $request
     * @return View
     */
    public function showSearch(Request $request): View
    {
        $bookingList = Booking::with('hotel.prefecture')->orderBy('created_at', 'desc')->paginate(10)->appends($request->except('_token', 'page'));

        return view('admin.booking.result', compact('bookingList'));
    }

    /**
     * Search bookings and display results with pagination
     *
     * @param Request $request
     * @return View
     */
    public function searchResult(Request $request): View
    {
        $customerName = $request->input('customer_name');
        $customerContact = $request->input('customer_contact');
        $checkinTime = $request->input('checkin_time');
        $checkoutTime = $request->input('checkout_time');

        $bookingList = Booking::searchBookingsQuery(
            !empty($customerName) ? trim($customerName) : null,
            !empty($customerContact) ? trim($customerContact) : null,
            !empty($checkinTime) ? $checkinTime : null,
            !empty($checkoutTime) ? $checkoutTime : null
        )->paginate(10)->appends($request->except('_token', 'page'));

        return view('admin.booking.result', [
            'bookingList' => $bookingList,
            'customer_name' => $customerName,
            'customer_contact' => $customerContact,
            'checkin_time' => $checkinTime,
            'checkout_time' => $checkoutTime,
        ]);
    }
}

