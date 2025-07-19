@extends('core.index')
@section('title','Customers')
@section('vendor-css')
@endsection
@section('page-css')
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<!-- Boxicons -->
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
<style>
  .table td, .table th {
    vertical-align: middle;
  }
  .table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transition: 0.2s ease;
  }
  .btn-icon {
    width: 36px;
    height: 36px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    padding: 0;
    border-radius: 50%;
  }
.card {
  border-radius: 1rem;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
}

</style>

@section('content')
<div class="card shadow-sm border-0 rounded-4 mb-3">
  <div class="card-body d-flex justify-content-between align-items-center">
    <h5 class="fw-bold mb-0">Customers</h5>
    @if(session()->get('role') == 'admin' || session()->get('role') == 'marketing')
      <button class="btn btn-primary btn-sm rounded-3 px-3" onclick="addCustomer()">
        <i class="bx bx-plus"></i> Add
      </button>
    @endif
  </div>
</div>

<div class="card shadow-sm border-0 rounded-4 p-3">
  <div class="table-responsive">
    <table id="customerTable" class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Contract No</th>
          <th>Perusahaan</th>
          <th>Produk</th>
          <th>Industry</th>
          <th>PIC Customer</th>
          <th>PIC Marketing</th>
          <th>Status</th>
          @if(session()->get('role') == 'admin' || session()->get('role') == 'marketing')
          <th class="text-center">Actions</th>
          @endif
        </tr>
      </thead>
      <tbody>
        @foreach($data['customers']->get() as $customer)
        <tr>
          <td>{{ $customer->contract_no }}</td>
          <td>{{ $customer->perusahaan }}</td>
          <td>{{ $customer->product_name }}</td>
          <td>{{ $customer->industry_name }}</td>
          <td>{{ $customer->customer_pic_name }}</td>
          <td>{{ $customer->marketing_pic_name }}</td>
          @if($customer->status == 'active')
            <td class="text-center"><span class="badge bg-success">Active</span></td>
            @else
            <td class="text-center"><span class="badge bg-danger">Inactive</span></td>
            @endif
          @if(session()->get('role') == 'admin' || session()->get('role') == 'marketing')

          <td class="text-center">
            <button type="button" class="btn btn-icon btn-info" title="Detail" onclick="detailCustomer('{{ $customer->id }}')">
              <i class="bx bx-search"></i>
            </button>
            <button type="button" class="btn btn-icon btn-primary" title="Edit" onclick="editCustomer('{{ $customer->id }}')">
              <i class="bx bx-edit"></i>
            </button>
            <button type="button" class="btn btn-icon btn-dark" title="Penagihan" onclick="addtagihan('{{ $customer->id }}')">
              <i class="bx bx-notepad"></i>

            </button>
          </td>
          @endif
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <form id="addCustomerForm" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Customer</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Contract No</label>
              <input type="text" class="form-control" name="contract_no" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Perusahaan</label>
              <input type="text" class="form-control" name="perusahaan" required>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">No. Telp</label>
              <input type="text" class="form-control" name="notelp" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Alamat</label>
              <input type="text" class="form-control" name="alamat" required>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Skema Berlangganan</label>
              <select class="form-select" name="skema_berlangganan" id="add_skema_berlangganan" required>
                <option value="" disabled selected>Pilih Skema</option>
                <option value="none">None</option>
                <option value="bulanan">Bulanan</option>
                <option value="triwulan">Triwulan</option>
                <option value="semester">Semester</option>
                <option value="tahunan">Tahunan</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Tanggal Mulai</label>
              <input type="date" class="form-control" name="tanggal_mulai" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Tanggal Akhir</label>
              <input type="date" class="form-control" name="tanggal_akhir" >
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select class="form-select" name="status" id="add_status" required>
                <option value="" disabled selected>Pilih Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">PIC Customer</label>
              <select class="form-select" name="customer_pic" required>
                <option selected disabled>Pilih PIC Customer</option>
                @foreach($data['userscustomers'] as $user)
                  <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">PIC Marketing</label>
              <select class="form-select" name="marketing_pic">
                <option selected disabled>Pilih PIC Marketing</option>
                @foreach($data['usersmarketing'] as $user)
                  <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Product</label>
              <select class="form-select" name="product_id" required>
                <option selected disabled>Pilih Product</option>
                @foreach($data['products'] as $product)
                  <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Industry</label>
              <select class="form-select" name="industry_type" required>
                <option selected disabled>Pilih Industry</option>
                @foreach($data['industries'] as $industry)
                  <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Dokumen (Max 3, pdf, doc, docx, jpg, jpeg, png, max 2MB each)</label>
              <input type="file" class="form-control" name="dokumen[]" id="add_dokumen" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
              <div id="add_dokumen_preview" class="mt-2"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <form id="editCustomerForm" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="_method" value="PUT">
      <input type="hidden" id="edit_id" name="id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Customer</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Contract No</label>
              <input type="text" class="form-control" id="edit_contract_no" name="contract_no" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Perusahaan</label>
              <input type="text" class="form-control" id="edit_perusahaan" name="perusahaan" required>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">No. Telp</label>
              <input type="text" class="form-control" id="edit_notelp" name="notelp" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Alamat</label>
              <input type="text" class="form-control" id="edit_alamat" name="alamat" required>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Skema Berlangganan</label>
              <select class="form-select" name="skema_berlangganan" id="edit_skema_berlangganan" required>
                <option value="" disabled>Pilih Skema</option>
                <option value="none">None</option>
                <option value="bulanan">Bulanan</option>
                <option value="triwulan">Triwulan</option>
                <option value="semester">Semester</option>
                <option value="tahunan">Tahunan</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Tanggal Mulai</label>
              <input type="date" class="form-control" id="edit_tanggal_mulai" name="tanggal_mulai" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Tanggal Akhir</label>
              <input type="date" class="form-control" id="edit_tanggal_akhir" name="tanggal_akhir" >
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select class="form-select" name="status" id="edit_status" required>
                <option value="" disabled>Pilih Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">PIC Customer</label>
              <select class="form-select" id="edit_customer_pic" name="customer_pic" required>
                <option selected disabled>Pilih PIC Customer</option>
                @foreach($data['userscustomers'] as $user)
                  <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">PIC Marketing</label>
              <select class="form-select" id="edit_marketing_pic" name="marketing_pic">
                <option selected disabled>Pilih PIC Marketing</option>
                @foreach($data['usersmarketing'] as $user)
                  <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Product</label>
              <select class="form-select" id="edit_product_id" name="product_id" required>
                <option selected disabled>Pilih Product</option>
                @foreach($data['products'] as $product)
                  <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Industry</label>
              <select class="form-select" id="edit_industry_type" name="industry_type" required>
                <option selected disabled>Pilih Industry</option>
                @foreach($data['industries'] as $industry)
                  <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Dokumen (Max 3, pdf, doc, docx, jpg, jpeg, png, max 2MB each)</label>
              <input type="file" class="form-control" name="dokumen[]" id="edit_dokumen" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
              <div id="edit_dokumen_preview" class="mt-2"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update changes</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailCustomerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="detailCustomerBody">
        <!-- Filled by AJAX -->
      </div>
    </div>
  </div>
