<?php

namespace App\Http\Controllers\Api;

use App\Models\Sale;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    public function index()
    {
        try {
            return response()->json(Sale::all(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => 'required|exists:clients,id',
                'total_price' => 'required|numeric|min:0',
                'status' => 'required|in:PENDIENTE,PAGADO,CANCELADO'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $sale = Sale::create($request->all());
            return response()->json($sale, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $sale = Sale::find($id);
            if (!$sale) {
                return response()->json(['error' => 'Venta no encontrada'], 404);
            }
            return response()->json($sale, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => 'sometimes|exists:clients,id',
                'total_price' => 'sometimes|numeric|min:0',
                'status' => 'sometimes|in:PENDIENTE,PAGADO,CANCELADO'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $sale = Sale::find($id);
            if (!$sale) {
                return response()->json(['error' => 'Venta no encontrada'], 404);
            }

            $sale->update($request->all());
            return response()->json($sale, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $sale = Sale::find($id);
            if (!$sale) {
                return response()->json(['error' => 'Venta no encontrada'], 404);
            }

            $sale->delete();
            return response()->json(['message' => 'Venta eliminada correctamente'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
