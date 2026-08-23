@extends('admin.layouts.master')
@section('content')

@push('add-modal-code')
<div class="col-auto float-end ms-auto">
    @if($rates->count() > 0)
    <a href="{{ route('admin.india-post-br-rate.edit', ['type' => 1]) }}" class="btn add-btn">
        <i class="fa-solid fa-pen"></i> Edit
    </a>
@else
    <a href="{{ route('admin.india-post-br-rate.create', ['type' => 1]) }}" class="btn add-btn">
        <i class="fa-solid fa-plus"></i> Add
    </a>
@endif

</div>
@endpush

<div class="row">
    <div class="col-md-12">

        @if(request()->query('type')==1)
        <h4>India Post Parcel Contractual</h4>
        @endif
        <div class="table-responsive">
            <table class="table table-striped custom-table mb-0">
                <thead>
                    

                    <tr>
                        <th>Distance</th>
                        <th></th>
                        <th></th>
                        <th>Weight</th>
                       
                    </tr>
                    <tr>
                        <th>
                            
                        </th>
                        <th>Upto 2 kgs</th>
                        <th>Every addl kg upto 5kg</th>
                        <th>Every above kg upto 5kg</th>
                        
                    </tr>
                </thead>
                @foreach($rates as $rate)
                <tr>
                    <td>{{ $rate->distance }}</td>
                    <td>{{ $rate->upto_2kg }}</td> 
                    <td>{{ $rate['addl_upto_5kg'] }}</td> 
                    <td>{{ $rate['above_upto_5kg'] }}</td>
                    <!-- <td>{{ $rate['1001_to_2000_kms'] }}</td>
                    <td>{{ $rate['above_2000_kms'] }}</td> -->
                </tr>
                @endforeach
            </table>
        </div>

        @if(request()->query('type')==1)

        <p class="pt-2">* Provided it is not covered under within state or neighbouring state </p>
        <p class="">* Tariff exclusive of taxes as notified by the Central Government.</p>

        @endif
    </div>
</div>


<!-- Delete  Modal -->
<div class="modal custom-modal fade" id="delete_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Delete</h3>
                    <p>Are you sure want to delete?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <a href="" id="delete_button" class="btn btn-primary continue-btn">Delete</a>
                        </div>
                        <div class="col-6">
                            <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Delete  Modal -->

@push('page-javascript')
<script type="text/javascript">
    function delete_modal(id) {
        const deleteUrl = "{{ route('admin.parcel.delete', ['id' => ':id']) }}".replace(':id', id);
        $('#delete_button').attr('href', deleteUrl);
        $('#delete_modal').modal('show');
    }
</script>
@endpush
@endsection