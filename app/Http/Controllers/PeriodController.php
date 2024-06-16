<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class PeriodController extends Controller
{
    public function index()
    {
        try {
            $periods = Period::paginate(10);

            return response()->json([
                'status' => 'success',
                'data' => $periods,
                'pagination' => [
                    'total' => $periods->total(),
                    'count' => $periods->count(),
                    'per_page' => $periods->perPage(),
                    'current_page' => $periods->currentPage(),
                    'total_pages' => $periods->lastPage()
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
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'platform_id' => 'required|exists:platforms,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $period = Period::create($request->all());
            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $period,
                'message' => 'Period created successfully'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the period',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $period = Period::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $period
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Period not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the period',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $period = Period::findOrFail($id);

            $rules = [
                'start_date' => 'sometimes|required|date',
                'end_date' => 'sometimes|required|date|after_or_equal:start_date',
                'platform_id' => 'sometimes|required|exists:platforms,id',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $period->update($request->only(array_keys($rules)));
            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $period,
                'message' => 'Period updated successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Period not found'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the period',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $period = Period::findOrFail($id);
            $period->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Period deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Period not found'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the period',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
