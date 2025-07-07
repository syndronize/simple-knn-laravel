@extends('core.index')
@section('title','Users') <!-- delete if not needed -->
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
    <h5 class="fw-bold mb-0">Users</h5>
    @if(session()->get('role') == 'admin')

    <button class="btn btn-primary btn-sm rounded-3 px-3" onclick="adduser()">
      <i class="bx bx-plus"></i> Add
    </button>
    @endif
  </div>
</div>

<!-- CARD 2: Table Content -->
<div class="card shadow-sm border-0 rounded-4 p-3">
  <div class="table-responsive">
    <table id="projectTable" class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Name</th>
          <th>Username</th>
          <th>Email</th>
          <th>Role</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
          @foreach($data as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->username }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
            <td class="text-center">
                <div >
                    <button type="button" class="btn btn-icon btn-primary" title="Edit User" onclick="edituser('{{ $user->id }}')">
                    <i class="bx bx-edit"></i>
                    </button>
                    @if(session()->get('role') == 'admin')
                    <button type="button" class="btn btn-icon btn-danger" title="Delete User" onclick="deleteuser('{{ $user->id }}')">
                    <i class="bx bx-trash"></i>
                    </button>
                    @endif
                </div>
            </td>
            
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<div class="modal fade" id="adduserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="modalCenterTitle">Add Users</h5>
        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
        ></button>
        </div>
        <div class="modal-body">
            <div class="row g-2">
                <div class="col mb-0">
                    <label for="nameusers" class="form-label">Name</label>
                    <input
                        type="text"
                        id="nameusers"
                        class="form-control"
                        placeholder="John Doe"
                    />
                </div>
                <div class="col mb-0">
                    <label for="roleusers" class="form-label">Role</label>
                    <select class="form-select" id="roleusers">
                        {{-- first select role but this cant selected --}}
                        <option value="none" selected disabled>Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="customers">Customers</option>
                        <option value="marketing">Marketing</option>
                    </select>
                </div>
            </div>
            <div class="row g-2">
                <div class="col mb-0">
                    <label for="usernameusers" class="form-label">Username</label>
                    <input
                        type="text"
                        id="usernameusers"
                        class="form-control"
                        placeholder="johndoe"
                    />
                </div>
                <div class="col mb-0">
                    <label for="emailusers" class="form-label">Email</label>
                    <input
                        type="text"
                        id="emailusers"
                        class="form-control"
                        placeholder="johndoe@xxx.xx"
                    />
                </div>
            </div>
            
            <div class="row">
                <div class="col mb-3">
                <label for="passwordusers" class="form-label">Password</label>
                <input
                    type="password"
                    id="passwordusers"
                    class="form-control"
                    placeholder="●●●●●●●"
                />
                </div>
            </div>
        
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Close
        </button>
        <button type="button" onclick="saveuser()" class="btn btn-primary">Save changes</button>
        </div>
    </div>
    </div>
</div>

<!-- Modal for Edit User -->
<div class="modal fade" id="edituserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="modalCenterTitle">Edit User</h5>
        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
        ></button>
        </div>
        <div class="modal-body">
            <!-- Form fields will be populated dynamically -->
            <input type="hidden" id="iduseredit" />
            <div class="row g-2">
                <div class="col mb-0">
                    <label for="nameuseredit" class="form-label">Name</label>
                    <input
                        type="text"
                        id="nameuseredit"
                        class="form-control"
                        placeholder="John Doe"
                    />
                </div>
                <div class="col mb-0">
                    <label for="roleuseredit" class="form-label">Role</label>
                    <select class="form-select" id="roleuseredit">
                        {{-- first select role but this cant selected --}}
                        <option value="none" selected disabled>Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="customers">Customers</option>
                        <option value="marketing">Marketing</option>
                    </select>
                </div>
            </div>
            <div class="row g-2">
                <div class="col mb-0">
                    <label for="usernameuseredit" class="form-label">Username</label>
                    <input
                        type="text"
                        id="usernameuseredit"
                        class="form-control"
                        placeholder="johndoe"
                    />
                </div>
                <div class="col mb-0">
                    <label for="emailuseredit" class="form-label" >Email </label>
                    <input
                        type="text"
                        id="emailuseredit"
                        class="form-control"
                        placeholder=""
                        disabled
                    />
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                <label for="passworduseredit" class="form-label">Password</label>
                <input
                    type="password"
                    id="passworduseredit"
                    class="form-control"
                    placeholder="●●●●●●●"
                />
                </div>  
            </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Close  
        </button>
        <button type="button" onclick="updateuser()" class="btn btn-primary">Submit changes</button>
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

