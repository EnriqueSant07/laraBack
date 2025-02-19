<?php

namespace App\Http\Controllers\Api;

use App\Models\Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    public function index()
    {
        try {
            return response()->json(Staff::all(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'position' => 'required|string|max:255',
                'email' => 'required|email|unique:staff,email',
                'phone' => 'nullable|string|max:20'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $staff = Staff::create($request->all());
            return response()->json($staff, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $staff = Staff::find($id);
            if (!$staff) {
                return response()->json(['error' => 'Personal no encontrado'], 404);
            }
            return response()->json($staff, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'position' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:staff,email,' . $id,
                'phone' => 'nullable|string|max:20'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $staff = Staff::find($id);
            if (!$staff) {
                return response()->json(['error' => 'Personal no encontrado'], 404);
            }

            $staff->update($request->all());
            return response()->json($staff, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $staff = Staff::find($id);
            if (!$staff) {
                return response()->json(['error' => 'Personal no encontrado'], 404);
            }

            $staff->delete();
            return response()->json(['message' => 'Personal eliminado correctamente'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
