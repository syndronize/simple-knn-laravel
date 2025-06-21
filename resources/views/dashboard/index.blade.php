@extends('core.index')
@section('title','Sample Page') <!-- delete if not needed -->
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
      <h6 class="fw-semibold mb-3">Angular Projects</h6>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Project</th>
              <th>Status</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>Angular CRM</strong></td>
              <td><span class="badge bg-success-subtle text-success rounded-pill px-3">Active</span></td>
              <td class="text-center">
                <button class="btn btn-icon btn-primary btn-sm me-1"><i class="bx bx-edit"></i></button>
                <button class="btn btn-icon btn-danger btn-sm"><i class="bx bx-trash"></i></button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Table 2 -->
  <div class="col-md-6">
    <div class="card shadow-sm border-0 rounded-4 p-3 h-100">
      <h6 class="fw-semibold mb-3">React Projects</h6>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Project</th>
              <th>Status</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>React Dashboard</strong></td>
              <td><span class="badge bg-warning-subtle text-warning rounded-pill px-3">Pending</span></td>
              <td class="text-center">
                <button class="btn btn-icon btn-primary btn-sm me-1"><i class="bx bx-edit"></i></button>
                <button class="btn btn-icon btn-danger btn-sm"><i class="bx bx-trash"></i></button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


@endsection
@section('vendor-js')
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  const ctx = document.getElementById('leadsChart').getContext('2d');
  const leadsChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Hot Leads', 'Warm Leads', 'Cold Leads'],
      datasets: [{
        data: [25, 35, 40], // Replace with your values
        backgroundColor: [
          '#ff6b6b',  // Hot - Vivid Red
          '#ffa94d',  // Warm - Vibrant Orange
          '#4dabf7'   // Cold - Bright Blue
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
  columnDefs: [
    { responsivePriority: 1, targets: 0 }, // Project
    { responsivePriority: 2, targets: -1 } // Actions
  ]
});
</script>
<script>
  const customerCounter = new countUp.CountUp('totalCustomer', 10, {
    duration: 2,
    separator: ','
  });

  const followupCounter = new countUp.CountUp('totalFollowup', 24, {
    duration: 2,
    separator: ','
  });

  if (!customerCounter.error && !followupCounter.error) {
    customerCounter.start();
    followupCounter.start();
  } else {
    console.error(customerCounter.error || followupCounter.error);
  }
</script>

@endsection <!-- delete if not needed -->
@section('page-js') @endsection <!-- delete if not needed -->