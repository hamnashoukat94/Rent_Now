<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    private array $cars = [
        ['name' => 'Toyota Corolla', 'price' => 1000, 'image' => 'car1.png'],
        ['name' => 'Honda Civic', 'price' => 1500, 'image' => 'car2.png'],
        ['name' => 'Suzuki Alto', 'price' => 800,  'image' => 'car3.png'],
    ];

    public function index()
    {
        return view('rentals', ['cars' => $this->cars]);
    }

    public function checkAvailability(Request $request)
    {
        $data = $request->validate([
            'car_name' => 'required|string',
            'booking_date' => 'required|date',
        ]);

        $booked = Booking::where('car_name', $data['car_name'])
            ->where('booking_date', $data['booking_date'])
            ->exists();

        return response()->json(['available' => !$booked]);
    }

    public function confirmBooking(Request $request)
    {
        $data = $request->validate([
            'car_name' => 'required|string',
            'location' => 'required|string|max:255',
            'booking_date' => 'required|date',
            'hours' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
        ]);

        if (Booking::where('car_name', $data['car_name'])
                ->where('booking_date', $data['booking_date'])
                ->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Car was just booked by someone else!',
            ]);
        }

        Booking::create([
            'user_id' => Auth::id(),
            'car_name' => $data['car_name'],
            'location' => $data['location'],
            'booking_date' => $data['booking_date'],
            'hours' => $data['hours'],
            'total_amount' => $data['total_amount'],
            'payment_status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Car successfully booked!',
        ]);
    }
}
