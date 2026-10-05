@extends('admin.layouts.master')
@section('title') Barcode Uploads @endsection
@section('content')





<style>
    .select2-container {
        box-sizing: border-box;
        display: inline-block;
        margin: 0;
        position: relative;
        vertical-align: middle;
        width: 400px !important;
    }

    .input-block {
        display: flex;
        flex-direction: column
    }

    .select2-container--default .select2-selection--single .select2-selection__clear {
        cursor: pointer;
        float: right;
        font-weight: bold;
        height: 26px;
        margin-right: 20px;
        padding-right: 0px;
        display: none;
    }

    .form-focus .select2-container .select2-selection--single .select2-selection__rendered {
        padding-right: 30px;
        padding-left: 12px;
        padding-top: 3px;
    }

    .barcode-input-container{
        width: 450px;
        margin:auto;
    }

        @media screen and (max-width: 480px) {
            body {
                background-color: lightgreen;
            }

            .select2-container {
        box-sizing: border-box;
        display: inline-block;
        margin: 0;
        position: relative;
        vertical-align: middle;
        width: 300px !important;
    }

    .barcode-input-container{
        width: auto;
    }
        }

</style>


<div class="row justify-content-center">
    <div class="col-sm-12 col-md-6">
        <div class="card">
            <div class="card-body">

                <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                    <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item"><a href="#franchise" data-bs-toggle="tab" class="nav-link active">Business Associate</a></li>
                        <li class="nav-item"><a href="#cms" data-bs-toggle="tab" class="nav-link">CMS</a></li>
                        <li class="nav-item"><a href="#pph" data-bs-toggle="tab" class="nav-link">PPH</a></li>
                    </ul>
                </div>
                
                <div class="tab-content">
                
                    <!-- franchise start -->
                    <div id="franchise" class="pro-overview tab-pane fade show active">
                        <form action="{{route('admin.franchise-barcode-upload.store')}}" method="POST">
                            @csrf
                            <div class="d-flex align-items-center flex-column">
                                <div class="input-block mb-4 form-focus ">
                                    <label for="PickupName">Select Business Associate<span class="text-danger">*</span></label>
                                    <select name="franchise_id" class="floating">
                                        <option value=""> -- Select -- </option>
                                        @foreach($franchise as $user)
                                        <option value="{{$user->id}}">{{$user->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                
                            <div class="d-flex align-items-center flex-column">
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Select item <span class="text-danger">*</span></label>
                                    <select name="item" class="select" data-tags="true" data-placeholder="Select an option" required>
                                        <option value="">Select an option</option>
                                        <option value="bag">Bag</option>
                                        <option value="parcel">Parcel</option>
                                    </select>
                                </div>
                            </div>
                
                
                
                            <div class="d-flex align-items-center flex-column">
                                <div class="input-block mb-4 form-focus ">
                                    <label for="PickupName">Select Service<span class="text-danger">*</span></label>
                                    <select name="service_type" class="floating" name="service_type">
                                        <option value=""> -- Select -- </option>
                                        @foreach($serviceType as $key=>$value)
                                        <option value="{{$key}}">{{$value}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                
                
                
                
                
                
                            <div class="mb-4 mt-2 px-4 barcode-input-container">
                                <label for="PickupName">Enter No of Barcode Series<span class="text-danger">*</span></label>
                                <div class="input-group mt-1">
                                    <span class="input-group-text"><i class="fa fa-building"></i></span>
                                    <input type="text" class="form-control NumberValidate " maxlength="6" id="originPincode" name="seriesAmount" value="" placeholder="Enter Barcode series amount">
                                    <div class="invalid-feedback">
                                        Please provide pincode
                                    </div>
                                    <div class="invalid-feedback validerror-origin">
                                    </div>
                                </div>
                            </div>
                            <div class="input-block mb-3 mb-0 mt-3">
                                <div class="text-center">
                                    <button class="btn btn-primary"><span>Submit</span> <i class="fa-solid fa-paper-plane m-l-5"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- franchise end-->

                    <!-- cms start -->
                    <div class="tab-pane fade" id="cms">
                        <form action="{{route('admin.cms-barcode-upload.store')}}" method="POST">
                            @csrf
                            <div class="d-flex align-items-center flex-column">
                                <div class="input-block mb-4 form-focus ">
                                    <label for="PickupName">Select CMS<span class="text-danger">*</span></label>
                                    <select name="cms_id" class="floating">
                                        <option value=""> -- Select -- </option>
                                        @foreach($cms as $user)
                                        <option value="{{$user->id}}">{{$user->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex align-items-center flex-column mt-2">
                                <div class="input-block mb-4 form-focus ">
                                    <label for="PickupName">Select Service<span class="text-danger">*</span></label>
                                    <select name="service_type" class="floating" name="service_type">
                                        <option value=""> -- Select -- </option>
                                        @foreach($serviceType as $key=>$value)
                                        <option value="{{$key}}">{{$value}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                
                            <div class="mb-4 mt-2 px-4 barcode-input-container">
                                <label for="PickupName">Enter No of Barcode Series<span class="text-danger">*</span></label>
                                <div class="input-group mt-1">
                                    <span class="input-group-text"><i class="fa fa-building"></i></span>
                                    <input type="text" class="form-control NumberValidate " maxlength="6" id="originPincode" name="seriesAmount" value="" placeholder="Enter Barcode series amount">
                                    <div class="invalid-feedback">
                                        Please provide pincode
                                    </div>
                                    <div class="invalid-feedback validerror-origin">
                                    </div>
                                </div>
                            </div>
                            <div class="input-block mb-3 mb-0 mt-3">
                                <div class="text-center">
                                    <button class="btn btn-primary"><span>Submit</span> <i class="fa-solid fa-paper-plane m-l-5"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- cms end--> 

                    <!-- pph start -->
                    <div class="tab-pane fade" id="pph">
                        <form action="{{route('admin.pph-barcode-upload.store')}}" method="POST">
                            @csrf
                            <div class="d-flex align-items-center flex-column">
                                <div class="input-block mb-4 form-focus ">
                                    <label for="PickupName">Select PPH<span class="text-danger">*</span></label>
                                    <select name="pph_id" class="floating">
                                        <option value=""> -- Select -- </option>
                                        @foreach($pph as $user)
                                        <option value="{{$user->id}}">{{$user->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex align-items-center flex-column mt-2">
                                <div class="input-block mb-4 form-focus ">
                                    <label for="PickupName">Select Service<span class="text-danger">*</span></label>
                                    <select name="service_type" class="floating" name="service_type">
                                        <option value=""> -- Select -- </option>
                                        @foreach($serviceType as $key=>$value)
                                        <option value="{{$key}}">{{$value}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                
                            <div class="mb-4 mt-2 px-4 barcode-input-container">
                                <label for="PickupName">Enter No of Barcode Series<span class="text-danger">*</span></label>
                                <div class="input-group mt-1">
                                    <span class="input-group-text"><i class="fa fa-building"></i></span>
                                    <input type="text" class="form-control NumberValidate " maxlength="6" id="originPincode" name="seriesAmount" value="" placeholder="Enter Barcode series amount">
                                    <div class="invalid-feedback">
                                        Please provide pincode
                                    </div>
                                    <div class="invalid-feedback validerror-origin">
                                    </div>
                                </div>
                            </div>
                            <div class="input-block mb-3 mb-0 mt-3">
                                <div class="text-center">
                                    <button class="btn btn-primary"><span>Submit</span> <i class="fa-solid fa-paper-plane m-l-5"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- pph end--> 
                </div>   
            </div>
        </div>
    </div>
</div>



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