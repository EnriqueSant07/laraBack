<?php

namespace App\Http\Controllers\Api;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PaymentMethodController extends Controller
{
    public function index()
    {
        try {
            return response()->json(PaymentMethod::all(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $paymentMethod = PaymentMethod::create($request->all());
            return response()->json($paymentMethod, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $paymentMethod = PaymentMethod::find($id);
            if (!$paymentMethod) {
                return response()->json(['error' => 'Método de pago no encontrado'], 404);
            }
            return response()->json($paymentMethod, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $paymentMethod = PaymentMethod::find($id);
            if (!$paymentMethod) {
                return response()->json(['error' => 'Método de pago no encontrado'], 404);
            }

            $paymentMethod->update($request->all());
            return response()->json($paymentMethod, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $paymentMethod = PaymentMethod::find($id);
            if (!$paymentMethod) {
                return response()->json(['error' => 'Método de pago no encontrado'], 404);
            }

            $paymentMethod->delete();
            return response()->json(['message' => 'Método de pago eliminado correctamente'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
