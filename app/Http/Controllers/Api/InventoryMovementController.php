<?php

namespace App\Http\Controllers\Api;

use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class InventoryMovementController extends Controller
{
    public function index()
    {
        try {
            return response()->json(InventoryMovement::all(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|integer|exists:products,id',
                'type' => 'required|in:ENTRADA,SALIDA',
                'quantity' => 'required|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $movement = InventoryMovement::create($request->all());
            return response()->json($movement, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $movement = InventoryMovement::find($id);
            if (!$movement) {
                return response()->json(['error' => 'Movimiento no encontrado'], 404);
            }
            return response()->json($movement, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'sometimes|integer|exists:products,id',
                'type' => 'sometimes|in:ENTRADA,SALIDA',
                'quantity' => 'sometimes|integer|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $movement = InventoryMovement::find($id);
            if (!$movement) {
                return response()->json(['error' => 'Movimiento no encontrado'], 404);
            }

            $movement->update($request->all());
            return response()->json($movement, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $movement = InventoryMovement::find($id);
            if (!$movement) {
                return response()->json(['error' => 'Movimiento no encontrado'], 404);
            }

            $movement->delete();
            return response()->json(['message' => 'Movimiento eliminado correctamente'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
