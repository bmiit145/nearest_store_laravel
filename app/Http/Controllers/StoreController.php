<?php

namespace App\Http\Controllers;

use App\Models\Store;
use http\Env\Response;
use Illuminate\Http\Request;

class StoreController extends Controller
{

    public function findNearestStore(Request $request)
    {

        // validate request
        $request->validate([
            'latitute' => 'required | numeric | between:-90,90',
            'longitute' => 'required | numeric | between:-180,180',
        ], [
            'latitute.required' => 'The latitute field is required.',
            'longitute.required' => 'The longitute field is required.',
            'latitute.numeric' => 'The latitute field must be a number.',
            'longitute.numeric' => 'The longitute field must be a number.',
            'latitute.between' => 'The latitute field must be between -90 and 90.',
            'longitute.between' => 'The longitute field must be between -180 and 180.',
        ]);

        $userLatitute = $request->latitute;
        $userLongitute = $request->longitute;
        $distanceInKm = 10;


        // get all stores which near to the user up to 10 KMs
        $stores = Store::select('*')
            ->selectRaw(
                '(6371 * acos(cos(radians(?)) * cos(radians(latitute)) * cos(radians(longitute) - radians(?)) + sin(radians(?)) * sin(radians(latitute)))) AS distance',
                [$userLatitute, $userLongitute, $userLatitute]
            )
            ->whereRaw('(6371 * acos(cos(radians(?)) * cos(radians(latitute)) * cos(radians(longitute) - radians(?)) + sin(radians(?)) * sin(radians(latitute)))) <= ?', [$userLatitute, $userLongitute, $userLatitute, $distanceInKm])
            ->orderBy('distance')
            ->get();

        $count = $stores->count();
        if ($stores->count() == 0) {
            return response()->json(['error' => 'No stores found'] , 404);
        }
        return response()->json(['stores' => $stores , 'message' => "$count Store Found within 10 KMs"] , 200);
    }


    public function addStoreForm(Request $request)
    {
        // validate request
        $request->validate([
            'store_name' => 'required | string | max:255',
            'store_address' => 'required | string | max:255',
            'latitute' => 'required | numeric | between:-90,90',
            'longitute' => 'required | numeric | between:-180,180',
        ], [
            'store_name.required' => 'The store name field is required.',
            'store_address.required' => 'The store address field is required.',
            'latitute.required' => 'The latitute field is required.',
            'longitute.required' => 'The longitute field is required.',
            'store_name.string' => 'The store name field must be a string.',
            'store_address.string' => 'The store address field must be a string.',
            'latitute.numeric' => 'The latitute field must be a number.',
            'longitute.numeric' => 'The longitute field must be a number.',
            'latitute.between' => 'The latitute field must be between -90 and 90.',
            'longitute.between' => 'The longitute field must be between -180 and 180.',
        ]);

        $store = new Store();
        $store->store_name = $request->store_name;
        $store->store_address = $request->store_address;
        $store->latitute = $request->latitute;
        $store->longitute = $request->longitute;

        if ($store->save()){
        return response()->json(['message' => 'Store added successfully'] , 200);
        }else{
            return response()->json(['error' => 'Something went wrong'] , 500);
        }
    }

}
