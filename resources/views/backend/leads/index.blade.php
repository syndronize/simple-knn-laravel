@extends('core.index')
@section('title','Leads') <!-- delete if not needed -->
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
    <h5 class="fw-bold mb-0">Leads</h5>
    <div class="d-flex align-items-center gap-2 flex-nowrap">
        <select class="form-select" style="min-width:180px;max-width:220px;" id="typeselect">
          <option value="x" disabled selected>Pilih Type</option>
            {{-- <option value="none">None</option> --}}
            <option value="cold leads">Cold Leads</option>
            <option value="warm leads">Warm Leads</option>
            <option value="hot leads">Hot Leads</option>
        </select>
        <button class="btn btn-primary  rounded-3 px-3 d-flex align-items-center"  onclick="cetakreport()">
          <i class="bx bx-printer"></i>
        </button>
        <button class="btn btn-primary rounded-3 px-3" onclick="addleads()">
            <i class="bx bx-plus"></i> 
        </button>
      </div>
   
  </div>
</div>

<!-- CARD 2: Table Content -->
<div class="card shadow-sm border-0 rounded-4 p-3">
  <div class="table-responsive">
    <table id="projectTable" class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>name</th>
          <th>email</th>
          <th>type</th>
          <th>total follow up</th>
          <th>leads by</th>
          
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
          @foreach($data['leads'] as $leads)
        <tr>
          {{-- foreach from controller--}}
            <td>{{ $leads->name }}</td>
            <td>{{ $leads->email }}</td>
            <td>
                {{-- leads type is none, cold leads, warm leads, hot leads and make if statement and badge --}}
                @if($leads->type == 'none')
                    <span class="badge bg-secondary">None</span>
                @elseif($leads->type == 'cold leads')
                    <span class="badge bg-info">Cold Leads</span>
                @elseif($leads->type == 'warm leads')
                    <span class="badge bg-warning">Warm Leads</span>
                @elseif($leads->type == 'hot leads')
                    <span class="badge bg-danger">Hot Leads</span>
                @else
                    <span class="badge bg-secondary">None</span>

                @endif
            </td>
            <td>{{ $leads->total_fu }}</td>
            <td>{{ $leads->leads_by }}</td>


            <td class="text-center">
                <div >
                    <!-- detail button -->
                    {{-- <button type="button" class="btn btn-icon btn-info" title="Detail Leads" onclick="detailleads('{{ $leads->id }}')">
                        <i class="bx bx-info-circle"></i>
                    </button> --}}
                    <!-- follow up button -->
                    @if($leads->decision != 'berlangganan')
                        <button type="button" class="btn btn-icon btn-success" title="Follow Up Leads" onclick="followupleads('{{ $leads->id }}')">
                            <i class="bx bxs-phone"></i>
                        </button>
                    @endif
                    <button type="button" class="btn btn-icon btn-primary" title="Edit Leads" onclick="editleads('{{ $leads->id }}')">
                        <i class="bx bx-edit"></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-danger" title="Delete Leads" onclick="deleteleads('{{ $leads->id }}')">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </td>
            
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
<!-- modal detail leads -->
<div class="modal fade" id="detailleadsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="modalCenterTitle">Detail Leads</h5>
        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
        ></button>
        </div>
        <div class="modal-body">
            <div class="row g-3 mb-3">
                <div class="col mb-0">
                    <label for="nameleads" class="form-label">Leads</label>
                    <input
                        type="text"
                        id="nameleads"
                        class="form-control"
                        readonly
                    />
                </div>
            </div>
            <div class="row" g-3 mb-3>
                <div class="col mb-0">
                    <label for="emailleads" class="form-label">Email Leads</label>
                    <input
                        type="text"
                        id="emailleads"
                        class="form-control"
                        placeholder="John Doe"
                        readonly
                    />
                </div>
                <div class="col mb-0">
                    <label for="notelpleads" class="form-label">No Telp</label>
                    <input
                        type="text"
                        id="notelpleads"
                        class="form-control"
                        placeholder="08123456789"
                        readonly
                    />
                </div>
            </div>
            <div class="row g-3 mb-3">
                
                <div class="col mb-0">
                    <label for="industryname" class="form-label">Industry Type</label>
                    <input
                        type="text"
                        id="industryname"
                        class="form-control"
                        readonly
                    />
                </div>
                <div class="col mb-0">
                    <label for="typeleads" class="form-label">Type</label>
                    <input
                        type="text"
                        id="typeleads"
                        class="form-control"
                        readonly
                    />
                </div>
               
            </div>
            
            <div class="row g-3 mb-3">
                <div class="col mb-0">
                <label for="leads_by" class="form-label">Leads by</label>
                <input
                    type="text"
                    id="leadsby"
                    class="form-control"
                    readonly
                />
                </div>
                <div class="col mb-0">
                <label for="leads_by" class="form-label">Total Follow Up</label>
                <input
                    type="text"
                    id="totalfuleads"
                    class="form-control"
                    readonly
                />
                </div>
            </div>
            <div class="row">
                <div class="mb-3">
                    <label class="form-label" for="addressleads">Adress</label>
                    <textarea
                    id="addressleads"
                    class="form-control"
                    readonly
                    rows="3"
                    ></textarea>
                </div>
            </div>
            

        
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Close
        </button>
        </div>
    </div>
    </div>
