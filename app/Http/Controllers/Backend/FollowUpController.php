<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class FollowUpController extends Controller
{
    public function index()
    {
        // Fetch follow-ups from the database
        $data['followup'] = DB::table('followup')
            ->leftJoin('leads', 'leads.id', '=', 'followup.lead_id')
            ->select('leads.name', 'followup.id', 'followup.lead_id', 'followup.tanggal_followup', 'followup.followup_ke')
            ->get();
        // dd($data['followup']);
        $data['maxid'] = DB::table('followup')
            ->select(DB::raw('MAX(id) as idmax'))
            ->groupBy('lead_id');
        $data['leads'] = DB::table('leads')->select('id', 'name')->get();

        return view('backend.followup.index', compact('data'));
    }
    public function create(Request $request)
    {
        try {
            $request->validate([
                'lead_id' => 'required|integer|exists:leads,id',
                'tanggal_followup' => 'required|date',
                'status' => 'required|in:open,progress,done',
                'dibalas' => 'required|boolean',
                'respon_positif' => 'required|boolean',
                'pitching' => 'required|boolean',
                'penawaran' => 'required|boolean',
            ]);

            // Create follow-up
            $totalfollowup = DB::table('followup')
                ->where('lead_id', $request->lead_id)
                ->count() + 1;

            DB::table('followup')->insert([
                'lead_id' => $request->lead_id,
                'tanggal_followup' => $request->tanggal_followup,
                'status' => $request->status,
                'dibalas' => $request->dibalas,
                'respon_positif' => $request->respon_positif,
                'pitching' => $request->pitching,
                'penawaran' => $request->penawaran,
                'followup_ke' => $totalfollowup,
            ]);
            sleep(5);
            if (!$request->has('lead_id')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Lead ID is required'
                ], 400);
            }
            $this->knn($request->lead_id);
            return response()->json([
                'status' => 'success',
                'message' => 'Follow-up created successfully',
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
        // Fetch follow-up by ID
        $followup = DB::table('followup')
            ->leftJoin('leads', 'leads.id', '=', 'followup.lead_id')
            ->select('followup.id', 'leads.name', 'followup.lead_id', 'followup.tanggal_followup', 'followup.followup_ke', 'followup.status', 'followup.penawaran', 'followup.pitching', 'followup.respon_positif', 'followup.dibalas')
            ->where('followup.id', $id)
            ->first();

        if (!$followup) {
            return response()->json([
                'status' => 'error',
                'message' => 'Follow-up not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $followup
        ], 200);
    }
    public function update($id, Request $request)
    {
        try {
            $request->validate([
                'tanggal_followup' => 'required|date',
                'status' => 'required|in:open,progress,done',
                'dibalas' => 'required|boolean',
                'respon_positif' => 'required|boolean',
                'pitching' => 'required|boolean',
                'penawaran' => 'required|boolean',
            ]);

            // Update follow-up
            $followup = DB::table('followup')
                ->where('id', $id)
                ->first();

            if (!$followup) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Follow-up not found'
                ], 404);
            }

            DB::table('followup')
                ->where('id', $id)
                ->update([
                    'tanggal_followup' => $request->tanggal_followup,
                    'status' => $request->status,
                    'dibalas' => $request->dibalas,
                    'respon_positif' => $request->respon_positif,
                    'pitching' => $request->pitching,
                    'penawaran' => $request->penawaran,
                ]);
            $this->knn($request->lead_id);


            return response()->json([
                'status' => 'success',
                'message' => 'Follow-up updated successfully',
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

    public function knn($id)
    {
        // Ambil data followup terakhir berdasarkan lead_id
        $input = $input = DB::table('followup')
            ->where('lead_id', $id)
            ->orderByDesc('id')
            ->first();
        // Cek jika data tidak ditemukan
        if (!$input) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        // Dataset untuk KNN
        $dataset = [
            ['dibalas' => 0, 'respon_positif' => 0, 'pitching' => 0, 'penawaran' => 0, 'kategori' => 0],
            ['dibalas' => 1, 'respon_positif' => 0, 'pitching' => 0, 'penawaran' => 0, 'kategori' => 0],
            ['dibalas' => 1, 'respon_positif' => 1, 'pitching' => 0, 'penawaran' => 0, 'kategori' => 1],
            ['dibalas' => 1, 'respon_positif' => 1, 'pitching' => 1, 'penawaran' => 0, 'kategori' => 2],
            ['dibalas' => 1, 'respon_positif' => 1, 'pitching' => 1, 'penawaran' => 1, 'kategori' => 2],
        ];

        $k = 3;
        $distances = [];

        // Hitung jarak antara input dengan setiap data pada dataset
        foreach ($dataset as $data) {
            $distance = sqrt(
                pow($data['dibalas'] - $input->dibalas, 2) +
                    pow($data['respon_positif'] - $input->respon_positif, 2) +
                    pow($data['pitching'] - $input->pitching, 2) +
                    pow($data['penawaran'] - $input->penawaran, 2)
            );

            $distances[] = [
                'kategori' => $data['kategori'],
                'distance' => $distance
            ];
        }

        // Urutkan berdasarkan jarak terdekat
        usort($distances, function ($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

        // Ambil k data terdekat
        $kNearest = array_slice($distances, 0, $k);

        // Hitung jumlah kategori terdekat
        $kategoriCount = [];
        foreach ($kNearest as $item) {
            $kategori = $item['kategori'];
            if (!isset($kategoriCount[$kategori])) {
                $kategoriCount[$kategori] = 0;
            }
            $kategoriCount[$kategori]++;
        }

        arsort($kategoriCount);
        $predictedKategori = (int) array_key_first($kategoriCount);

        // Konversi hasil prediksi ke label
        if ($predictedKategori === 0) {
            $kategoriLabel = 'cold leads';
        } else if ($predictedKategori === 1) {
            $kategoriLabel = 'warm leads';
        } else if ($predictedKategori === 2) {
            $kategoriLabel = 'hot leads';
        } else {
            $kategoriLabel = 'none';
        }

        // Update data leads
        DB::table('leads')
            ->where('id', $id)
            ->update(['type' => $kategoriLabel]);

        return response()->json([
            'status' => 'success',
            'message' => 'KNN classification successful',
            'data' => $kategoriLabel
        ], 200);
    }

    public function detail($id)
    {
        $detail =  DB::table('followup')
            ->leftJoin('leads', 'leads.id', '=', 'followup.lead_id')
            ->select('followup.tanggal_followup', 'followup.lead_id', 'leads.name', 'followup.followup_ke')
            ->where('followup.lead_id', $id)
            ->orderBy('followup.followup_ke')
            ->get();
        if (!$detail) {
            return response()->json([
                'status' => 'error',
                'message' => 'Follow-up not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $detail
        ], 200);
    }
}
