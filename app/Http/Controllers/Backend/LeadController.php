<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;



class LeadController extends Controller
{
    public function index()
    {
        // Fetch users from the database selecting all columns except the password
        $data['leads'] = DB::table('leads as ls')
            ->leftJoin(
                DB::raw('(
            SELECT f1.*
            FROM followup f1
            JOIN (
                SELECT lead_id, MAX(followup_ke) AS max_followup_ke
                FROM followup
                GROUP BY lead_id
            ) f2 ON f1.lead_id = f2.lead_id AND f1.followup_ke = f2.max_followup_ke
        ) as t1'),
                't1.lead_id',
                '=',
                'ls.id'
            )
            ->leftJoin('industry', 'industry.id', '=', 'ls.industry_id')
            ->select(
                'ls.id as lead_id',
                DB::raw('IFNULL(t1.followup_ke, 0) as total_fu'),
                'industry.name as industry_name',
                'ls.id',
                'ls.name',
                'ls.email',
                'ls.notelp',
                'ls.alamat',
                'ls.type',
                'ls.leads_by',
                'ls.decision'
            )
            ->get();

        $data['industries'] = DB::table('industry')->get();

        return view('backend.leads.index', compact('data'));
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'industry_id' => 'required|integer|exists:industry,id',
                'leads_by' => 'required|string|max:255',
            ]);
            // dd($request->all());
            // Create leads

            $cekleadsunique = DB::table('leads')
                ->where('name', $request->name)
                ->first();

            if ($cekleadsunique) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Lead with this name already exists.'
                ], 409);
            }

            DB::table('leads')->insert([
                'name' => $request->name,
                'email' => $request->email,
                'notelp' => $request->notelp,
                'industry_id' => $request->industry_id,
                'alamat' => $request->alamat,
                'leads_by' => $request->leads_by,
                'decision' => $request->decision,
            ]);
            $iduserlead = session::get('id');
            if ($request->decision === 'berlangganan') {
                // Insert into penawaran if decision is 'berlangganan'
                DB::table('customers')->insert([
                    'perusahaan' => $request->name,
                    'notelp' => $request->notelp,
                    'alamat' => $request->alamat,
                    'industry_type' => $request->industry_id,
                    'tanggal_mulai' => now(),
                    'status' => 'active',
                    'marketing_pic' => $iduserlead,
                ]);
            }
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
                'leads.leads_by',
                'leads.decision'
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
                'industry_id' => 'required|integer|exists:industry,id',
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
                'decision' => $request->decision,
            ]);
            $iduserlead = session::get('id');

            if ($request->decision === 'berlangganan') {
                // Insert into penawaran if decision is 'berlangganan'
                DB::table('customers')->insert([
                    'perusahaan' => $request->name,
                    'notelp' => $request->notelp,
                    'alamat' => $request->alamat,
                    'industry_type' => $request->industry_id,
                    'tanggal_mulai' => now(),
                    'status' => 'active',
                    'marketing_pic' => $iduserlead,
                ]);
            }

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
