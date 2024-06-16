<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class PlatformController extends Controller
{
    public function index()
    {
        try {
            $platforms = Platform::paginate(10);

            return response()->json([
                'status' => 'success',
                'data' => $platforms,
                'pagination' => [
                    'total' => $platforms->total(),
                    'count' => $platforms->count(),
                    'per_page' => $platforms->perPage(),
                    'current_page' => $platforms->currentPage(),
                    'total_pages' => $platforms->lastPage()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving data',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'platform_name' => 'required|string',
                'token_value' => 'required|numeric',
                'exchange_rate' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $platform = Platform::create($request->all());
            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $platform,
                'message' => 'Platform created successfully'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the platform',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $platform = Platform::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $platform
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Platform not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the platform',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $platform = Platform::findOrFail($id);

            $rules = [
                'platform_name' => 'sometimes|required|string',
                'token_value' => 'sometimes|required|numeric',
                'exchange_rate' => 'sometimes|required|numeric',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $platform->update($request->only(array_keys($rules)));
            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $platform,
                'message' => 'Platform updated successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Platform not found'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the platform',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $platform = Platform::findOrFail($id);
            $platform->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Platform deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Platform not found'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the platform',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
