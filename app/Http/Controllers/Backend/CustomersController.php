<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomersController extends Controller
{
    public function index()
    {
        $data['customers'] = DB::table('customers')
            ->leftJoin('product', 'product.id', '=', 'customers.product_id')
            ->leftJoin('industry', 'industry.id', '=', 'customers.industry_type')
            ->leftJoin('users as customer_user', 'customer_user.id', '=', 'customers.customer_pic')
            ->leftJoin('users as marketing_user', 'marketing_user.id', '=', 'customers.marketing_pic')
            ->select([
                'customers.contract_no',
                'customers.id',
                'customers.perusahaan',
                'customer_user.name as customer_pic_name',
                'product.name as product_name',
                'customers.notelp',
                'customers.alamat',
                'industry.name as industry_name',
                'customers.skema_berlangganan',
                'customers.tanggal_mulai',
                'customers.tanggal_akhir',
                'customers.status',
                'marketing_user.name as marketing_pic_name',
                'customers.dokumen'
            ]);

        $data['products'] = DB::table('product')->select('id', 'name')->get();
        $data['industries'] = DB::table('industry')->select('id', 'name')->get();
        $data['users'] = DB::table('users')->select('id', 'name')->get();

        return view('backend.customers.index', compact('data'));
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'contract_no' => 'required|string|max:50',
                'perusahaan' => 'required|string|max:255',
                'notelp' => 'required|string|max:20',
                'alamat' => 'required|string|max:255',
                'skema_berlangganan' => 'required|string|max:50',
                'tanggal_mulai' => 'required|date',
                'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
                'status' => 'required|string|max:20',
                'customer_pic' => 'required|exists:users,id',
                'marketing_pic' => 'nullable|exists:users,id',
                'product_id' => 'required|exists:product,id',
                'industry_type' => 'required|exists:industry,id',
                'dokumen.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
            ]);

            // Handle file uploads
            $dokumenPaths = [];
            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $file) {
                    $dokumenPaths[] = $file->store('dokumen', 'public');
                }
            }
            $dokumenPaths = array_slice($dokumenPaths, 0, 3);

            // HANYA SEKALI INSERT!
            DB::table('customers')->insert([
                'contract_no' => $request->contract_no,
                'perusahaan' => $request->perusahaan,
                'notelp' => $request->notelp,
                'alamat' => $request->alamat,
                'skema_berlangganan' => $request->skema_berlangganan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_akhir' => $request->tanggal_akhir,
                'status' => $request->status,
                'customer_pic' => $request->customer_pic,
                'marketing_pic' => $request->marketing_pic,
                'product_id' => $request->product_id,
                'industry_type' => $request->industry_type,
                'dokumen' => json_encode($dokumenPaths),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Customer created successfully',
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
        // Fetch customer data by id
        $customer = DB::table('customers')
            ->leftJoin('product', 'product.id', '=', 'customers.product_id')
            ->leftJoin('industry', 'industry.id', '=', 'customers.industry_type')
            ->leftJoin('users as customer_user', 'customer_user.id', '=', 'customers.customer_pic')
            ->leftJoin('users as marketing_user', 'marketing_user.id', '=', 'customers.marketing_pic')
            ->select([
                'customers.contract_no',
                'customers.id',
                'customers.perusahaan',
                'customer_user.name as customer_pic_name',
                'customer_user.id as customer_pic_id',
                'product.name as product_name',
                'product.id as product_id',
                'customers.notelp',
                'customers.alamat',
                'industry.name as industry_name',
                'industry.id as industry_id',
                'customers.skema_berlangganan',
                'customers.tanggal_mulai',
                'customers.tanggal_akhir',
                'customers.status',
                'marketing_user.id as marketing_pic_id',
                'marketing_user.name as marketing_pic_name',
                'customers.dokumen'
            ])
            ->where('customers.id', $id)
            ->first();

        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $customer
        ], 200);
    }

    public function update($id, Request $request)
    {
        try {
            $request->validate([
                'contract_no' => 'required|string|max:50',
                'perusahaan' => 'required|string|max:255',
                'notelp' => 'required|string|max:20',
                'alamat' => 'required|string|max:255',
                'skema_berlangganan' => 'required|string|max:50',
                'tanggal_mulai' => 'required|date',
                'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
                'status' => 'required|string|max:20',
                'customer_pic' => 'required|exists:users,id',
                'marketing_pic' => 'nullable|exists:users,id',
                'product_id' => 'required|exists:product,id',
                'industry_type' => 'required|exists:industry,id',
                // dokumen is optional and can be an array of files
                'dokumen.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
            ]);

            // Ambil dokumen lama
            $customer = DB::table('customers')->where('id', $id)->first();
            $oldDokumen = $customer->dokumen ? json_decode($customer->dokumen, true) : [];

            // Upload dokumen baru
            $newDokumen = [];
            if ($request->hasFile('dokumen')) {
                foreach ($request->file('dokumen') as $file) {
                    $newDokumen[] = $file->store('dokumen', 'public');
                }
            }

            // Gabungkan lama & baru, maksimal 3 file
            $dokumenPaths = array_merge($oldDokumen, $newDokumen);
            $dokumenPaths = array_slice($dokumenPaths, 0, 3);

            // Update customer
            DB::table('customers')->where('id', $id)->update([
                'contract_no' => $request->contract_no,
                'perusahaan' => $request->perusahaan,
                'notelp' => $request->notelp,
                'alamat' => $request->alamat,
                'skema_berlangganan' => $request->skema_berlangganan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_akhir' => $request->tanggal_akhir,
                'status' => $request->status,
                'customer_pic' => $request->customer_pic,
                'marketing_pic' => $request->marketing_pic,
                'product_id' => $request->product_id,
                'industry_type' => $request->industry_type,
                // Store dokumen paths as JSON
                'dokumen' => json_encode($dokumenPaths),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Customer updated successfully',
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

    public function deleteDokumen($id, $index)
    {
        $customer = DB::table('customers')->where('id', $id)->first();
        if (!$customer) {
            return response()->json(['status' => 'error', 'message' => 'Customer not found'], 404);
        }
        $dokumen = json_decode($customer->dokumen, true) ?? [];
        if (!isset($dokumen[$index])) {
            return response()->json(['status' => 'error', 'message' => 'Dokumen not found'], 404);
        }
        // Delete file from storage
        \Storage::disk('public')->delete($dokumen[$index]);
        // Remove from array
        array_splice($dokumen, $index, 1);
        DB::table('customers')->where('id', $id)->update(['dokumen' => json_encode($dokumen)]);
        return response()->json(['status' => 'success']);
    }
}
