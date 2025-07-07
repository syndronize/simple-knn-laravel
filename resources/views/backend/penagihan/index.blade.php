@extends('core.index')
@section('title','Penagihan') <!-- delete if not needed -->
@section('vendor-css') @endsection <!-- delete if not needed -->
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
{{-- @endsection --}}
@section('content')

  
<!-- CARD 1: Header with Title and Add Button -->
<div class="card shadow-sm border-0 rounded-4 mb-3">
  <div class="card-body py-3 px-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
      <!-- Left: Title -->
      <h5 class="fw-bold mb-0 text-secondary">Penagihan</h5>
      <!-- Right: Filters + Button (in one flex container) -->
    @if(session()->get('role') == 'admin' || session()->get('role') == 'marketing')
      
      <div class="d-flex align-items-center gap-2 flex-nowrap">
        <select class="form-select" style="min-width:180px;max-width:220px;" id="productSelect">
          <option value="">Pilih Product</option>
          @foreach($data['products'] as $product)
            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
          @endforeach
        </select>
        <input type="text" id="daterange" class="form-control" style="min-width:220px;" placeholder="Tanggal..." />
        <button class="btn btn-primary  rounded-3 px-3 d-flex align-items-center"  onclick="cetakreport()">
          <i class="bx bx-printer"></i>
        </button>
      </div>
      @endif
    </div>
  </div>
</div>

<!-- CARD 2: Table Content -->
<div class="card shadow-sm border-0 rounded-4 p-3">
  <div class="table-responsive">
    <table id="projectTable" class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Penagihan Ke-</th>
          <th>Perusahaan</th>
          <th>Product</th>
          <th>Jumlah Tagihan</th>
          <th>Tanggal Tagihan</th>
          <th>Skema Pembayaran</th>
          <th>Skema Pembayaran</th>
        @if(session()->get('role') == 'admin' || session()->get('role') == 'marketing')
          
          <th class="text-center">Actions</th>
          @endif
        </tr>
      </thead>
      <tbody>
          @foreach($data['penagihan'] as $key => $penagihan)
        <tr>
            <td>{{ $penagihan->penagihan_ke }}</td>
            <td>{{ $penagihan->nama_perusahaan }}</td>
            <td>{{ $penagihan->product_name }}</td>
            <td>Rp. {{ number_format($penagihan->jumlah_tagihan, 0, ',', '.') }} ,-</td>
            <td>{{ \Carbon\Carbon::parse($penagihan->tanggal_tagihan)->format('d-m-Y') }}</td>
            <td>{{ $penagihan->skema_pembayaran }}</td>
            <td>
                @if($penagihan->skema_pembayaran == 'prabayar')
                    <span class="badge bg-success">Prabayar</span>
                @elseif($penagihan->skema_pembayaran == 'pascabayar')
                    <span class="badge bg-warning">Pasca Bayar</span>
                @else
                    <span class="badge bg-secondary">None</span>
                @endif
            </td>
    @if(session()->get('role') == 'admin' || session()->get('role') == 'marketing')

            <td class="text-center">
                <div >
                    <button class="btn btn-icon btn-primary" onclick="editpenagihan({{ $penagihan->pngid }})" title="Edit">
                        <i class='bx bx-edit'></i>
                    </button>
                    <button class="btn btn-icon btn-info" onclick="detailpengaihan({{$penagihan->pngid}})" title="Detail">
                        <i class='bx bx-show'></i>
                    </button>
                </div>
            </td>
            @endif
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<!-- Modal for Edit Product -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProductModalLabel">Edit Penagihan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <form id="editProductForm" >
      <input type="hidden" name="_method" value="PUT">
      @csrf

        <div class="modal-body">
          <!-- Form fields for editing product -->
          <input type="hidden" name="customer_id"id="customerId">
            <input type="hidden" name="penagihan_id" id="penagihanId">
          <div class="mb-3">
            <label for="productName" class="form-label" >Nama Perusahaan</label>
            <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" required readonly>
          </div>
          <div class="mb-3">
            <label for="productPrice" class="form-label">Jumlah Tagihan</label>
            <input type="number" class="form-control" id="productPrice" name="jumlah_tagihan" required>
          </div>
            <div class="mb-3">
                <label for="productDate" class="form-label">Tanggal Tagihan</label>
                <input type="date" class="form-control" id="productDate" name="tanggal_tagihan" required>
            </div>
            <div class="mb-3">
                <label for="productPaymentScheme" class="form-label">Skema Pembayaran</label>
                <select class="form-select" id="productPaymentScheme" name="skema_pembayaran" required>
                    <option value="prabayar">Prabayar</option>
                    <option value="pascabayar">Pasca Bayar</option>
                    <option value="none">None</option>
                </select>
            </div>
            {{-- upload file --}}
            <div class="mb-3">
                <label for="productInvoice" class="form-label">Upload Invoice</label>
                <input type="file" class="form-control" id="productInvoice" name="invoice" accept=".pdf,.jpg,.jpeg,.png">
                <small class="form-text text-muted">Format: PDF, JPG, JPEG, PNG. Max size: 5MB.</small>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- modal detail have a preview invoices --}}
