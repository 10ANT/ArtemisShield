<?php

// app/Http/Controllers/Api/RouteController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RouteController extends Controller
{
    /**
     * Display a listing of all saved routes.
     */
    public function index()
    {
        return response()->json(Route::all());
    }

    /**
     * Store a newly created route in the database.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'start_latitude' => 'required|numeric',
            'start_longitude' => 'required|numeric',
            'end_latitude' => 'required|numeric',
            'end_longitude' => 'required|numeric',
            'geometry' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // The geometry from the request is already a JSON string, so we pass it directly.
        $validatedData = $validator->validated();
        $validatedData['geometry'] = json_decode($validatedData['geometry'], true);

        $route = Route::create($validatedData);

        return response()->json($route, 201);
    }

    /**
     * Remove the specified route from the database.
     */
    public function destroy(Route $route)
    {
        $route->delete();
        return response()->json(null, 204); // 204 No Content
    }
}