</div>

<div class="modal fade" id="addleadsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="modalCenterTitle">Add Leads</h5>
        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
        ></button>
        </div>
        <div class="modal-body">
            <div class="row g-3 mb-3">
                <div class="col mb-0">
                    <label for="addnameleads" class="form-label">Leads</label>
                    <input
                        type="text"
                        id="addnameleads"
                        class="form-control"
                        placeholder="John Doe"
                    />
                </div>
                <div class="col mb-0">
                    <label for="addemailleads" class="form-label">Email Leads</label>
                    <input
                        type="text"
                        id="addemailleads"
                        class="form-control"
                        placeholder="leads@xxx.xx"
                    />
                </div> 
            </div>
            {{-- pic name get from users using select --}}
            <div class="row g-3 mb-3">
                {{-- no telp --}}
                <div class="col mb-0">
                    <label for="addnotelpleads" class="form-label">No Telp</label>
                    <input
                        type="text"
                        id="addnotelpleads"
                        class="form-control"
                        placeholder="08123456789"
                    />
                </div>
                {{-- industry tpe get from industry  using select --}}
                <div class="col mb-0">
                    <label for="addindustryname" class="form-label">Industry Type</label>
                    <select class="form-select" id="addindustryname">
                        {{-- first select industry but this cant selected --}}
                        <option value="none" selected disabled>Select Industry</option>
                        @foreach($data['industries'] as $ind)
                            <option value="{{ $ind->id }}">{{ $ind->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                <label for="addleadsby" class="form-label">Leads By</label>
                <input
                    type="text"
                    id="addleadsby"
                    class="form-control"
                    placeholder="Leads "
                />
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                <label for="adddecision" class="form-label">Decision</label>
                <select class="form-select" id="adddecision">
                    <option value="none" selected disabled>Select Decision</option>
                    <option value="berlangganan">Berlangganan</option>
                    <option value="tidakberlangganan">Tidak Berlangganan</option>
                </select>
                </div>
            </div>
            <div class="row">
                <div class="mb-3">
                    <label class="form-label" for="addaddressleads">Adress</label>
                    <textarea
                    id="addaddressleads"
                    class="form-control"
                    rows="3"
                    ></textarea>
                </div>
            </div>
                
            
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Close
        </button>
        <button type="button" onclick="saveleads()" class="btn btn-primary">Save changes</button>
        </div>
    </div>
    </div>
</div>

<!-- edit leads modal -->
<div class="modal fade" id="editleadsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- wider -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Leads</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="editLeadsForm">
        <input type="hidden" id="idleads" value="" />
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label for="editnameleads" class="form-label">Leads</label>
              <input type="text" id="editnameleads" class="form-control" />
            </div>
            <div class="col-md-6">
              <label for="editemailleads" class="form-label">Email Leads</label>
              <input type="email" id="editemailleads" class="form-control" />
            </div>
          </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="editnotelpleads" class="form-label">No Telp</label>
                    <input type="text" id="editnotelpleads" class="form-control" />
                </div>
                <div class="col-md-6">
                    <label for="editindustryname" class="form-label">Industry Type</label>
                    <select class="form-select" id="editindustryname">
                        <option value="none" selected disabled>Select Industry</option>
                        @foreach($data['industries'] as $ind)
                            <option value="{{ $ind->id }}">{{ $ind->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="editleadsby" class="form-label">Leads By</label>
                    <input type="text" id="editleadsby" class="form-control" />
                </div>
                <div class="col-md-6" id="editdecisionrow">

                </div>
            </div>
            
            <div class="row g-3 mb-3">
                <div class="col mb-0">
                    <label for="editaddressleads" class="form-label">Address</label>
                    <textarea id="editaddressleads" class="form-control" rows="3"></textarea>
                </div>
            </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" onclick="updateleads()" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- followup leads modal -->
<div class="modal fade" id="followupleadsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Follow Up Leads</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idleads" value="" />
                <!-- tanggal follow up (date) & status (select)-->
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
                <button type="button" onclick="savefollowup()" class="btn btn-primary">Save changes</button>
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


function followupleads(id) {
    $('#idleads').val(id);
    $('#followupleadsModal').modal('show');
}

function savefollowup() {
    var leadId = $('#idleads').val();
    if (!leadId) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Lead ID is missing.',
            showConfirmButton: false,
        });
        return;
    }
    var tanggalFollowUp = $('#tanggalfollowup').val();
    var statusFollowUp = $('#statusfollowup').val();
    var dibalas = $('#dibalas').is(':checked') ? 1 : 0;
    var responPositif = $('#responpositif').is(':checked') ? 1 : 0;
    var pitching = $('#pitching').is(':checked') ? 1 : 0;
    var penawaran = $('#penawaran').is(':checked') ? 1 : 0;
    $.ajax({
        url : '{{ route("follow-ups.create") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            lead_id : leadId,
            tanggal_followup : tanggalFollowUp,
            status : statusFollowUp,
            dibalas: dibalas,
            respon_positif: responPositif,
            pitching: pitching,
            penawaran: penawaran,
        },
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Follow up saved successfully!',
                    showConfirmButton: false,
                    timer: 750
                });
                $('#followupleadsModal').modal('hide');
                // wait for 1 second then reload the page
                setTimeout(function() {
                    location.reload();
                }, 800);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Failed to save follow up.',
                });
            }
        },
        error: function(xhr) {
            let message = 'Something went wrong';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,
                });
            } else {
                message = xhr.responseText;
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,
                });
            }
        }
    });
}
function detailleads(id) {
    $.ajax({
        url: "{{ url('/leads') }}/" + id + "/edit",
        type: 'GET',
        success: function(response) {
            if (response.status === 'success') {
                const leads = response.data;
                $('#nameleads').val(leads.name);
                $('#emailleads').val(leads.email);
                $('#notelpleads').val(leads.notelp);
                $('#industryname').val(leads.industry_name);
                $('#typeleads').val(leads.type);
                $('#leadsby').val(leads.leads_by);
                $('#totalfuleads').val(leads.total_fu);
                $('#addressleads').val(leads.alamat);
                $('#detailleadsModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "Leads not found.",
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "Error fetching leads data: " + xhr.responseText,
                showConfirmButton: false,
            });
        }
    });
}
function addleads(){
    $('#addleadsModal').modal('show');
}
function saveleads(){
    var name = $('#addnameleads').val();
    var email = $('#addemailleads').val(); 
    var notelpleads = $('#addnotelpleads').val();
    var industry = $('#addindustryname').val();
    var leadsby = $('#addleadsby').val();
    var addressleads = $('#addaddressleads').val();
    var decision = $('#adddecision').val();

    $.ajax({
        url: "{{ url('/leads') }}",
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            name: name,
            email: email,
            notelp: notelpleads,
            industry_id: industry,
            leads_by: leadsby,
            alamat: addressleads,
            decision: decision
        },
        success: function(response) {
            if (response.status === 'success') {
                // using sweetalert2
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Leads added successfully!',
                    showConfirmButton: false,
                    timer: 750
                });

                $('#addleadsModal').modal('hide');
                // wait for 1 second then reload the page
                setTimeout(function() {
                    location.reload();
                }, 800);
                location.reload();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Failed to add leads.',
                    showConfirmButton: false,
                });
            }
        },
        error: function(xhr) {
            let message = 'Something went wrong';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,
                });
            } else {
                message = xhr.responseText;
                
            }
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
            });
        }
    });
}
function editleads(id) {
    $.ajax({
        url: "{{ url('/leads') }}/" + id + "/edit",
        type: 'GET',
        success: function(response) {
            
            if (response.status === 'success') {
                const leads = response.data;
                $('#editpicname option').each(function() {
                    if ($(this).val() == leads.pic_id) {
                        $(this).prop('selected', true);
                    }
                });
                console.log(leads.decision);
                $('#idleads').val(leads.id);
                $('#editnameleads').val(leads.name);
                $('#editemailleads').val(leads.email);
                $('#editnotelpleads').val(leads.notelp);
                $('#editindustryname').val(leads.industry_id).change();
                // if leads.decision is tidakberlanganan then hide the decision row
                if (leads.decision == 'tidakberlangganan' || leads.decision == 'none' || leads.decision == null) {
                    $('#editdecisionrow').html(`
                            <label for="editdecision" class="form-label">Decision</label>
                            <select class="form-select" id="editdecision">
                                <option value="none" selected disabled>Select Decision</option>
                                <option value="berlangganan">Berlangganan</option>
                                <option value="tidakberlangganan">Tidak Berlangganan</option>
                            </select>
                    `);
                } else {
                    
                }
                $('#editleadsby').val(leads.leads_by);
                $('#editaddressleads').val(leads.alamat);
                $('#editleadsModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "Leads not found.",
                });
            }
        },
        error: function(xhr) {
            //swal error
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "Error fetching leads data: " + xhr.responseText,
                showConfirmButton: false,
            });
        }
    });
}

