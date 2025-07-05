<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class LeadController extends Controller
{
    public function index()
    {
        // Fetch users from the database selecting all columns except the password
        $data['leads'] = DB::table('leads')
            ->select(

                'leads.id',
                'leads.name',
                'leads.email',
                'leads.notelp',
                'industry.name as industry_name',
                'leads.alamat',
                'leads.total_fu',
                'leads.type',
                'leads.leads_by'
            )
            ->leftJoin('industry', 'leads.industry_id', '=', 'industry.id')
            ->get();
        $data['industries'] = DB::table('industry')->get();

        return view('backend.leads.index', compact('data'));
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'notelp' => 'required|string|max:20',
                'industry_id' => 'required|integer|exists:industry,id',
                'alamat' => 'required',
                'leads_by' => 'required|string|max:255',
            ]);
            // dd($request->all());
            // Create leads
            DB::table('leads')->insert([
                'name' => $request->name,
                'email' => $request->email,
                'notelp' => $request->notelp,
                'industry_id' => $request->industry_id,
                'alamat' => $request->alamat,
                'leads_by' => $request->leads_by,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
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
        // Fetch leads data by id
        $leads = DB::table('leads')
            ->select(
                'leads.id',
                'leads.name',
                'leads.email',
                'leads.notelp',
                'leads.industry_id as industry_id',
                'industry.name as industry_name',
                'leads.alamat',
                'leads.type',
                'leads.leads_by'
            )
            ->leftJoin('industry', 'leads.industry_id', '=', 'industry.id')
            ->where('leads.id', $id)
            ->first();
        if (!$leads) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $leads
        ], 200);
    }
    public function update($id, Request $request)
    {

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'notelp' => 'required|string|max:20',
                'industry_id' => 'required|integer|exists:industry,id',
                'alamat' => 'required|string',
                'leads_by' => 'required|string|max:255',
            ]);

            // Update leads
            DB::table('leads')->where('id', $id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'notelp' => $request->notelp,
                'industry_id' => $request->industry_id,
                'alamat' => $request->alamat,
                'leads_by' => $request->leads_by,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
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
    public function destroy($id)
    {
        $leads = DB::table('leads')
            ->where('id', $id)
            ->first();
        try {
            if (!$leads) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }

            DB::table('leads')->where('id', $id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
