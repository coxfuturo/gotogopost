@extends('deliveryBoy.layouts.master')
@section('title') {{$title}} @endsection
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

                        <span>Payment Method: {{ strtoupper($parcelToUpdate->payment_method) }}</span>
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



@endpush
@endsection