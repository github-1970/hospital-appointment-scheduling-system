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
            'working_hours_start' => 'required|date_format:H:i:s',
            'working_hours_end' => 'required|date_format:H:i:s',
            'max_appointments_per_hour' => 'nullable|integer|min:1|max:10',
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
            'working_hours_start' => 'date_format:H:i:s',
            'working_hours_end' => 'date_format:H:i:s',
            'max_appointments_per_hour' => 'nullable|integer|min:1|max:10',
        ]);

        if (count($request->input()) <= 1) {
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
