@extends('admin.layouts.master') @section('title') Gotogo Pincode @endsection @section('content') @if ($errors->any())

<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)

        <li>{{ $error }}</li>

        @endforeach
    </ul>
</div>

@endif

<style>
    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        display: block;
    }
</style>
 <form method="get" action="{{route('admin.gotogo.pincode.index')}}">
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <label for="generated_id">Select State</label>
                        <div class="input-group">
                            <select class="select-my" id="state" name="state" required>
                                <option selected disabled>-- Select State --</option>
                                <option value="Andaman">Andaman and Nicobar Islands</option>
                                <option value="Andhra Pradesh">Andhra Pradesh</option>
                                <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                <option value="Assam">Assam</option>
                                <option value="Bihar">Bihar</option>
                                <option value="Chandigarh">Chandigarh</option>
                                <option value="Chhattisgarh">Chhattisgarh</option>
                                <option value="Dadra and Nagar Haveli and Daman and Diu">Dadra and Nagar Haveli and Daman and Diu</option>
                                <option value="Delhi">Delhi</option>
                                <option value="Goa">Goa</option>
                                <option value="Gujarat">Gujarat</option>
                                <option value="Haryana">Haryana</option>
                                <option value="Himachal Pradesh">Himachal Pradesh</option>
                                <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                <option value="Jharkhand">Jharkhand</option>
                                <option value="Karnataka">Karnataka</option>
                                <option value="Kerala">Kerala</option>
                                <option value="Ladakh">Ladakh</option>
                                <option value="Lakshadweep">Lakshadweep</option>
                                <option value="Madhya Pradesh">Madhya Pradesh</option>
                                <option value="Maharashtra">Maharashtra</option>
                                <option value="Manipur">Manipur</option>
                                <option value="Meghalaya">Meghalaya</option>
                                <option value="Mizoram">Mizoram</option>
                                <option value="Nagaland">Nagaland</option>
                                <option value="Odisha">Odisha</option>
                                <option value="Puducherry">Puducherry</option>
                                <option value="Punjab">Punjab</option>
                                <option value="Rajasthan">Rajasthan</option>
                                <option value="Sikkim">Sikkim</option>
                                <option value="Tamil Nadu">Tamil Nadu</option>
                                <option value="Telangana">Telangana</option>
                                <option value="Tripura">Tripura</option>
                                <option value="Uttar Pradesh">Uttar Pradesh</option>
                                <option value="Uttarakhand">Uttarakhand</option>
                                <option value="West Bengal">West Bengal</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-3">
                        <label for="generated_id">Select District</label>
                        <div class="input-group">
                            <select class="form-control select-my" id="district" name="district" >
                                <option selected disabled>-- Select State --</option>
                               
                            </select>
                        </div>
                    </div>

                    <div class="col-3">
                        <label for="generated_id">Pincode</label>
                        <div class="input-group">
                            <input type="number" name="pincode" placeholder="Pincode" class="form-control">
                        </div>
                    </div>

                    <div class="col-3">
                        <label for="generated_id"></label>
                        <div class="input-group">
                            <button type="submit" class="btn btn-primary">Find</button>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
</form>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped custom-table datatable">
                        <thead>
                            <tr>
                                <th>SN#</th>
                                <th>State</th>
                                <th>District</th>
                                <th>Pincode</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($pincodes as $i => $list)

                            <tr>
                                <td>{{$i+1}}</td>
                                <td>{{$list->state_name}}</td>

                                <td>{{$list->district}}</td>
                                <td>{{$list->pincode}}</td>

                                <td>
                                    <div class="dropdown action-label">
                                        @if($list->status == 1)

                                        <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>

                                        @else

                                        <a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>

                                        @endif

                                        <div class="dropdown-menu active-inactive-menu-{{ $list->id }}">
                                            <a class="dropdown-item" onclick="status_update('{{$list->id}}','1')" href="#"><i class="fa-regular fa-circle-dot text-success"></i> Active</a>

                                            <a class="dropdown-item" onclick="status_update('{{$list->id}}','0')" href="#"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive</a>
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

@push('page-javascript')

<script type="text/javascript">
    $("#state").on("change", function () {
        var state = $(this).val();

        $.ajax({
            type: "GET",
            data: { state: state, _token: "{{ csrf_token() }}" },
            url: "{{ route('admin.gotogo.getState') }}",

            success: function (data) {
                $("#district").html(data.html);
            },
        });
    });

    function status_update(id, status) {
        var className = "active-inactive-menu-" + id;

        $.ajax({
            url: "{{route('admin.gotogo.pincode.status')}}",

            type: "POST",

            data: {
                _token: "{{ csrf_token() }}",

                id: id,

                status: status,
            },

            success: function (data) {
                console.log("reached here", data);

                if (data.success == true) {
                    Swal.fire("Success!", "Status updated", "success");

                    if (data.status == 0) {
                        var newElement = $('<a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-danger"></i> Inactive </a>');

                        $("." + className)
                            .prev()
                            .replaceWith(newElement);
                    } else {
                        var newElement = $('<a href="#" class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-regular fa-circle-dot text-success"></i> Active </a>');

                        $("." + className)
                            .prev()
                            .replaceWith(newElement);
                    }
                } else {
                    Swal.fire("Error!", "Something went wrong", "error");
                }
            },
        });
    }
</script>

@endpush @endsection
