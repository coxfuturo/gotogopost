@extends('franchise.layouts.master')

@section('title') Payment @endsection

@section('content')

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<style>
    .personal-info li .text {
        color: #0c48be;
        display: block;
        overflow: hidden;
        width: 70%;
        float: left;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-6 d-flex">
        <div class="card profile-box flex-fill">
            <div class="card-header">
                <h5 class="card-title mb-0">Payment</h5>
            </div>
            <form id="payment-form" action="{{ route('franchise.parcel-payment.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="col-md-12 mb-3">
                        <label for="amount">Amount <span class="text-danger">*</span></label>
                        <div class="input-group mt-2">
                            <span class="input-group-text"><i class="fa fa-money-bill"></i></span>
                            <input type="number" class="form-control NumberValidate" id="amount" name="amount"
                                placeholder="Enter Amount" required min="1">
                            <div class="invalid-feedback">
                                Please Enter a Valid Amount
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="text-center">
                                <button type="button" id="pay-button" class="btn btn-primary mb-4">Pay Now</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('page-javascript')

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    document.getElementById('pay-button').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the form from submitting

        // Get the amount entered by the user
        const amountInput = document.getElementById('amount').value;

        // Validate the amount
        if (!amountInput || amountInput <= 0) {
            alert('Please enter a valid amount');
            return;
        }

        // Convert amount to paise (required by Razorpay)
        const amountInPaise = amountInput * 100;

        // Create a new Razorpay Checkout instance
        const options = {
            key: "{{ env('RAZORPAY_KEY') }}", // Razorpay key
            amount: amountInPaise, // Amount in paise
            currency: "INR",
            name: "gotogopost.com",
            description: "Parcel Payment",
            image: "https://gotogopost.com/website/images/logonewtransparent.png",
            theme: {
                color: "#ff7529"
            },
            method: {
                card: true, // Enable card payments
                netbanking: true, // Enable netbanking
                wallet: true, // Enable wallet payments
                upi: true, // Enable UPI
            },
            handler: function(response) {
                // Handle the payment success response
                const paymentForm = document.getElementById('payment-form');

                // Append payment details to the form
                const paymentIdInput = document.createElement('input');
                paymentIdInput.type = 'hidden';
                paymentIdInput.name = 'razorpay_payment_id';
                paymentIdInput.value = response.razorpay_payment_id;
                paymentForm.appendChild(paymentIdInput);

                const finalAmountInput = document.createElement('input');
                finalAmountInput.type = 'hidden';
                finalAmountInput.name = 'final_amount';
                finalAmountInput.value = amountInPaise;
                paymentForm.appendChild(finalAmountInput);

                // Submit the form to the server
                paymentForm.submit();
            },
            modal: {
                ondismiss: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Payment Cancelled',
                        confirmButtonText: 'Okay'
                    });
                }
            }
        };

        const rzp = new Razorpay(options);
        rzp.open();
    });
</script>

@endpush

@endsection