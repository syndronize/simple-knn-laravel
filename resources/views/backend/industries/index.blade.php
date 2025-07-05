@extends('core.index')
@section('title','Industries') <!-- delete if not needed -->
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
    <h5 class="fw-bold mb-0">Industries</h5>
    <button class="btn btn-primary btn-sm rounded-3 px-3" onclick="addindustry()">
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
          <th>#</th>
          <th>Jenis Industries</th>
          
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>

          @foreach($data['industries'] as $i => $industries)  
        <tr>
            <td>{{$i + 1}}</td>
            <td>{{ $industries->name }}</td>
            <td class="text-center">
                <div >
                    <!-- detail button -->
                    <button type="button" class="btn btn-icon btn-primary" title="Edit Industries" onclick="editindustries('{{ $industries->id }}')">
                        <i class="bx bx-edit"></i>
                    </button>
                </div>
            </td>
            
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<div class="modal fade" id="addindustryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="modalCenterTitle">Add Industries</h5>
        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
        ></button>
        </div>
        <div class="modal-body">
            
            <div class="row">
                {{-- dateindustries --}}
                <div class="col mb-0">
                    <label for="addjenisindustries" class="form-label">Jenis Industries</label>
                    <input type="text" class="form-control" placeholder="e.g : Finance" id="addjenisindustries" value="">
                </div>
            </div>
                
            
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Close
        </button>
        <button type="button" onclick="saveindustries()" class="btn btn-primary">Save changes</button>
        </div>
    </div>
    </div>
</div>

<!-- edit industries modal -->
<div class="modal fade" id="editindustriesModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- wider -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Industries</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="idindustries" value="">
        
        <div class="row">
            {{-- dateindustries --}}
            <div class="col mb-0">
                <label for="editjenisindustries" class="form-label">Jenis Industries</label>
                <input type="text" class="form-control" id="editjenisindustries" value="">
            </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" onclick="updateindustries()" class="btn btn-primary">Save changes</button>
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

function addindustry() {
    $('#addindustryModal').modal('show');
}
function saveindustries() {
    var jenisindustries = $('#addjenisindustries').val();


    $.ajax({
        url: '{{ route("industries.create") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            name: jenisindustries,
        },
        success: function(response) {
            Swal.fire({
                title: 'Success',
                text: 'Industries added successfully',
                icon: 'success',
                showConfirmButton: false,
                timer: 750
            });
            $('#addindustryModal').modal('hide');
            setTimeout(function() {
                location.reload();
            }, 800);          
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error',
                text: 'Failed to add Industries',
                icon: 'error',
                showConfirmButton: true
            });
        }
    });
}
function editindustries(id) {
    $.ajax({
        url: "{{ url('/industries') }}/" + id + "/edit",
        type: 'GET',
        
        success: function(response) {
            // console.log(response);
            
            let industries = response.data;
            $('#idindustries').val(industries.id);
            $('#editjenisindustries').val(industries.name)
            $('#editindustriesModal').modal('show');
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error',
                text: 'Failed to load Industries data',
                icon: 'error',
                showConfirmButton: true
            });
        }
    });
}

function updateindustries() {
    var id = $('#idindustries').val();
    var jenisindustries = $('#editjenisindustries').val();

    
    $.ajax({
        url: '{{ route("industries.update", "") }}/' + id,
        type: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            id: id,
            name: jenisindustries,
        },
        success: function(response) {
            Swal.fire({
                title: 'Success',
                text: 'Industries updated successfully',
                icon: 'success',
                showConfirmButton: false,
                timer: 750
            });
            $('#editindustriesModal').modal('hide');
            setTimeout(function() {
                location.reload();
            }, 800);
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error',
                text: 'Failed to update Industries',
                icon: 'error',
                showConfirmButton: true
            });
        }
    });
}

</script>

@endsection <!-- delete if not needed -->
@section('page-js') @endsection <!-- delete if not needed -->