@extends('core.index')
@section('title','Dashboard') <!-- delete if not needed -->
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
    background-color: #f9f9ff;
  }
  .btn-icon {
    width: 32px;
    height: 32px;
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
canvas {
    max-height: 220px;
  }
  .card h6 {
    color: #6c757d;
    font-size: 1rem;
  }
  .card h1, .card h2, .card h6 {
  margin-bottom: 0.5rem;
}
.display-3 {
  font-size: 3rem;
}


</style>
@section('content')

 
<!-- CARD 1: Title and Add Button -->
<div class="row g-4 mb-3">
  <!-- Chart Leads -->
  <div class="col-md-6">
    <div class="card shadow-sm border-0 rounded-4 p-4 h-100">
      <h6 class="fw-semibold mb-3 text-center">Chart Leads</h6>
      <canvas id="leadsChart" height="200"></canvas>
    </div>
  </div>

  <!-- Total Customer & Follow Up -->
  <div class="col-md-6">
  <div class="card shadow-sm border-0 rounded-4 p-4 h-100 d-flex justify-content-center align-items-center">
    <div class="text-center w-100">
      <div class="row">
        <div class="col-6 border-end">
          <h6 class="fw-semibold mb-2 text-muted">Total Customer</h6>
          <h1 id="totalCustomer" class="display-3 fw-bold text-primary mb-0">0</h1>
          <p class="text-muted small">Customer Active</p>
        </div>
        <div class="col-6">
          <h6 class="fw-semibold mb-2 text-muted">Total Follow Up</h6>
          <h1 id="totalFollowup" class="display-3 fw-bold text-success mb-0">0</h1>
          <p class="text-muted small">Follow Up Tracked</p>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<!-- CARD 2: Two Tables in One Row -->
<div class="row g-3">
  <!-- Table 1 -->
  <div class="col-md-6">
    <div class="card shadow-sm border-0 rounded-4 p-3 h-100">
      <h6 class="fw-semibold mb-3">Monitoring Penagihan</h6>
      <div class="table-responsive">
        <table id="projectTable" class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Leads</th>
              <th>Due Date</th>
              <th>Next Debt</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($penagihan as $item)
            <tr>
              <td><strong>{{ $item->nama_perusahaan }}</strong></td>
              <td>{{ $item->tanggal_tagihan }}</td>
              <td>
                {{ \Carbon\Carbon::parse($item->tanggal_tagihan)->addDays($item->skemaberlangganan)->toDateString() }}

              </td>
              <td class="text-center">
                <button type="button" class="btn btn-icon btn-info" title="Detail Leads" onclick="detailpenagihan('{{ $item->id }}')">
                        <i class="bx bx-info-circle"></i>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Table 2 -->
  <div class="col-md-6">
    <div class="card shadow-sm border-0 rounded-4 p-3 h-100">
      <h6 class="fw-semibold mb-3">Monitoring Follow Up</h6>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="projectTableFollowup">
          <thead class="table-light">
            <tr>
              <th>Leads</th>
              <th>Total Follow Up</th>
              <th>Next Follow Up</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($followup as $item)
            <tr>
              <td><strong>{{ $item->name }}</strong></td>
              <td>{{ $item->followup_ke }}</td>
              <td>

                {{ \Carbon\Carbon::parse($item->tanggal_followup)->addDays(7)->toDateString() }}
              </td>
              <td class="text-center">
                <button type="button" class="btn btn-icon btn-info" title="Detail Leads" onclick="detailfollowup('{{ $item->lead_id }}')">
                  <i class="bx bx-info-circle"></i>
                </button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="custtotal" value="{{ $totcustomer }}">
<input type="hidden" id="futotal" value="{{ $totfollowup }}">


<!-- Modal for Detail Penagihan -->
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

<!-- Modal for Detail Follow Up -->
<div class="modal fade" id="detailFollowUpModal" tabindex="-1" aria-labelledby="detailFollowUpModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailFollowUpModalLabel">Detail Follow Up</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <!-- table -->
          <div class="col-md-12 mb-3">
            <table class="table table-hover align-middle mb-0" id="followUpDetailTable">
              <thead class="table-light">
                <tr>
                  <th>Follow Up Ke</th>
                  <th>Leads Name</th>
                  <th>Tanggal FollowUp</th>
                </tr>
              </thead>
              <tbody id="followUpDetailBody">
              </tbody>
            </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@php
    $map = collect($summarytype)->pluck('totaltype', 'type');
    $leadsData = [
        (int) ($map['hot leads'] ?? 0),
        (int) ($map['warm leads'] ?? 0),
        (int) ($map['cold leads'] ?? 0),
    ];
@endphp

@endsection
@section('vendor-js')
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
 
</script>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/countup.js@2.6.2/dist/countUp.umd.js"></script>



<!-- Initialize DataTable -->
<script>
  $('#projectTable').DataTable({
    responsive: {
      details: {
        type: 'column',
        target: 'tr'
      }
    },
    pageLength: 5,
    lengthChange: false,
    columnDefs: [
      { responsivePriority: 1, targets: 0 }, // Project
      { responsivePriority: 2, targets: -1 } // Actions
    ]
    
  });
  $('#projectTableFollowup').DataTable({
    responsive: {
      details: {
        type: 'column',
        target: 'tr'
      }
    },
    lengthChange: false,

    pageLength: 5,
    columnDefs: [
      { responsivePriority: 1, targets: 0 }, // Project
      { responsivePriority: 2, targets: -1 } // Actions
    ]
    
  });

  const leadsData = @json($leadsData);
  const ctx = document.getElementById('leadsChart').getContext('2d');
  const leadsChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Hot Leads', 'Warm Leads', 'Cold Leads'],
      datasets: [{
        data: leadsData, 
        backgroundColor: [
          '#ffa94d',  
          '#ff6b6b',
          '#4dabf7' 
        ],
        borderColor: '#fff',
        borderWidth: 2,
        hoverOffset: 10
      }]
    },
    options: {
      responsive: true,
      animation: {
        animateRotate: true,
        animateScale: true
      },
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            color: '#495057',
            usePointStyle: true,
            boxWidth: 10
          }
        }
      }
    }
  });
  const totalCustomer = document.getElementById('custtotal').value;
  const customerCounter = new countUp.CountUp('totalCustomer', totalCustomer, {
    duration: 2,
    separator: ','
  });

  const followupCounterValue = document.getElementById('futotal').value;
  const followupCounter = new countUp.CountUp('totalFollowup', followupCounterValue, {
    duration: 2,
    separator: ','
  });

  if (!customerCounter.error && !followupCounter.error) {
    customerCounter.start();
    followupCounter.start();
  } else {
    console.error(customerCounter.error || followupCounter.error);
  }

  function detailpenagihan(id){
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

 function detailfollowup(id) {
    // Kosongkan isi tabel dulu
    $('#followUpDetailBody').html('<tr><td colspan="3" class="text-center">Loading...</td></tr>');

    // Request ke server
    $.ajax({
        url: '/follow-ups/' + id + '/detail',
        method: 'GET',
        success: function(response) {
            if(response.status === 'success') {
                let rows = '';
                if(response.data.length > 0) {
                    response.data.forEach(function(item) {
                        rows += `
                            <tr>
                                <td>${item.followup_ke}</td>
                                <td>${item.name}</td>
                                <td>${item.tanggal_followup}</td>
                            </tr>
                        `;
                    });
                } else {
                    rows = '<tr><td colspan="3" class="text-center">No data found</td></tr>';
                }
                $('#followUpDetailBody').html(rows);
            } else {
                $('#followUpDetailBody').html('<tr><td colspan="3" class="text-center">Data not found</td></tr>');
            }
            // Show modal
            $('#detailFollowUpModal').modal('show');
        },
        error: function(xhr) {
            $('#followUpDetailBody').html('<tr><td colspan="3" class="text-center">Error loading data</td></tr>');
            $('#detailFollowUpModal').modal('show');
        }
    });
}
</script>

@endsection <!-- delete if not needed -->
@section('page-js') @endsection <!-- delete if not needed -->