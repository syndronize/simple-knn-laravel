<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $data['totcustomer'] =
            DB::table('customers')
            ->select(DB::raw('COUNT(*) as total'))
            ->first()
            ->total;
        $data['totfollowup'] =
            DB::table('followup')
            ->select(DB::raw('COUNT(*) as total'))
            ->first()
            ->total;
        $data['summarytype'] =
            DB::table(DB::raw(
                "(
                    SELECT 'cold leads' AS type
                    UNION ALL
                    SELECT 'warm leads'
                    UNION ALL
                    SELECT 'hot leads'
                ) AS t"
            ))
            ->leftJoin('leads as l', 'l.type', '=', 't.type')
            ->select('t.type', DB::raw('COUNT(l.type) AS totaltype'))
            ->groupBy('t.type')
            ->get();
        $data['penagihan'] = DB::table('penagihan')
            ->leftJoin('customers', 'customers.id', '=', 'penagihan.customer_id')
            ->select(
                DB::raw("CASE 
            WHEN skema_berlangganan = 'bulanan' THEN 30
            WHEN skema_berlangganan = 'triwulan' THEN 90
            WHEN skema_berlangganan = 'semester' THEN 180
            WHEN skema_berlangganan = 'tahunan' THEN 365
            ELSE 0 
        END AS skemaberlangganan"),
                'penagihan.nama_perusahaan',
                'penagihan.tanggal_tagihan',
                'penagihan.id'
            )
            ->orderBy('penagihan.tanggal_tagihan', 'desc')
            ->get();
        // dd($data['penagihan']);
        $maxIdPerLead = DB::table('followup')
            ->select(DB::raw('MAX(id) as max_id'), 'lead_id')
            ->groupBy('lead_id');

        $data['followup'] = DB::table('followup as f')
            ->joinSub($maxIdPerLead, 'mx', function ($join) {
                $join->on('f.id', '=', 'mx.max_id');
            })
            ->leftJoin('leads', 'leads.id', '=', 'f.lead_id')
            ->select(
                'f.id',
                'f.lead_id',
                'leads.name',
                'f.followup_ke',
                'f.tanggal_followup'
            )
            ->get();
        return view('dashboard.index', $data);
    }
}
