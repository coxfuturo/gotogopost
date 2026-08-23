@extends('admin.layouts.master')
@section('title') {{ $title}} @endsection
@section('content')

@push('add-modal-code')
<div class="col-auto float-end ms-auto">
    @if($rates->count()>0)
    <a href="{{route('admin.gotogo-postal-rates.edit',['type'=>request()->query('type')])}}" class="btn add-btn"><i class="fa-solid fa-pen"></i> edit</a>
    @else
    <a href="{{route('admin.gotogo-postal-rates.create',['type'=>request()->query('type')])}}" class="btn add-btn"><i class="fa-solid fa-plus"></i>Add</a>
    @endif
</div>
@endpush


<style>
    .table th {
        white-space: nowrap;
        border-top: 1px solid #e2e5e8;
        padding: 8px 23px 8px 2px !important;
        font-weight: 600;
        font-size: 14px;
        line-height: 18px;
        text-align: center;
    }

    .custom-table td {
        padding: 5px !important;
        text-align: center;
        font-size: 14px;
    }
</style>



<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped custom-table mb-0">
                <thead>
                    <tr>
                        <th>Weight</th>
                        <th>Metro City Local</th>
                        <th>Up to 200 kms</th>
                        <th>201 to 1000 kms</th>
                        <th>1001 to 2000 kms</th>
                        <th>Above 2000 kms</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rates as $rate)
                    <tr>
                        <td>{{ $rate['weight'] }}</td>
                        <td>{{ $rate['Local'] }}</td>
                        <td>{{ $rate['upto_200_kms'] }}</td>
                        <td>{{ $rate['201_to_1000_kms'] }}</td>
                        <td>{{ $rate['1001_to_2000_kms'] }}</td>
                        <td>{{ $rate['above_2000_kms'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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