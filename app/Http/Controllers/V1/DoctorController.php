<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::all();

        return response()->json($doctors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'status' => __('http-statuses.400')
            ], 400);
        }

        $doctor = Doctor::create($request->all());

        return response()->json($doctor, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        return response()->json($doctor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'specialty' => 'string|max:255',
        ]);

        if (!$request->hasAny(['name', 'specialty'])) {
            return response()->json([
                'error' => [__('You must submit at least one valid field'),
                'status' => __('http-statuses.400')
                ]], 400);
        };
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'status' => __('http-statuses.400')
            ], 400);
        }

        $doctor->update($request->all());

        return response()->json($doctor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return response()->json([
            'message' => __(':Attribute deleted successfully', ['Attribute'=>'دکتر']),
            'status' => __('http-statuses.200')
        ]);
    }
}
