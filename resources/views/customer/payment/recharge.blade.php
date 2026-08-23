@extends('customer.layouts.master') 
@section('title') Payment & Recharge @endsection
 @section('content')

<style>
    .hradding i {
        font-size: 20px;
    }

    .hradding {
        font-size: 18px;
    }
    .button{
        background: #de0114;
        margin-bottom: 5px;
        color: #fff;
        text-align: center;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <span id="message" class="text-danger"></span>
        <div class="card">
            <div class="card-body">
                <!-- start row -->
                <div class="row">
                    <div class="col-sm-12 bg-dark p-2 text-white hradding">
                       <b><i class="la la-rocket" ></i> Advanced Contract Balance and Recharge</b>
                    </div>

                    <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                         <div class="card-body">
                          <h5 class="card-title">Primary card title</h5>
                          <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                       </div>
                       <a class="card-header button">
                          Recharge
                       </a>
                    </div>


                </div>
                <!-- end row -->
            </div>
        </div>
    </div>
</div>

@endsection
