@extends('admin.layouts.master')
@section('title') IndiaPost Barcode @endsection
@section('content')
@push('add-modal-code')

<div class="col-auto float-end ms-auto">
    <button class="btn add-btn" data-bs-toggle="modal" data-bs-target="#barcodeModal" onclick="resetModal()">
        <i class="fa-solid fa-plus"></i> Add
    </button>
</div>
@endpush

<style>
    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        display: block;
    }
    .table-newdatatable .dataTables_length {
        display: block;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered custom-table datatable table-hover">
                        <thead>
                            <tr>
                                <th>SN#</th>
                                <th class="text-center">Service Type</th>
                                <th class="text-center">State</th>
                                <th class="text-center">Code</th>
                                <th class="text-center">Range From</th>
                                <th class="text-center">Range To</th>
                                <th class="text-center">Available</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barcodes as $i => $data)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td class="text-center">
                                    @if($data->service_type == 5)
                                    {{\App\Models\Admin::INDIA_POST_SPEED}}
                                    @else
                                    {{\App\Models\Admin::INDIA_POST_BUSINESS}}
                                    @endif
                                </td>
                                <td class="text-center">{{ $data->state }}</td>
                                <td class="text-center">{{ $data->code }}</td>
                                <td class="text-center">{{ $data->prefix . $data->range_from . $data->postfix }}</td>
                                <td class="text-center">{{ $data->prefix . $data->range_to . $data->postfix }}</td>
                                <td class="text-center">{{ $data->availables }}</td>
                                <td class="text-end">
                                    <div class="dropdown dropdown-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="material-icons">more_vert</i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#" onclick="editModal({{ json_encode($data) }})">
                                                <i class="fa-solid fa-pencil m-r-5"></i> Edit
                                            </a>
                                            <a class="dropdown-item" href="#" onclick="deleteBarcode('{{ $data->id }}')">
                                                <i class="fa-regular fa-trash-can m-r-5"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="barcodeModal" tabindex="-1" aria-labelledby="barcodeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">            
            <form id="barcodeForm" action="{{ route('indiapostbarcodes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST" id="formMethod">
                <input type="hidden" name="id" id="barcode_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="barcodeModalLabel">Add New Barcode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="state" class="form-label">Service</label>
                        <select class="form-select" name="service_type" id="service_type" required>
                            <option selected disabled>Select Service</option>
                            <option value="5">SP-Inland Document</option>
                            <option value="6">Seed Post-Parcel Domestic</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <select class="form-select" name="state" id="state" required>
                            <option selected disabled>Select State</option>
                            @foreach($states as $state)
                            <option value="{{ $state }}">{{ $state }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form-label">Code</label>
                        <input type="text" class="form-control" name="code" id="code" required maxlength="2">
                    </div>
                    <div class="mb-3">
                        <label for="prefix" class="form-label">Prefix</label>
                        <input type="text" class="form-control" name="prefix" id="prefix" required maxlength="3">
                    </div>
                    <div class="mb-3">
                        <label for="range_from" class="form-label">Range From</label>
                        <input type="number" class="form-control" name="range_from" id="range_from" required>
                    </div>
                    <div class="mb-3">
                        <label for="range_to" class="form-label">Range To</label>
                        <input type="number" class="form-control" name="range_to" id="range_to" required>
                    </div>
                    <div class="mb-3">
                        <label for="postfix" class="form-label">Postfix</label>
                        <input type="text" class="form-control" name="postfix" id="postfix" required maxlength="3">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<<script>
    function resetModal() {
        $('#barcodeForm').trigger("reset");
        $('#formMethod').remove(); // Remove any existing _method input
        $('#barcodeModalLabel').text('Add New Barcode');
        $('#barcodeForm').attr('action', '{{ route('indiapostbarcodes.store') }}');
        $('#barcodeForm').append('<input type="hidden" name="_method" value="POST" id="formMethod">');
    }
    function editModal(data) {
        $('#barcode_id').val(data.id);
        $('#state').val(data.state);
        $('#code').val(data.code);
        $('#prefix').val(data.prefix);
        $('#range_from').val(data.range_from);
        $('#range_to').val(data.range_to);
        $('#postfix').val(data.postfix); 
        $('#formMethod').remove(); // Remove existing _method input to avoid duplication
        $('#barcodeForm').append('<input type="hidden" name="_method" value="POST" id="formMethod">');
        $('#barcodeModalLabel').text('Edit Barcode');
        $('#barcodeForm').attr('action', 'indiapostbarcodes/' + data.id + '/update');
        $('#barcodeModal').modal('show');
    }
</script>

<script>
    function deleteBarcode(id) {
        if (confirm('Are you sure you want to delete this barcode?')) {
            let form = $('<form>', {
                'method': 'GET',
                'action': '{{ route("indiapostbarcodes.destroy", ":id") }}'.replace(':id', id)
            });

            let methodInput = $('<input>', {
                'type': 'hidden',
                'name': '_method',
                'value': 'GET'
            });

            let csrfInput = $('<input>', {
                'type': 'hidden',
                'name': '_token',
                'value': '{{ csrf_token() }}'
            });
            form.append(methodInput, csrfInput).appendTo('body').submit();
        }
    }
</script>    

@endsection
