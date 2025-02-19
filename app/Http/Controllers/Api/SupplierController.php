<?php

namespace App\Http\Controllers\Api;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index()
    {
        try {
            return response()->json(Supplier::all(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'contact_info' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $supplier = Supplier::create($request->all());
            return response()->json($supplier, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $supplier = Supplier::find($id);
            if (!$supplier) {
                return response()->json(['error' => 'Proveedor no encontrado'], 404);
            }
            return response()->json($supplier, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'contact_info' => 'sometimes|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $supplier = Supplier::find($id);
            if (!$supplier) {
                return response()->json(['error' => 'Proveedor no encontrado'], 404);
            }

            $supplier->update($request->all());
            return response()->json($supplier, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $supplier = Supplier::find($id);
            if (!$supplier) {
                return response()->json(['error' => 'Proveedor no encontrado'], 404);
            }

            $supplier->delete();
            return response()->json(['message' => 'Proveedor eliminado correctamente'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
