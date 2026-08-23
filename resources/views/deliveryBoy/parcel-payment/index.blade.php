@extends('deliveryBoy.layouts.master')
@section('title') {{$title}} 
@endsection
@section('content')


<style>
    .account-page .main-wrapper {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: auto;
}

.account-page .main-wrapper .account-content .account-box .account-btn {
    background: #ff9b44;
    background: linear-gradient(to right, #ff9b44 0%, #fc6075 100%);
    border: 0;
    display: block;
    font-size: 22px;
    width: 50%;
    border-radius: 4px;
    padding: 10px 26px;
    margin: auto;
}

.account-page .main-wrapper .account-content .account-box .otp-wrap .otp-input {
    background-color: #ffffff;
    border: 1px solid #e3e3e3;
    display: inline-block;
    font-size: 24px;
    font-weight: 500;
    height: 50px;
    line-height: 29px;
    margin-right: 15px;
    text-align: center;
    width: 50px;
    border-radius: 4px;
}

.account-page .main-wrapper .account-content .account-box .account-wrapper {
    padding: 20px;
}
</style>

<div class="account-page">

	<!-- Main Wrapper -->
	<div class="main-wrapper">
		<div class="account-content">
			<div class="container">
				<div class="account-box">
					<div class="account-wrapper">
                        <span>Payment Method: {{ strtoupper($parcel->payment_method) }}</span>
                        <h3 class="account-title">OTP</h3>
						<p class="account-subtitle">Verification your account</p>
                        <form action="{{ route('deliveryBoy.bag.verifyOtp', ['service_type' => request()->query('service_type'), 'barcode_no' => request()->query('barcode_no')]) }}" method="POST">
                            @csrf
                            <div class="otp-wrap">
                                <input type="text" placeholder="0" maxlength="1" class="otp-input" name="otp[]">
                                <input type="text" placeholder="0" maxlength="1" class="otp-input" name="otp[]">
                                <input type="text" placeholder="0" maxlength="1" class="otp-input" name="otp[]">
                                <input type="text" placeholder="0" maxlength="1" class="otp-input" name="otp[]">
							</div>
							<div class="input-block mb-4 text-center">
								<button class="btn btn-primary account-btn" type="submit">Enter</button>
							</div>
							<div class="account-footer">
								<p>Not yet received? <a href="{{ route('deliveryBoy.bag.sendOtp', ['service_type' => request()->query('service_type'), 'barcode_no' => request()->query('barcode_no'),'id' => request()->query('service_type')]) }}">Resend OTP</a></p>
							</div>
                        </form>
            
					</div>
				</div>
			</div>
		</div>
	</div>



</div>



@push('page-javascript')

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    // Razorpay Payment Handling

    const amount  = @json($parcel->payment_amount); 
    const amountInPaise = amount*100;

    // Query parameters ko JavaScript me fetch karne ka sahi tarika
    const urlParams = new URLSearchParams(window.location.search);
    const service_type = urlParams.get('service_type');
    const barcode_no = urlParams.get('barcode_no');

    const options = {
    key: "{{ env('RAZORPAY_KEY') }}", // Razorpay key
    amount: amountInPaise, // Amount in paise
    currency: "INR",
    name: "gotogopost.com",
    description: "Parcel Payment",
    image: "https://gotogopost.com/website/images/logonewtransparent.png",
    theme: {
        color: "#ff7529",
    },
    handler: function (response) {
        const paymentData = {
            razorpay_payment_id: response.razorpay_payment_id,
            final_amount: amount,
            service_type: service_type, 
            barcode_no: barcode_no,
        };

        // Sending payment data using $.ajax()
        $.ajax({
            url: "{{ route('deliveryBoy.parcel-payment.store') }}", 
            type: "POST",
            data: JSON.stringify(paymentData),
            contentType: "application/json",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            success: function (paymentResponse) {
                console.log(paymentResponse);
                if (paymentResponse.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Payment Successful",
                        text: paymentResponse.message,
                        confirmButtonText: "Okay",
                    }).then(() => {
                        window.location.href = "{{ route('deliveryBoy.bag.showAllParcelToDeliver') }}?service_type=" 
                            + encodeURIComponent(service_type) + "&barcode_no=" + encodeURIComponent(barcode_no);
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Payment Failed",
                        text: `${paymentResponse.message || "An error occurred while processing the payment."}`,
                        confirmButtonText: "Okay",
                    }).then(() => {
                        window.location.href = "{{ route('deliveryBoy.bag.verifyOtp') }}?service_type=" 
                            + encodeURIComponent(service_type) + "&barcode_no=" + encodeURIComponent(barcode_no);
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "An error occurred while processing the payment.",
                    confirmButtonText: "Okay",
                }).then(() => {
                    window.location.href = "{{ route('deliveryBoy.bag.verifyOtp') }}?service_type=" 
                        + encodeURIComponent(service_type) + "&barcode_no=" + encodeURIComponent(barcode_no);
                });
                console.error("Payment AJAX request failed:", error);
            },
        });
    },
    modal: {
        ondismiss: function () {
            Swal.fire({
                icon: "error",
                text: "Payment Cancelled.",
                confirmButtonText: "Okay",
            }).then(() => {
                window.location.href = "{{ route('deliveryBoy.bag.verifyOtp') }}?service_type=" 
                        + encodeURIComponent(service_type) + "&barcode_no=" + encodeURIComponent(barcode_no);
            });
        },
    },
    };

    const rzp = new Razorpay(options);
    rzp.open();
</script>



@endpush
@endsection


















































