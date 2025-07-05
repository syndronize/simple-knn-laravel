<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndustriesController extends Controller
{
    public function index()
    {
        // Fetch Industriess from the database
        $data['industries'] = DB::table('industry')
            ->select('industry.id', 'industry.name')
            ->get();
        return view('backend.industries.index', compact('data'));
    }
    public function create(Request $request)
    {
        // dd($request->all());
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Create Industries
            DB::table('industry')->insert([
                'name' => $request->name,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Industries created successfully',
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        //return in json format
        $industry = DB::table('industry')
            ->select('id', 'name')
            ->where('id', $id)
            ->first();
        // dd($industry);
        if (!$industry) {
            return response()->json([
                'status' => 'error',
                'message' => 'Industries not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $industry
        ]);
    }
    public function update($id, Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Update Industries
            DB::table('industry')
                ->where('id', $id)
                ->update([
                    'name' => $request->name,
                ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Industries updated successfully',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