function updateleads() {
    var leadId = $('#idleads').val();
    if (!leadId) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Lead ID is missing.',
            showConfirmButton: false,

        });
        return;
    }
    var name = $('#editnameleads').val();
    var email = $('#editemailleads').val();
    var notelp = $('#editnotelpleads').val();
    var industryname = $('#editindustryname').val();
    var leadsby = $('#editleadsby').val();
    var addressleads = $('#editaddressleads').val();
    var decision = $('#editdecision').val(); // Get the decision value
    if (!decision) {
        decision = 'none'; // Default to 'none' if not set
    }

    // Get the ID of the lead being edited

    $.ajax({
        url: "{{ url('/leads') }}/" + leadId,
        type: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            name: name,
            email: email,
            notelp: notelp,
            leads_by: leadsby,
            industry_id: industryname,
            alamat: addressleads,
            decision: decision
        },
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Leads updated successfully!',
                    showConfirmButton: false,
                    timer: 750
                });

                $('#editleadsModal').modal('hide');
                // wait for 1 second then reload the page
                setTimeout(function() {
                    location.reload();
                }, 800);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Failed to update leads.',
                });
            }
        },
        error: function(xhr) {
            let message = 'Something went wrong';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
                swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,

                });
            } else {
                message = xhr.responseText;
                swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,

                });
            }
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,

            });
        }
    });
}
function deleteleads(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ url('/leads') }}/" + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire(
                            'Deleted!',
                            'Your leads has been deleted.',
                            'success'
                        );
                        // wait for 1 second then reload the page
                        setTimeout(function() {
                            location.reload();
                        }, 800);
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message || 'Failed to delete leads.',
                            'error'
                        );
                    }
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        xhr.responseText || 'Something went wrong.',
                        'error'
                    );
                }
            });
        }
    });
}

function cetakreport() {
    var type = $('#typeselect').val();
    if (type === 'x') {
        Swal.fire({
            icon: 'warning',
            title: 'Warning',
            text: 'Please select a type to export report.',
            showConfirmButton: false,
        });
        return;
    }

    $.ajax({
        url: '{{ route("export.leads") }}',
        method: 'GET',
        data: {
            type: type 
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