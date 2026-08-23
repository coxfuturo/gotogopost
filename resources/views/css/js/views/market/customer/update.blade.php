@extends('market.layouts.master')
@section('title') Update Customer  @endsection
@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: '{{ session('error') }}',
        });
    @endif
</script>

    <div class="card">
    <div class="row card-body">
        <!-- Search Key Input -->

        <!-- Search Button -->
        <div class="col-sm-12 text-end">
                <a href="{{route('market.customer.index')}}"><button type="submit" class="btn marketGreenBackground" style="width:fit-content;float:right;">Back</button></a>
            
        </div>

        

    </div>
</div>

<style>

     label{
        background:#FF671F;
        color: #fff;
        width: 100%;
        border-radius: 10px;
    padding-left: 10px;
    } 
</style>
<form action="{{route('market.customer.update', $data->id)}}" method="post">
    @csrf
<div class="row">
    <div class="col-md-12">
        <span id="message" class="text-danger"></span>
        <div class="card">
            <div class="card-body">
                <div class="row">
                   <div class="col-sm-12 mb-3" >
                      <h3 class="pt-3 marketGreenColor" style="border-bottom: 1px solid #046A38">Customer Registration</h3>
                    
                  </div>


                    <div class="col-sm-6 mb-3">
                         <label for="name" class="form-label">Name/Company</label>
                         <input type="text" class="form-control" id="name" name="name" value="{{$data->name}}" placeholder="Enter Name." required>
                          @error('name')
                                 <small class="text-danger">{{ $message }}</small>
                          @enderror
                    </div>

                    <div class="col-sm-6 mb-3">
                         <label for="mobile" class="form-label">Mobile</label>
                         <input type="number" class="form-control" id="mobile" name="mobile" value="{{$data->phone}}" placeholder="Enter Mobile No." required>
                         @error('mobile')
                                 <small class="text-danger">{{ $message }}</small>
                          @enderror
                    </div>

                    <div class="col-sm-6 mb-3">
                         <label for="email" class="form-label">Email</label>
                         <input type="email" class="form-control" id="email" name="email" value="{{$data->email}}" placeholder="Enter Email id.">
                        
                    </div>

                     <div class="col-sm-6 mb-3">
                         <label for="register_type" class="form-label"> Partner Type</label>
                         <select class="form-select type" name="register_type" id="register_type" required>
                          <option value="proprietor" {{ $data->register_type == 'proprietor' ? 'selected' : '' }}>Proprietorship Firm</option>
                          <option value="partner" {{ $data->register_type == 'partner' ? 'selected' : '' }}>Partnership Firm</option>
                          <option value="private" {{ $data->register_type == 'private' ? 'selected' : '' }}>Private Limited</option>

                         </select>

                         @error('register_type')
                                 <small class="text-danger">{{ $message }}</small>
                          @enderror
                         
                    </div>

                    <div class="col-sm-6 mb-3">
                         <label for="gst_no" class="form-label">GST No</label>
                         <input type="text" class="form-control" id="gst_no" name="gst_no" value="{{$data->gst_no}}" placeholder="Enter GST No.">
                         
                    </div>

                    <div class="col-sm-6 mb-3">
                         <label for="state" class="form-label">State</label>
                         <input type="text" class="form-control" id="state" name="state" value="{{$data->state}}" placeholder="Enter State." required>
                         @error('state')
                                 <small class="text-danger">{{ $message }}</small>
                          @enderror
                    </div>

                    <div class="col-sm-6 mb-3">
                         <label for="city" class="form-label">City</label>
                         <input type="text" class="form-control" id="city" name="city" value="{{$data->city}}" placeholder="Enter City." required>
                         @error('city')
                                 <small class="text-danger">{{ $message }}</small>
                          @enderror
                    </div>


                    <div class="col-sm-6 mb-3">
                         <label for="pincode" class="form-label">Pincode</label>
                         <input type="number" class="form-control" id="pincode" name="pincode" value="{{$data->pincode}}" placeholder="Enter Pincode." required>
                         @error('pincode')
                                 <small class="text-danger">{{ $message }}</small>
                          @enderror
                    </div>

                     <div class="col-sm-6 mb-3">
                         <label for="franchise_id" class="form-label">Franchise</label>
                         <select name="franchise_id" class="form-select location_dropdown" required>
                             <option select disabled>Select Franchise</option>
                             @foreach($franchise as $list)
                                    <option value="{{ $list->id }}" {{ $list->id == $data->franchise_id ? 'selected' : '' }}>
                                     {{ $list->name }} - {{ $list->address }}, {{ $list->city }} - {{ $list->pincode }}
                                   </option>
                             @endforeach
                         </select>
                         @error('franchise_id')
                                 <small class="text-danger">{{ $message }}</small>
                          @enderror
                    </div>

                    <div class="col-sm-6 mb-3">
                         <label for="address" class="form-label">Address</label>
                         <textarea class="form-control" id="address" rows="3" name="address">{{$data->address}}</textarea>
                         @error('address')
                                 <small class="text-danger">{{ $message }}</small>
                          @enderror
                    </div>

                    <div class="col-sm-12 mb-3 text-center">
            
                         <button type="submit" class="btn marketGreenBackground" style="margin-top: 50px">Create</button>
                    </div>



                </div>
                   

            </div>
        </div>
    </div>
</div>
</form>


<style>
    @import url(https://fonts.googleapis.com/css?family=Open+Sans:700,300);
</style>
<?php 
//   echo"<script>
//     Swal.fire({
//             icon: 'error',
//             title: 'Oops!',
//             text: 'sdafa',
//         });
//   </script>";
?>


@push('page-javascript')
<script type="text/javascript">

            $(document).ready(function () {
                $("#city").on("keyup", function () {
                    let city = $(this).val();
                    var csrfToken = "{{ csrf_token() }}";

                    $.ajax({
                        url: "{{route('customer.get.location')}}",
                        type: "POST",
                        data: {
                            city: city,
                            _token: csrfToken, // token yahan hai
                        },
                        success: function (response) {
                        
                            $(".location_dropdown").html(response);
                        },
                        error: function (xhr) {
                            console.log(xhr.responseText);
                        },
                    });
                });
            });
        <!--End Filter Location -->
</script>




@endpush
@endsection