<?php

namespace App\Http\Controllers\Api;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{
    public function index()
    {
        try {
            return response()->json(Warehouse::all(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'location' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $warehouse = Warehouse::create($request->all());
            return response()->json($warehouse, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $warehouse = Warehouse::find($id);
            if (!$warehouse) {
                return response()->json(['error' => 'Almacén no encontrado'], 404);
            }
            return response()->json($warehouse, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'location' => 'sometimes|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $warehouse = Warehouse::find($id);
            if (!$warehouse) {
                return response()->json(['error' => 'Almacén no encontrado'], 404);
            }

            $warehouse->update($request->all());
            return response()->json($warehouse, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $warehouse = Warehouse::find($id);
            if (!$warehouse) {
                return response()->json(['error' => 'Almacén no encontrado'], 404);
            }

            $warehouse->delete();
            return response()->json(['message' => 'Almacén eliminado correctamente'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