<div class="modal fade" id="detailProductModal" tabindex="-1" aria-labelledby="detailProductModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailProductModalLabel">Detail Penagihan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="detailCompanyName" class="form-label">Nama Perusahaan</label>
            <input type="text" class="form-control" id="detailCompanyName" readonly>
          </div>
            <div class="col-md-6 mb-3">
                <label for="detailProductName" class="form-label">Nama Product</label>
                <input type="text" class="form-control" id="detailProductName" readonly>
            </div>
          <div class="col-md-6 mb-3">
            <label for="detailInvoiceAmount" class="form-label">Jumlah Tagihan</label>
            <input type="text" class="form-control" id="detailInvoiceAmount" readonly>
          </div>
            <div class="col-md-6 mb-3">
                <label for="detailInvoiceDate" class="form-label">Tanggal Tagihan</label>
                <input type="text" class="form-control" id="detailInvoiceDate" readonly>
            </div>
          <div class="col-md-6 mb-3">
            <label for="detailPaymentScheme" class="form-label">Skema Pembayaran</label>
            <input type="text" class="form-control" id="detailPaymentScheme" readonly>
          </div>
            
            <div class="col-md-12 mb-3">
                <label for="detailInvoicePreview" class="form-label">Preview Invoice</label>
                <div id="detailInvoicePreview" class="border p-3">
                    <!-- Preview content will be loaded here -->
                </div>
            </div>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
    </div>
</div>







@endsection
@section('vendor-js')

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!-- Initialize DataTable -->
<script>
  $('#projectTable').DataTable({
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
$(function() {
    $('#daterange').daterangepicker({
        opens: 'right', // or 'left'
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
});
$('#daterange').daterangepicker({
    opens: 'right',
    locale: { format: 'YYYY-MM-DD' },
    ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')]
    }
});

function editpenagihan(id) {
    console.log('id', id);
    
    $.ajax({
        url: '/penagihan/' + id + '/detail',
        method: 'GET',
        success: function(data) {
            console.log(data);
            
            $('#customerId').val(data.cstid);
            $('#penagihanId').val(data.pngid);
            $('#nama_perusahaan').val(data.nama_perusahaan);
            $('#productPrice').val(data.jumlah_tagihan);
            $('#productDate').val(new Date(data.tanggal_tagihan).toISOString().split('T')[0]);
            $('#productPaymentScheme').val(data.skema_pembayaran);
            // If you have an invoice file, you can handle it here
            

            // Add more fields as needed
            $('#editProductModal').modal('show');
        },
        error: function() {
            alert('Error fetching product data.');
        }
    });
}
function detailpengaihan(id) {
    $.ajax({
        url: '/penagihan/' + id + '/detail',
        method: 'GET',
        success: function(data) {
            console.log(data);
            
            $('#detailCompanyName').val(data.nama_perusahaan);
            $('#detailProductName').val(data.product_name);
            $('#detailInvoiceAmount').val('Rp. ' + data.jumlah_tagihan.toLocaleString('id-ID') + ' ,-');
            $('#detailInvoiceDate').val(new Date(data.tanggal_tagihan).toLocaleDateString('id-ID'));
            $('#detailPaymentScheme').val(data.skema_pembayaran);
            $('#detailPaymentStatus').val(data.status_pembayaran);
            let invoiceurl = '{{ url('/storage/invoices/') }}/' + data.invoice;
            $('#detailInvoicePreview').html('<iframe src="' + invoiceurl + '" width="100%" height="400px"></iframe>');

            $('#detailProductModal').modal('show');
        },
        error: function() {
            alert('Error fetching penagihan details.');
        }
    });
}

// Handle form submission for editing product
$('#editProductForm').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    var id = $('#penagihanId').val();

    $.ajax({
        url: '/penagihan/' + id,
        method: 'POST', // Use POST with _method=PUT for Laravel
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            $('#editProductModal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Product updated successfully!'
            });
            setTimeout(function() {
                Swal.close();
                location.reload(); // Reload the page to see changes
            }, 800);
        },
        error: function() {
            alert('Error updating product.');
        }
    });
});

function cetakreport() {
    var productId = $('#productSelect').val();
    var dateRange = $('#daterange').val();
    const [date1, date2] = dateRange.split(' - ');

    $.ajax({
        url: '{{ route("export.penagihan") }}',
        method: 'GET',
        data: {
            product_id: productId,
            date1: date1,
            date2: date2
        },
        xhrFields: {
            responseType: 'blob' // supaya response bisa dijadikan file
        },
        success: function(blob, status, xhr) {
            // Get filename from Content-Disposition header
            var filename = "";
            var disposition = xhr.getResponseHeader('Content-Disposition');
            if (disposition && disposition.indexOf('attachment') !== -1) {
                var matches = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(disposition);
                if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
            }
            if (!filename) filename = "report.xlsx";

            var link = document.createElement('a');
            var url = window.URL.createObjectURL(blob);
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            setTimeout(function() {
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);  
            }, 100);
        },
        error: function(xhr) {
            alert('Gagal mendownload report!');
        }
    });
}

</script>

@endsection <!-- delete if not needed -->
@section('page-js') @endsection <!-- delete if not needed -->