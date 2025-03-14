<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
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
}
