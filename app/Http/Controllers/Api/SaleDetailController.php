<?php

namespace App\Http\Controllers\Api;

use App\Models\SalesDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SaleDetailsController extends Controller
{
    public function index()
    {
        try {
            return response()->json(SalesDetail::all(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sale_id' => 'required|exists:sales,id',
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'subtotal' => 'required|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $saleDetail = SalesDetail::create($request->all());
            return response()->json($saleDetail, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $saleDetail = SalesDetail::find($id);
            if (!$saleDetail) {
                return response()->json(['error' => 'Detalle de venta no encontrado'], 404);
            }
            return response()->json($saleDetail, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sale_id' => 'sometimes|exists:sales,id',
                'product_id' => 'sometimes|exists:products,id',
                'quantity' => 'sometimes|integer|min:1',
                'subtotal' => 'sometimes|numeric|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $saleDetail = SalesDetail::find($id);
            if (!$saleDetail) {
                return response()->json(['error' => 'Detalle de venta no encontrado'], 404);
            }

            $saleDetail->update($request->all());
            return response()->json($saleDetail, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $saleDetail = SalesDetail::find($id);
            if (!$saleDetail) {
                return response()->json(['error' => 'Detalle de venta no encontrado'], 404);
            }

            $saleDetail->delete();
            return response()->json(['message' => 'Detalle de venta eliminado correctamente'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
