@extends('admin.layouts.master')
@section('title') {{ $title}} @endsection
@section('content')

@push('add-modal-code')
<div class="col-auto float-end ms-auto">
    @if($rates->count()>0)
    <a href="{{route('admin.postal-rates.edit',['type'=>request()->query('type')])}}" class="btn add-btn"><i class="fa-solid fa-pen"></i> edit</a>
    @else
    <a href="{{route('admin.postal-rates.create',['type'=>request()->query('type')])}}" class="btn add-btn"><i class="fa-solid fa-plus"></i>Add</a>
    @endif

</div>
@endpush

<div class="row">
    <div class="col-md-12">

        @if(request()->query('type')==1)
        <h4>Inland Speed Post Rates</h4>
        <p>EMS speed post is a guaranteed and fast mail service for letters,Parcel and Packets deliverable in a specified time,between specified stations in India.(Rate In INR)</p>
        @endif
        <div class="table-responsive">
            <table class="table table-striped custom-table mb-0">
                <thead>
                    <tr>
                        <th>weight</th>
                        <th>Local</th>
                        <th>Up to 200 kms</th>
                        <th>201 to 1000 kms</th>
                        <th>1001 to 2000 kms</th>
                        <th>above 2000 kms</th>
                    </tr>
                </thead>
                @foreach($rates as $rate)
                <tr>
                    <td>{{ $rate->weight }}</td>
                    <td>{{ $rate['Local'] }}</td> <!-- Use square brackets with quotes -->
                    <td>{{ $rate['upto_200_kms'] }}</td>
                    <td>{{ $rate['201_to_1000_kms'] }}</td> <!-- Square brackets for keys with numbers at front -->
                    <td>{{ $rate['1001_to_2000_kms'] }}</td>
                    <td>{{ $rate['above_2000_kms'] }}</td>
                </tr>
                @endforeach
            </table>
        </div>

        @if(request()->query('type')==1)

        <p class="mt-2">The above tariff is exclusive of taxes.The taxes have to be paid extra as notified by the central government from time to time.(Rates effective from 1st october 2012)</p>

        <p class="mt-2">source
            <a href="https://www.indiapost.gov.in" target="_blank">www.indiapost.gov.in</a>
            <span>|</span>
            <a href="https://www.maharashtra.gov.in" target="_blank">www.maharashtra.gov.in</a>
        </p>

        <p class="mt-2">The information provided in this webpage has been compipled to provide general infomation to the public with the utmost care.For accuracy and completeness of the information in the question,Please verify details with any post office or India post website.</p>

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