</div>

<!-- Add Tagihan Modal -->
<div class="modal fade" id="addTagihanModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <form id="addTagihanForm" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="_method" value="POST">
      <input type="hidden" id="tagihan_customer_id" name="customer_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Tagihan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Nama Perusahaan</label>
              <input type="text" class="form-control" id="tagihan_nama_perusahaan" name="nama_perusahaan" value="" required readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label">Jumlah Tagihan</label>
              <input type="number" class="form-control" id="tagihan_jumlah" name="jumlah_tagihan" required>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Tanggal Tagihan</label>
              <input type="date" class="form-control" id="tagihan_tanggal" name="tanggal_tagihan" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Skema Pembayaran</label>
              <select class="form-select" id="tagihan_skema_pembayaran" name="skema_pembayaran" required>
                <option value="" disabled selected>Pilih Skema</option>
                <option value="none">None</option>
                <option value="prabayar">Prabayar</option>
                <option value="pascabayar">Pasca Bayar</option>
              </select>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Invoice (optional, pdf, jpg, jpeg, png)</label>
              <input type="file" class="form-control" name="invoice" accept=".pdf,.jpg,.jpeg,.png">
            </div>
            <div class="col-md-6">
              <label class="form-label">Keterangan (optional)</label>
              <textarea class="form-control" name="keterangan" rows="3"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </form>
  </div>
</div>

@endsection

@section('vendor-js')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$('#customerTable').DataTable({
  responsive: {
    details: {
      type: 'column',
      target: 'tr'
    }
  },
  columnDefs: [
    { responsivePriority: 1, targets: 0 },
    { responsivePriority: 2, targets: -1 } 
  ]
});

