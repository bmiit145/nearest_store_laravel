<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class homeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function findNearestStore(Request $request)
    {

        // validate request
        $request->validate([
            'latitute' => 'required | numeric | between:-90,90',
            'longitute' => 'required | numeric | between:-180,180',
        ], [
            'latitute.required' => 'The latitude field is required.',
            'longitute.required' => 'The longitude field is required.',
            'latitute.numeric' => 'The latitude field must be a number.',
            'longitute.numeric' => 'The longitude field must be a number.',
            'latitute.between' => 'The latitude field must be between -90 and 90.',
            'longitute.between' => 'The longitude field must be between -180 and 180.',
        ]);

        $userLatitude = $request->latitute;
        $userLongitude = $request->longitute;
        $distanceInKm = 10;


        // get all stores which near to the user up to 10 KMs
        $stores = Store::select('*')
            ->selectRaw(
                '(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
                [$userLatitude, $userLongitude, $userLatitude]
            )
            ->whereRaw('(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?', [$userLatitude, $userLongitude, $userLatitude, $distanceInKm])
            ->orderBy('distance')
            ->get();

        $count = $stores->count();
if ($stores->count() == 0) {
    return response()->json(['error' => 'No stores found'] , 404);
}
        return response()->json(['stores' => $stores , 'message' => "$count Store Found within 10 KMs"] , 200);
    }

}
