<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenagihanController extends Controller
{
    public function index()
    {
        $data['penagihan'] = DB::table('penagihan')
            ->leftJoin('customers', 'customers.id', '=', 'penagihan.customer_id')
            ->leftJoin('product', 'product.id', '=', 'customers.product_id')
            ->select([
                'penagihan.id as pngid',
                'penagihan.nama_perusahaan',
                'penagihan.jumlah_tagihan',
                'penagihan.tanggal_tagihan',
                'penagihan.skema_pembayaran',
                'penagihan.penagihan_ke',
                'customers.id as cstid',
                'customers.skema_berlangganan',
                'customers.product_id as cstprdid',

                'product.name as product_name',
                'penagihan.invoice',
            ])->get();
        $data['customers'] = DB::table('customers')
            ->leftJoin('product', 'product.id', '=', 'customers.product_id')
            ->select('customers.id', 'customers.perusahaan', 'product.name as product_name')
            ->get();
        $data['products'] = DB::table('product')->select('id', 'name')->get();
        $data['product'] = DB::table('product')->get();
        return view('backend.penagihan.index', compact('data'));
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'nama_perusahaan' => 'required|string|max:255',
                'jumlah_tagihan' => 'required|numeric|min:0',
                'tanggal_tagihan' => 'required|date',
                'skema_pembayaran' => 'required|string|in:none,prabayar,pascabayar',
                'invoice' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);
            $penagihanKe = DB::table('penagihan')
                ->where('customer_id', $request->customer_id)
                ->count() + 1;
            $data = [
                'customer_id' => $request->customer_id,
                'nama_perusahaan' => $request->nama_perusahaan,
                'jumlah_tagihan' => $request->jumlah_tagihan,
                'tanggal_tagihan' => $request->tanggal_tagihan,
                'skema_pembayaran' => $request->skema_pembayaran,
                'penagihan_ke' => $penagihanKe,
            ];

            if ($request->hasFile('invoice')) {
                $file = $request->file('invoice');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('invoices', $filename, 'public');
                $data['invoice'] = $filename;
            }
            DB::table('penagihan')->insert($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Penagihan created successfully',
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

    public function detail($id)
    {
        $penagihan = DB::table('penagihan')
            ->leftJoin('customers', 'customers.id', '=', 'penagihan.customer_id')
            ->leftJoin('product', 'product.id', '=', 'customers.product_id')
            ->select([
                'penagihan.id as pngid',
                'penagihan.nama_perusahaan',
                'penagihan.jumlah_tagihan',
                'penagihan.tanggal_tagihan',
                'penagihan.skema_pembayaran',
                'penagihan.penagihan_ke',
                'customers.id as cstid',
                'customers.skema_berlangganan',
                'customers.product_id as cstprdid',
                'product.name as product_name',
                'penagihan.invoice',
            ])
            ->where('penagihan.id', $id)
            ->first();
        if (!$penagihan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Penagihan not found'
            ], 404);
        }
        return response()->json($penagihan);
    }

    // update function if the invoice was updated the data from storage will be deleted and if not change the data still
    public function update(Request $request, $id)
    {

        try {
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'nama_perusahaan' => 'required|string|max:255',
                'jumlah_tagihan' => 'required|numeric|min:0',
                'tanggal_tagihan' => 'required|date',
                'skema_pembayaran' => 'required|string|in:none,prabayar,pascabayar',
                'invoice' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            $data = [
                'customer_id' => $request->customer_id,
                'nama_perusahaan' => $request->nama_perusahaan,
                'jumlah_tagihan' => $request->jumlah_tagihan,
                'tanggal_tagihan' => $request->tanggal_tagihan,
                'skema_pembayaran' => $request->skema_pembayaran,
            ];
            if ($request->hasFile('invoice')) {
                // Delete old invoice file if exists
                $oldInvoice = DB::table('penagihan')->where('id', $id)->value('invoice');
                if ($oldInvoice) {
                    \Storage::disk('public')->delete('invoices/' . $oldInvoice);
                }
                $file = $request->file('invoice');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('invoices', $filename, 'public');
                $data['invoice'] = $filename;
            }

            DB::table('penagihan')
                ->where('id', $id)
                ->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Penagihan updated successfully',
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