let dokumenBaruAdd = [];
let dokumenBaruEdit = [];
let dokumenLamaEdit = [];

// ADD: File input handler (bisa tambah satu-satu)
$('#add_dokumen').on('change', function() {
  dokumenBaruAdd = dokumenBaruAdd.concat(Array.from(this.files));
  if (dokumenBaruAdd.length > 3) dokumenBaruAdd = dokumenBaruAdd.slice(0, 3);
  renderDokumenPreview('#add_dokumen_preview', dokumenBaruAdd, []);
  this.value = "";
});

// EDIT: File input handler (bisa tambah satu-satu)
$('#edit_dokumen').on('change', function() {
  dokumenBaruEdit = dokumenBaruEdit.concat(Array.from(this.files));
  let total = dokumenLamaEdit.length + dokumenBaruEdit.length;
  if (total > 3) dokumenBaruEdit = dokumenBaruEdit.slice(0, 3 - dokumenLamaEdit.length);
  renderDokumenPreview('#edit_dokumen_preview', dokumenBaruEdit, dokumenLamaEdit);
  this.value = "";
});

// Render preview dokumen gabungan baru & lama
function renderDokumenPreview(target, baru, lama) {
  let html = '';
  (lama || []).forEach((path, idx) => {
    html += `<div class="mb-2" id="edit_doc_${idx}">
      <a href="/storage/${path}" target="_blank">Dokumen Lama ${idx+1}</a>
      <button type="button" class="btn btn-danger btn-sm ms-2" onclick="deleteDokumenLama(${idx})">
        <i class="bx bx-trash"></i>
      </button>
    </div>`;
  });
  (baru || []).forEach((file, idx) => {
    html += `<div class="mb-1"><b>Dokumen Baru ${idx+1}:</b> ${file.name}
      <button type="button" class="btn btn-danger btn-sm ms-2" onclick="deleteDokumenBaru('${target}', ${idx})">
        <i class="bx bx-trash"></i>
      </button>
    </div>`;
  });
  $(target).html(html);
}

// Hapus dokumen baru dari preview (add/edit)
function deleteDokumenBaru(target, idx) {
  if(target == '#add_dokumen_preview') dokumenBaruAdd.splice(idx, 1);
  if(target == '#edit_dokumen_preview') dokumenBaruEdit.splice(idx, 1);
  renderDokumenPreview(target, target == '#add_dokumen_preview' ? dokumenBaruAdd : dokumenBaruEdit, dokumenLamaEdit);
}

// Hapus dokumen lama dari preview (edit only)
function deleteDokumenLama(idx) {
  let customerId = $('#edit_id').val();
  $.ajax({
    url: `/customers/${customerId}/dokumen/${idx}`,
    type: 'DELETE',
    data: { _token: '{{ csrf_token() }}' },
    success: function() {
      dokumenLamaEdit.splice(idx, 1);
      renderDokumenPreview('#edit_dokumen_preview', dokumenBaruEdit, dokumenLamaEdit);
    }
  });
}

function addCustomer() {
  $('#addCustomerForm')[0].reset();
  dokumenBaruAdd = [];
  renderDokumenPreview('#add_dokumen_preview', dokumenBaruAdd, []);
  $('#addCustomerModal').modal('show');
}

// SUBMIT ADD
$('#addCustomerForm').on('submit', function(e){
  e.preventDefault();
  let form = new FormData(this);
  dokumenBaruAdd.forEach(f => form.append('dokumen[]', f));
  $.ajax({
    url: '{{ route("customers.create") }}',
    method: 'POST',
    data: form,
    processData: false,
    contentType: false,
    success: function(res){
      $('#addCustomerModal').modal('hide');
      Swal.fire({
        title: "Success",
        text: "Customer berhasil ditambahkan!",
        icon: "success",
        button: "OK",
        timer: 750,
      });
      setTimeout(()=>location.reload(), 800);
    }
  });
});

