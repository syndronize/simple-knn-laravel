@extends('core.index')
@section('title','Follow Up') <!-- delete if not needed -->
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
    <h5 class="fw-bold mb-0">Follow Up</h5>
    {{-- <button class="btn btn-primary btn-sm rounded-3 px-3" onclick="addfollowup()">
      <i class="bx bx-plus"></i> Add
    </button> --}}
  </div>
</div>

<!-- CARD 2: Table Content -->
<div class="card shadow-sm border-0 rounded-4 p-3">
  <div class="table-responsive">
    <table id="projectTable" class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Follow Up Ke-</th>
          <th>Leads</th>
          <th>Tanggal</th>
          
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
          @foreach($data['followup'] as $followup)
        <tr>
            <td>{{ $followup->followup_ke }}</td>
            <td>{{ $followup->name }}</td>
            <td>{{ $followup->tanggal_followup }}</td>
            <td class="text-center">
                <div >
                    <!-- jika followup id terdapat dalam $data['maxid'] maka data edit bisa digunakan atau visible-->
                    @if(in_array($followup->id, $data['maxid']->pluck('idmax')->toArray()))
                    <button type="button" class="btn btn-icon btn-primary" title="Edit Follow Up" onclick="editfollowup('{{ $followup->id }}')">
                        <i class="bx bx-edit"></i>
                    </button>
                    @else
                    <button type="button" class="btn btn-icon btn-secondary" title="Edit Follow Up" disabled>
                        <i class="bx bx-edit"></i>
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



<!-- edit followup modal -->
<div class="modal fade" id="followupleadsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Follow Up Leads</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idleads" value="" />
                <input type="hidden" id="idfollowup" value="" />
                <div class="row g-3 mb-3">
                    <div class="col mb-0">
                        <label for="tanggalfollowup" class="form-label">Tanggal Follow Up</label>
                        <input type="date" id="tanggalfollowup" class="form-control" />
                    </div>
                    <div class="col mb-0">
                        <label for="statusfollowup" class="form-label">Status Follow Up</label>
                        <select class="form-select" id="statusfollowup">
                            <option value="none" selected disabled>Select Status</option>
                            <option value="open">Open</option>
                            <option value="progress">In Progress</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                </div>
                <!-- toggle for dibalas dan respon positif 1 row 2 col-->
                <div class="row g-3 mb-3">
                    <div class="col mb-0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="dibalas" />
                            <label class="form-check-label" for="dibalas">Dibalas</label>
                        </div>
                    </div>
                    <div class="col mb-0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="responpositif" />
                            <label class="form-check-label" for="responpositif">Respon Positif</label>
                        </div>
                    </div>
                </div>
                <!-- toggle for pitching dan penawaran 1 row 2 col-->
                <div class="row g-3 mb-3">
                    <div class="col mb-0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="pitching" />
                            <label class="form-check-label" for="pitching">Pitching</label>
                        </div>
                    </div>
                    <div class="col mb-0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="penawaran" />
                            <label class="form-check-label" for="penawaran">Penawaran</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" onclick="updatefollowup()" class="btn btn-primary">Save changes</button>
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



function editfollowup(id) {
    // console.log(id);
    
    $.ajax({
        url: "{{ url('/follow-ups') }}/" + id + "/edit",
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            let followup = response.data
            console.log(followup);
            
            $('#idfollowup').val(followup.id);
            $('#editleadsid').val(followup.lead_id);
            $('#tanggalfollowup').val(followup.tanggal_followup);
            $('#statusfollowup').val(followup.status);
            $("#dibalas").prop("checked", followup.dibalas);
            $("#responpositif").prop("checked", followup.respon_positif);
            $("#pitching").prop("checked", followup.pitching);
            $("#penawaran").prop("checked", followup.penawaran);
            $('#followupleadsModal').modal('show');
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error',
                text: 'Failed to load follow up data',
                icon: 'error',
                showConfirmButton: true
            });
        }
    });
}

function updatefollowup() {
    var id = $('#idfollowup').val();
    // var leadsid = $('#editleadsid').val();
    var jenisfollowup = $('#statusfollowup').val();
    var tglfu = $('#tanggalfollowup').val();
    var dibalas = $('#dibalas').is(':checked') ? 1 : 0;
    var respon_positif = $('#responpositif').is(':checked') ? 1 : 0;
    var pitching = $('#pitching').is(':checked') ? 1 : 0;
    var penawaran = $('#penawaran').is(':checked') ? 1 : 0;
    

    if ( !jenisfollowup || !tglfu) {
        alert('Please fill all fields');
        return;
    }

    $.ajax({
        url: '{{ route("follow-ups.update", "") }}/' + id,
        type: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            status: jenisfollowup,
            tanggal_followup: tglfu,
            dibalas: dibalas,
            respon_positif: respon_positif,
            pitching: pitching,
            penawaran: penawaran
        },
        success: function(response) {
            $('#followupleadsModal').modal('hide');
            Swal.fire({
                title: 'Success',
                text: 'Follow up updated successfully',
                icon: 'success',
                showConfirmButton: false,
                timer: 750
            });
            setTimeout(function() {
                location.reload();
            }, 800);
        },
        error: function(xhr) {
            Swal.fire({
                title: 'Error',
                text: 'Failed to update follow up',
                icon: 'error',
                showConfirmButton: true
            });
        }
    });
}

</script>

@endsection <!-- delete if not needed -->


@section('page-js') @endsection <!-- delete if not needed -->