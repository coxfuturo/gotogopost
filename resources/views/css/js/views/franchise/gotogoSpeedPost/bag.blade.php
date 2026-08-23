@extends('franchise.layouts.master')

@section('title') Bag Management @endsection

@section('content')


<style>
    .submit-section {
    text-align: center;
    margin-top: 0px;
    float: left;
    width: 100%;
}
</style>

@push('add-modal-code')
<div class="col-auto float-end ms-auto">
    <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_role_user"><i class="fa-solid fa-plus"></i> Add</a>
    <div class="view-icons">
        <a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
        <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa-solid fa-bars"></i></a>
    </div>
</div>
@endpush
<!-- Search Filter -->
<div class="row filter-row">
    <div class="col-sm-6 col-md-3">
        <div class="input-block mb-3 form-focus">
            <input type="text" class="form-control floating input1">
            <label class="focus-label">Bag ID</label>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="input-block mb-3 form-focus">
            <input type="text" class="form-control floating input2">
            <label class="focus-label">Bag Name</label>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="d-grid">
            <a href="#" class="btn btn-success customSearch"> Search </a>
        </div>
    </div>
</div>
<!-- Search Filter -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="row staff-grid-row">
    @if ($bag->count()>0)
    @foreach ( $bag as $data)
   
    <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3 d-flex">
        <a href="{{route('franchise.bag.viewParcel',['id'=>$data->id])}}">
        <div class="profile-widget w-100" style="padding:10px">   
            <div class="dash-card-icon">
                <i class="fa-solid fa-suitcase" style="color:#666363;font-size:40px"></i>
            </div>
            <td class="text-end">
                <div class="dropdown dropdown-action" style="position: absolute;right: 0;top: 10px;">
                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{route('franchise.bag.viewParcel',['id'=>$data->id])}}"><i class="fa-regular fa-eye m-r-5"></i> view Parcel</a>
                        <a class="dropdown-item" href="{{route('franchise.bag.assignParcel',['id'=>$data->id])}}"><i class="fas fa-check-circle"></i> Assign Parcel</a>
                        {{-- <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_role{{$data->id}}"  class="btn btn-white btn-sm m-t-10"><i class="fa-solid fa-pencil m-r-5"></i> Edit</a>
                        <a class="dropdown-item" onclick="delete_modal({{$data->id}})" data-bs-toggle="modal" data-bs-target="#delete_bag" class="btn btn-white btn-sm m-t-10"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a> --}}
                    </div>
                </div>
            </td>
            <h4 class="user-name mt-2 mb-0 text-ellipsis"><a href="client-profile.html">{{$data->name}}</a></h4>
            <h5 class="user-name mt-1 mb-0 text-ellipsis"><a href="client-profile.html">ID: {{$data->bag_id}}</a></h5>
            <a href="#" data-bs-toggle="modal" data-bs-target="#edit_role{{$data->id}}"  class="btn btn-white btn-sm m-t-10"><i class="fa-solid fa-pencil m-r-5"></i> Edit</a>
            <a href="#" onclick="delete_modal({{$data->id}})" data-bs-toggle="modal" data-bs-target="#delete_bag" class="btn btn-white btn-sm m-t-10"><i class="fa-regular fa-trash-can m-r-5"></i> Delete</a>
        </div>
    </a>
    </div>

    <div id="edit_role{{$data->id}}" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-md">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Bag</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @if($data)
                    <div class="modal-body">
                        <form action="{{ route('franchise.bag.update', ['id' => $data->id]) }}" method="POST">
                            @csrf
                            <div class="input-block mb-3">
                                <label class="col-form-label">Bag Name <span class="text-danger">*</span></label>
                                <input name="name" class="form-control" value="{{$data->name}}" type="text">
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
   
    @endforeach
    @else
    <p>No result found</p>
    @endif
</div>


@php
    $service_type = request()->query('id');
@endphp