function editCustomer(id) {
  dokumenBaruEdit = [];
  $.ajax({
    url: `/customers/${id}/detail`,
    method: 'GET',
    success: function(res){
      let c = res.data;
      console.log(c);
      
      $('#edit_id').val(c.id);
      $('#edit_contract_no').val(c.contract_no);
      $('#edit_perusahaan').val(c.perusahaan);
      $('#edit_notelp').val(c.notelp);
      $('#edit_alamat').val(c.alamat);
      $('#edit_skema_berlangganan').val(c.skema_berlangganan).trigger('change');
      $('#edit_tanggal_mulai').val(c.tanggal_mulai);
      $('#edit_tanggal_akhir').val(c.tanggal_akhir);
      $('#edit_status').val(c.status).trigger('change');
      $('#edit_customer_pic').val(c.customer_pic_id).trigger('change');
      $('#edit_marketing_pic').val(c.marketing_pic_id).trigger('change');
      $('#edit_product_id').val(c.product_id).trigger('change');
      $('#edit_industry_type').val(c.industry_id).trigger('change');
      dokumenLamaEdit = c.dokumen ? JSON.parse(c.dokumen) : [];
      renderDokumenPreview('#edit_dokumen_preview', dokumenBaruEdit, dokumenLamaEdit);
      $('#editCustomerModal').modal('show');
    }
  });
}

// SUBMIT EDIT
$('#editCustomerForm').on('submit', function(e){
  e.preventDefault();
  let id = $('#edit_id').val();
  let form = new FormData(this);
  dokumenBaruEdit.forEach(f => form.append('dokumen[]', f));
  $.ajax({
    url: `/customers/${id}`,
    method: 'POST',
    data: form,
    processData: false,
    contentType: false,
    success: function(res){
      $('#editCustomerModal').modal('hide');
      Swal.fire({
        title: "Success",
        text: "Customer berhasil diperbarui!",
        icon: "success",
        button: "OK",
        timer: 750,
      });
      setTimeout(()=>location.reload(), 800);
    }
  });
});

function detailCustomer(id) {
  $.ajax({
    url: `/customers/${id}/detail`,
    method: 'GET',
    success: function(res){
      let c = res.data;
      let docs = [];
      if(c.dokumen) try { docs = JSON.parse(c.dokumen); } catch(e){}
      let doclist = docs.map((path, idx) => `<a href="/storage/${path}" target="_blank" class="btn btn-link">Dokumen ${idx+1}</a>`).join('<br>');
      let html = `
        <dl class="row">
          <dt class="col-sm-4">Contract No</dt><dd class="col-sm-8">${c.contract_no}</dd>
          <dt class="col-sm-4">Perusahaan</dt><dd class="col-sm-8">${c.perusahaan}</dd>
          <dt class="col-sm-4">Alamat</dt><dd class="col-sm-8">${c.alamat}</dd>
          <dt class="col-sm-4">PIC Customer</dt><dd class="col-sm-8">${c.customer_pic_name}</dd>
          <dt class="col-sm-4">PIC Marketing</dt><dd class="col-sm-8">${c.marketing_pic_name}</dd>
          <dt class="col-sm-4">Produk</dt><dd class="col-sm-8">${c.product_name}</dd>
          <dt class="col-sm-4">Industry</dt><dd class="col-sm-8">${c.industry_name}</dd>
          <dt class="col-sm-4">Skema</dt><dd class="col-sm-8">${c.skema_berlangganan}</dd>
          <dt class="col-sm-4">Tanggal Mulai</dt><dd class="col-sm-8">${c.tanggal_mulai}</dd>
          <dt class="col-sm-4">Tanggal Akhir</dt><dd class="col-sm-8">${c.tanggal_akhir}</dd>
          <dt class="col-sm-4">Status</dt><dd class="col-sm-8">${c.status}</dd>
          <dt class="col-sm-4">Dokumen</dt><dd class="col-sm-8">${doclist}</dd>
        </dl>
      `;
      $('#detailCustomerBody').html(html);
      $('#detailCustomerModal').modal('show');
    }
  });

 
}
 function addtagihan(id) {
    $('#tagihan_customer_id').val(id);
    $.ajax({
      url: `/customers/${id}/detail`,
      method: 'GET',
      success: function(res){
        $('#tagihan_nama_perusahaan').val(res.data.perusahaan);
      }
    });
    $('#addTagihanModal').modal('show');
  }
  $('#addTagihanForm').on('submit', function(e){
    e.preventDefault();
    let form = new FormData(this);
    $.ajax({
      url: '{{ route("penagihan.create") }}',
      method: 'POST',
      data: form,
      processData: false,
      contentType: false,
      success: function(res){
        $('#addTagihanModal').modal('hide');
        Swal.fire({
          title: "Success",
          text: "Tagihan berhasil ditambahkan!",
          icon: "success",
          button: "OK",
          timer: 750,
        });
        setTimeout(()=>location.reload(), 800);
      }
    });
  });
</script>
@endsection
@section('page-js')
@endsection