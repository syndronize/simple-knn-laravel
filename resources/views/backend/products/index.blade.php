@extends('core.index')
@section('title','Product') <!-- delete if not needed -->
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
<div class="card shadow-sm border-0 rounded-4  mb-3">
  <div class="card-body d-flex justify-content-between align-items-center">
    <h5 class="fw-bold mb-0">Product</h5>
    <button class="btn btn-primary btn-sm rounded-3 px-3" onclick="addproduct()">
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
          <th>No</th>
          <th>Name</th>
          <th>Deskripsi</th>
        <th class="text-center">Status</th>
          
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
          @foreach($data['product'] as $key => $product)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->description }}</td>
            @if($product->is_active == 1)
            <td class="text-center"><span class="badge bg-success">Active</span></td>
            @else
            <td class="text-center"><span class="badge bg-danger">Inactive</span></td>
            @endif
            <td class="text-center">
                <div >
                    <!-- detail button -->
                    <button type="button" class="btn btn-icon btn-primary" title="Edit Product" onclick="editproduct('{{ $product->id }}')">
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

<div class="modal fade" id="addproductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="modalCenterTitle">Add Product</h5>
        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
        ></button>
        </div>
        <div class="modal-body">
            {{-- pic name get from users using select --}}
            <div class="row g-3 mb-3">
                {{-- datefollowup --}}
                <div class="col mb-0">
                    <label for="addname" class="form-label">Nama Product</label>
                    <input type="text" class="form-control" id="addname" placeholder="e.g : HRIS" required>
                </div>
            </div>
            <div class="row g-3 mb-3">
                {{-- industry tpe get from industry  using select --}}
                <div class="col mb-0">
                    <label for="adddescription" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="adddescription" rows="3" placeholder="e.g : Human Resource Information System"></textarea>
                </div>
            </div>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="addisactive" checked>
                <label class="form-check-label" for="addisactive">Is Active</label>
            </div>
            
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Close
        </button>
        <button type="button" onclick="saveproduct()" class="btn btn-primary">Save changes</button>
        </div>
    </div>
    </div>
</div>

<!-- edit followup modal -->
<div class="modal fade" id="editproductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- wider -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="idproduct" value="">
        <div class="row g-3 mb-3">
          <div class="col mb-0">
            <label for="editname" class="form-label">Nama Product</label>
            <input type="text" class="form-control" id="editname" placeholder="e.g : HRIS" required>
          </div>
        </div>
        <div class="row g-3 mb-3">
          <div class="col mb-0">
            <label for="editdescription" class="form-label">description</label>
            <textarea class="form-control" id="editdescription" rows="3" placeholder="e.g : Human Resource Information System"></textarea>
          </div>
        </div>
        <div class="form-check form-switch mb-3">
          <input class="form-check-input" type="checkbox" id="editisactive" checked>
          <label class="form-check-label" for="editisactive">Is Active</label>
        </div>
        
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" onclick="updateproduct()" class="btn btn-primary">Save changes</button>
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

function addproduct() {
    $('#addproductModal').modal('show');
}
function saveproduct() {
    var name = $('#addname').val();
    var description = $('#adddescription').val();
    var isActive = $('#addisactive').is(':checked') ? 1 : 0;

    $.ajax({
        url: '{{ route("products.create") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            name: name,
            description: description,
            is_active: isActive,
        },
        success: function(response) {
            Swal.fire({
                title: 'Success',
                text: 'Product added successfully',
                icon: 'success',
                showConfirmButton: false,
                timer: 750
            });
            $('#addproductModal').modal('hide');
            setTimeout(function() {
                location.reload();
            }, 800);          
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error',
                text: 'Failed to add Product',
                icon: 'error',
                showConfirmButton: true
            });
        }
    });
}
function editproduct(id) {
    
    $.ajax({
        url: "{{ url('/products') }}/" + id + "/detail",
        type: 'GET',
        
        success: function(response) {
            console.log(response);
            
            let product = response;
            $('#idproduct').val(product.id);
            $('#editname').val(product.name);
            $('#editdescription').val(product.description);
            $('#editisactive').prop('checked', product.is_active == 1);

            $('#editproductModal').modal('show');
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error',
                text: 'Failed to load Product data',
                icon: 'error',
                showConfirmButton: true
            });
        }
    });
}

function updateproduct() {
    var id = $('#idproduct').val();
    var name = $('#editname').val();
    var description = $('#editdescription').val();
    var isActive = $('#editisactive').is(':checked') ? 1 : 0;
console.log('id: ' + id, ' name: ' + name, ' description: ' + description, ' isActive: ' + isActive);

    if (!id || !name || !description) {
        Swal.fire({
            title: 'Error',
            text: 'Please fill all fields',
            icon: 'error',
            showConfirmButton: true
        });
        return;
    }
    $.ajax({
        url: '{{ route("products.update", "") }}/' + id,
        type: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            name: name,
            description: description,
            is_active: isActive,
        },
        success: function(response) {
            Swal.fire({
                title: 'Success',
                text: 'Product updated successfully',
                icon: 'success',
                showConfirmButton: false,
                timer: 750
            });
            $('#editproductModal').modal('hide');
            setTimeout(function() {
                location.reload();
            }, 800);
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error',
                text: 'Failed to update Product',
                icon: 'error',
                showConfirmButton: true
            });
        }
    });
}

</script>

@endsection <!-- delete if not needed -->
@section('page-js') @endsection <!-- delete if not needed -->