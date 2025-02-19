<?php

namespace App\Http\Controllers\Api;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function index()
    {
        try {
            return response()->json(Appointment::all(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => 'required|exists:clients,id',
                'service_id' => 'required|exists:services,id',
                'date' => 'required|date',
                'status' => 'in:PENDIENTE,CONFIRMADO,CANCELADO'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $appointment = Appointment::create($request->all());
            return response()->json($appointment, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $appointment = Appointment::find($id);
            if (!$appointment) {
                return response()->json(['error' => 'Cita no encontrada'], 404);
            }
            return response()->json($appointment, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => 'sometimes|exists:clients,id',
                'service_id' => 'sometimes|exists:services,id',
                'date' => 'sometimes|date',
                'status' => 'in:PENDIENTE,CONFIRMADO,CANCELADO'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $appointment = Appointment::find($id);
            if (!$appointment) {
                return response()->json(['error' => 'Cita no encontrada'], 404);
            }

            $appointment->update($request->all());
            return response()->json($appointment, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $appointment = Appointment::find($id);
            if (!$appointment) {
                return response()->json(['error' => 'Cita no encontrada'], 404);
            }

            $appointment->delete();
            return response()->json(['message' => 'Cita eliminada correctamente'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