function adduser(){
    $('#adduserModal').modal('show');
}
function saveuser(){
    // Get values from input fields
    var name = $('#nameusers').val();
    var username = $('#usernameusers').val();
    var email = $('#emailusers').val();
    var role = $('#roleusers').val();
    var password = $('#passwordusers').val();

    // Perform validation (basic example)
    if(name === '' || username === '' || email === '' || role === 'none' || password === ''){
        alert('Please fill all fields');
        return;
    }
    //ajax request to save user data route name users.create
    $.ajax({
        url: "{{ route('users.create') }}",
        type: 'POST',
        data: {
            name: name,
            username: username,
            email: email,
            role: role,
            password: password,
            _token: '{{ csrf_token() }}' // CSRF token for security
        },
        success: function(response) {
            // Handle success response
            alert('User added successfully');
            $('#adduserModal').modal('hide');
            location.reload(); // Reload the page to see the new user
        },
        error: function(xhr) {
             let message = "An error occurred";
    if (xhr.responseJSON && xhr.responseJSON.message) {
        message = xhr.responseJSON.message;
    } else {
        message = xhr.responseText; // fallback
    }

    alert("Error adding user: " + message);
        }
    });
        
}

function edituser(id) {
    $.ajax({
        url: "{{ url('/users') }}/" + id + "/edit",
        type: 'GET',
        success: function(response) {
            if (response.status === 'success') {
                const user = response.data;
                $('#iduseredit').val(id);
                $('#nameuseredit').val(user.name);
                $('#usernameuseredit').val(user.username);
                $('#emailuseredit').val(user.email);
                $('#roleuseredit').val(user.role);
                $('#edituserModal').modal('show');
            } else {
                alert("User not found.");
            }
        },
        error: function(xhr) {
            alert("Error fetching user data: " + xhr.responseText);
        }
    });
}

function updateuser() {
    var id = $('#iduseredit').val();
    var name = $('#nameuseredit').val();
    var username = $('#usernameuseredit').val();
    var role = $('#roleuseredit').val();
    var password = $('#passwordusers').val();

    $.ajax({
        url: "{{ url('/users') }}/" + id,
        type: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            _method: 'PUT', // Laravel method spoofing
            name: name,
            username: username,
            role: role,
            password: password
        },
        success: function(response) {
            if (response.status === 'success') {
                alert('User updated successfully');
                $('#edituserModal').modal('hide');
                location.reload();
            } else {
                alert('Update failed: ' + response.message);
            }
        },
        error: function(xhr) {
            let message = 'Something went wrong';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            } else {
                message = xhr.responseText;
            }
            alert('Error updating user: ' + message);
        }
    });
}
function deleteuser(id){
    // using sweetalert2
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ url('/users') }}/" + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire(
                            'Deleted!',
                            'User has been deleted.',
                            'success'
                        );
                        location.reload();
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function(xhr) {
                    let message = "An error occurred";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    } else {
                        message = xhr.responseText; // fallback
                    }
                    Swal.fire(
                        'Error!',
                        message,
                        'error'
                    );
                }
            });
        }
    });
}

</script>

@endsection <!-- delete if not needed -->
@section('page-js') @endsection <!-- delete if not needed -->