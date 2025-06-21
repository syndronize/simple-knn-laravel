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

  
<!-- CARD 1: Header with Title and Add Button -->
<div class="card shadow-sm border-0 rounded-4  mb-3">
  <div class="card-body d-flex justify-content-between align-items-center">
    <h5 class="fw-bold mb-0">📋 Project Overview</h5>
    <button class="btn btn-primary btn-sm rounded-3 px-3">
      <i class="bx bx-plus"></i> Add
    </button>
  </div>
</div>

<!-- CARD 2: Table Content -->
<div class="card shadow-sm border-0 rounded-4 p-3">
  <div class="table-responsive">
    <table id="projectTable" class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Project</th>
          <th>Client</th>
          <th>Users</th>
          <th>Status</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><i class="fab fa-angular fa-lg text-danger me-2"></i> <strong>Angular Project</strong></td>
          <td>Albert Cook</td>
          <td>
            <div class="d-flex align-items-center gap-1">
              <img src="https://i.pravatar.cc/24?img=5" class="rounded-circle" width="30" />
              <img src="https://i.pravatar.cc/24?img=6" class="rounded-circle" width="30" />
              <img src="https://i.pravatar.cc/24?img=7" class="rounded-circle" width="30" />
            </div>
          </td>
          <td><span class="badge bg-success-subtle text-success fw-semibold px-3 py-1 rounded-pill">Active</span></td>
          <td class="text-center">
            <button class="btn btn-icon btn-primary btn-sm me-1" title="Edit Data">
              <i class="bx bx-edit"></i>
            </button>
            <button class="btn btn-icon btn-danger btn-sm me-1" title="Delete Data">
              <i class="bx bx-trash"></i>
            </button>
            <button class="btn btn-icon btn-secondary btn-sm" title="View Details">
              <i class="bx bx-show"></i>
            </button>
          </td>
        </tr>
        <!-- More rows as needed -->
      </tbody>
    </table>
  </div>
</div>



@endsection
@section('vendor-js')

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

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
@endsection <!-- delete if not needed -->
@section('page-js') @endsection <!-- delete if not needed -->