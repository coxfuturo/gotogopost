@extends('customer.layouts.master') 
@section('title') Payment & Recharge @endsection
 @section('content')

<style>
    .icon i {
        font-size: 30px;
        background: #fff;
        border: 1px silid #000;
        border-radius: 50%;
        margin-top: 10px;
        padding: 20px;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <span id="message" class="text-danger"></span>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{route('customer.payment.recharge.index')}}">
                        <div class="card mb-3 p-3" style="background: linear-gradient(to right, rgb(4, 102, 30), rgb(10, 140, 44));">
                            <div class="row g-0">
                                <div class="col-md-4 text-center">
                                    <div class="icon">
                                        <i class="la la-rocket"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title text-white">Recharge</h5>
                                        <p class="card-text text-white">You can recharge your franchise Here</p>
                                        <!-- <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>

                    <div class="col-sm-6">

                        <div class="card mb-3 p-3" style="background: linear-gradient(to right, rgb(156, 49, 6), rgb(196, 65, 8));">
                            <div class="row g-0">
                                <div class="col-md-4 text-center">
                                    <div class="icon">
                                        <i class="la la-rocket"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title text-white">Transaction History</h5>
                                        <p class="card-text text-white">View All Debit transactions from Wallet</p>
                                        <!-- <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