<!-- Add Role Modal -->
<div id="add_role_user" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Bag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('franchise.bag.store')}}" method="POST">
                    @csrf
                    <input type="text" hidden value="{{$code}}" name="barcodeCode">
                    <input type="text" hidden value="{{$service_type}}" name="service_type">
                    <div class="input-block mb-3">
                        <label class="col-form-label">Bag Name 0000000000 <span class="text-danger">*</span></label>
                        <input class="form-control" name="name" type="text" required>
                    </div>

                    <div class="col-md-6">
                        <select name="pickup-details" id="pickup-details">
                            <option value="">Select an option</option>
                            <option value="1">CMS </option>
                            <option value="2">Delivery Boy </option>
                        </select>
                    </div>

                    {{-- <div class="col-md-6">
                        <select name="pickup-details" id="pickup-details">
                            <option value="">Select an option</option>
                            @foreach($pickupDetails as $user)
                            <option value="{{$user->id}}">{{$user->name}} </option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="col-md-5 m-auto">
                        <label for="PickupAddress">Barcode <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <img  src="data:image/png;base64,{{ $barcode }}" alt="Barcode" style="height:50px;"/>
                            <p class="mt-2 text-center w-100">{{$code}}</p>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add Role Modal -->


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
        const deleteUrl = "{{ route('franchise.bag.delete', ['id' => ':id']) }}".replace(':id', id);
        $('#delete_button').attr('href', deleteUrl);
        $('#delete_modal').modal('show');
    }

$(document).ready(() => {
   
    const handleSearch = () => {
        let searchValue1 =  $('.input1').val();
        let searchValue2 =  $('.input2').val()
        if(searchValue1=='' && searchValue2=='')
        {
            return false;
        }
        let bagData = <?php echo json_encode($bag); ?>;
        let tocheckData=''
        let filteredData = bagData.filter(item => {
            if(searchValue1)
            {
                tocheckData=item.bag_id;
                return tocheckData.toLowerCase().includes(searchValue1.toLowerCase());
            }
            if(searchValue2)
            {
                tocheckData=item.name;
                return tocheckData.toLowerCase().includes(searchValue2.toLowerCase());
            }
        });

        const staffGridRow = $('.row.staff-grid-row');
        // Assuming staffGridRow is a valid jQuery or DOM element
        if (filteredData) {
        // Clear existing content
        staffGridRow.empty();

        filteredData.forEach(item => {
            const profileWidget = `
                <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3 d-flex">
                    <div class="profile-widget w-100" style="padding:10px">
                        <div class="dash-card-icon">
                            <i class="fa-solid fa-suitcase" style="color:#666363; font-size:40px;"></i>
                        </div>
                        <h4 class="user-name mt-2 mb-0 text-ellipsis">
                            <a href="client-profile.html">${item.name}</a>
                        </h4>
                        <h5 class="user-name mt-1 mb-0 text-ellipsis">
                            <a href="client-profile.html">ID: ${item.bag_id}</a>
                        </h5>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#edit_role${item.id}" class="btn btn-white btn-sm m-t-10">
                            <i class="fa-solid fa-pencil m-r-5"></i> Edit
                        </a>
                        <a href="#" onclick="delete_modal(${item.id})" data-bs-toggle="modal" data-bs-target="#delete_bag" class="btn btn-white btn-sm m-t-10">
                            <i class="fa-regular fa-trash-can m-r-5"></i> Delete
                        </a>
                    </div>
                </div>
                <div id="edit_role${item.id}" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content modal-md">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Bag</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="editForm${item.id}" method="POST">
                                    @csrf
                                    <div class="input-block mb-3">
                                        <label class="col-form-label">Bag Name <span class="text-danger">*</span></label>
                                        <input name="name" class="form-control" value="${item.name}" type="text">
                                    </div>
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            staffGridRow.append(profileWidget);

            // Setting form action via JavaScript
            const editForm = document.getElementById(`editForm${item.id}`);
            const route = `{{ route('franchise.bag.update', ['id' => '__ID__']) }}`;
            editForm.action = route.replace('__ID__', item.id);
        });
        }

    };
    $('.customSearch').on('click', handleSearch);

    $('.input1, .input2').on('keydown', (e) => {
    if (e.key === 'Enter' || e.keyCode === 13) { // Check for Enter key
        handleSearch();
    }
    });
});
</script>

@endpush
@endsection
