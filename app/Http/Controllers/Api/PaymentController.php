<?php

namespace App\Http\Controllers\Api;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function index()
    {
        try {
            return response()->json(Payment::all(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => 'required|exists:clients,id',
                'sale_id' => 'required|exists:sales,id',
                'amount' => 'required|numeric|min:0',
                'payment_method_id' => 'required|exists:payment_methods,id',
                'payment_date' => 'required|date',
                'status' => 'required|in:PENDIENTE,COMPLETADO,RECHAZADO'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $payment = Payment::create($request->all());
            return response()->json($payment, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $payment = Payment::find($id);
            if (!$payment) {
                return response()->json(['error' => 'Pago no encontrado'], 404);
            }
            return response()->json($payment, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => 'sometimes|exists:clients,id',
                'sale_id' => 'sometimes|exists:sales,id',
                'amount' => 'sometimes|numeric|min:0',
                'payment_method_id' => 'sometimes|exists:payment_methods,id',
                'payment_date' => 'sometimes|date',
                'status' => 'sometimes|in:PENDIENTE,COMPLETADO,RECHAZADO'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $payment = Payment::find($id);
            if (!$payment) {
                return response()->json(['error' => 'Pago no encontrado'], 404);
            }

            $payment->update($request->all());
            return response()->json($payment, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $payment = Payment::find($id);
            if (!$payment) {
                return response()->json(['error' => 'Pago no encontrado'], 404);
            }

            $payment->delete();
            return response()->json(['message' => 'Pago eliminado correctamente'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
