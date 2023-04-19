<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::all();

        return response()->json($appointments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'patient_id' => 'required|numeric',
            'doctor_id' => 'required|numeric',
            'appointment_time' => 'required|date',
        ]);

        $appointment = Appointment::create($validatedData);

        return response()->json($appointment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        return response()->json($appointment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'numeric',
            'doctor_id' => 'numeric',
            'appointment_time' => 'date',
        ]);

        if (!$request->hasAny(['patient_id', 'doctor_id', 'appointment_time'])) {
            return response()->json([
                'error' => [__('You must submit at least one valid field'),
                'status' => __('http-statuses.400')
                ]], 400);
        };
        // شما حداقل باید یک فیلد صحیح (patient_idو doctor_idو appointment_time) را ارسال کنید
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'status' => __('http-statuses.400')
            ], 400);
        };

        $appointment->update($request->all());

        return response()->json($appointment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json([
            'message' => __(':Attribute deleted successfully', ['Attribute'=>'نوبت تعیین شده']),
            'status' => __('http-statuses.200')
        ]);
    }
}
