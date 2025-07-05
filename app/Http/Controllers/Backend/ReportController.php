<?php

namespace App\Http\Controllers\Backend;

use App\Exports\PenagihanExport;
use App\Exports\LeadsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class ReportController extends Controller
{
    public function exportpenagihan(Request $request)
    {
        $tanggal_awal = $request->date1;
        $tanggal_akhir = $request->date2;
        $product_id = $request->product_id;
        $namereport = 'pendapatan-' . $tanggal_awal . '-' . $tanggal_akhir . 'xlsx';
        return Excel::download(new PenagihanExport($tanggal_awal, $tanggal_akhir, $product_id), 'pendapatan-' . $tanggal_awal . '-' . $tanggal_akhir . '.xlsx');
    }

    public function exportleads(Request $request)
    {
        $type = $request->type;
        $current_date = date('Y-m-d');
        return Excel::download(new LeadsExport($type), 'leads-data' . $current_date . '.xlsx');
    }
}
