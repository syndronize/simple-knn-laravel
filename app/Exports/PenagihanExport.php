<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PenagihanExport implements FromView
{
    protected $tanggal_awal;
    protected $tanggal_akhir;
    protected $product_id;

    public function __construct($tanggal_awal, $tanggal_akhir, $product_id)
    {
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->product_id = $product_id;
    }

    public function view(): View
    {
        $data = \DB::table('penagihan')
            ->leftJoin('customers', 'customers.id', '=', 'penagihan.customer_id')
            ->leftJoin('product', 'product.id', '=', 'customers.product_id')
            ->selectRaw('customers.perusahaan, SUM(penagihan.jumlah_tagihan) AS tagihan, product.name AS productname')
            ->whereBetween('tanggal_tagihan', [$this->tanggal_awal, $this->tanggal_akhir])
            ->when($this->product_id, function ($query) {
                $query->where('product.id', $this->product_id);
            })
            ->groupBy('customers.id', 'customers.perusahaan', 'product.name')
            ->get();

        $total = $data->sum('tagihan');
        $header = $this->tanggal_awal . ' / ' . $this->tanggal_akhir;

        return view('exports.penagihan.index', [
            'data' => $data,
            'total' => $total,
            'periode' => [$this->tanggal_awal, $this->tanggal_akhir],
            'header' => $header,
        ]);
    }
}
