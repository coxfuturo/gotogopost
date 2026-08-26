@extends('website.layouts.master')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Payment Success',
        text: '{{ session('success') }}',
        confirmButtonText: 'Okay'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('website.payment.printpaymentHistory') }}",
                method: 'GET',
                data: {
                    id: {{ session('success') }},
                },
                success: function(response) {
                    var iframe = document.createElement('iframe');
                    iframe.style.position = 'absolute';
                    iframe.style.width = '0';
                    iframe.style.height = '0';
                    iframe.style.border = 'none';
                    document.body.appendChild(iframe);

                    iframe.contentDocument.open();
                    iframe.contentDocument.write(response.otherPageContent);
                    iframe.contentDocument.close();

                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();

                    document.body.removeChild(iframe);
                    preventDefault();
                }
            });
        }
    });
</script>
@endif

<!-- Scoped Custom CSS for Payment Page -->
<style>
/* ==========================================================================
   PAYMENT PAGE SCOPED REDESIGN STYLES
   All rules strictly wrapped inside .payment-page-wrapper to guarantee zero global leak
   ========================================================================== */

.payment-page-wrapper {
    font-family: 'Montserrat', sans-serif;
    color: #1e293b;
    background-color: #f8fafc;
    overflow-x: hidden;
}

/* 1. Top Hero Banner (Exclusive to Payment Page) */
.payment-page-wrapper .payment-page-top-hero {
    position: relative;
    width: 100%;
    height: 300px;
    overflow: hidden;
}

.payment-page-wrapper .payment-hero-bg {
    position: relative;
    width: 100%;
    height: 100%;
}

.payment-page-wrapper .payment-hero-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center 35%;
    filter: brightness(0.8) contrast(1.05);
    transition: transform 0.6s ease;
}

.payment-page-wrapper .payment-page-top-hero:hover .payment-hero-img {
    transform: scale(1.02);
}

.payment-page-wrapper .payment-hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(180deg, rgba(11, 19, 43, 0.4) 0%, rgba(11, 19, 43, 0.85) 100%);
    display: flex;
    align-items: center;
}

.payment-page-wrapper .payment-hero-content {
    color: #ffffff;
}

.payment-page-wrapper .payment-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: #ffffff;
    padding: 6px 18px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 12px;
}

.payment-page-wrapper .payment-hero-badge i {
    color: #10b981;
}

.payment-page-wrapper .payment-hero-title {
    font-size: 32px;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 12px;
    letter-spacing: -0.5px;
}

.payment-page-wrapper .payment-hero-breadcrumb {
    font-size: 14px;
    font-weight: 500;
    color: #cbd5e1;
}

.payment-page-wrapper .payment-hero-breadcrumb a {
    color: #ffffff;
    text-decoration: none;
    transition: color 0.2s ease;
}

.payment-page-wrapper .payment-hero-breadcrumb a:hover {
    color: #d9251d;
}

.payment-page-wrapper .payment-hero-breadcrumb i {
    margin: 0 8px;
    color: #94a3b8;
}

.payment-page-wrapper .payment-hero-breadcrumb .active-crumb {
    color: #d9251d;
    font-weight: 700;
}

/* 2. Main Payment Section */
.payment-page-wrapper .payment-main-section {
    padding: 75px 0;
    background-color: #f8fafc;
}

.payment-page-wrapper .payment-card {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
    padding: 36px 32px;
    height: 100%;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.payment-page-wrapper .payment-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: transparent;
    transition: background 0.3s ease;
}

.payment-page-wrapper .payment-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.1);
    border-color: rgba(217, 37, 29, 0.25);
}

.payment-page-wrapper .payment-card:hover::before {
    background: #d9251d;
}

.payment-page-wrapper .payment-card-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 26px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f1f5f9;
}

.payment-page-wrapper .header-icon-wrap {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: #fff5f5;
    border: 1px solid rgba(217, 37, 29, 0.15);
    color: #d9251d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.payment-page-wrapper .payment-card:hover .header-icon-wrap {
    background: #d9251d;
    color: #ffffff;
    transform: scale(1.05);
}

.payment-page-wrapper .header-icon-wrap.pay-icon {
    background: #f0fdf4;
    border-color: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.payment-page-wrapper .payment-card:hover .header-icon-wrap.pay-icon {
    background: #10b981;
    color: #ffffff;
}

.payment-page-wrapper .header-text h4 {
    font-family: 'Montserrat', sans-serif;
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 4px 0;
}

.payment-page-wrapper .header-text p {
    font-size: 13px;
    color: #64748b;
    margin: 0;
}

/* Bank Details Styling */
.payment-page-wrapper .bank-details-body {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin-bottom: 24px;
}

.payment-page-wrapper .bank-detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 18px;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #edf2f7;
    transition: all 0.2s ease;
}

.payment-page-wrapper .bank-detail-item:hover {
    background: #ffffff;
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}

.payment-page-wrapper .detail-label {
    font-size: 13.5px;
    font-weight: 600;
    color: #64748b;
}

.payment-page-wrapper .detail-value {
    font-size: 14.5px;
    font-weight: 700;
    color: #0f172a;
}

.payment-page-wrapper .detail-value.highlight-account {
    color: #0f172a;
    font-family: monospace;
    font-size: 16px;
    letter-spacing: 0.5px;
}

.payment-page-wrapper .detail-value.highlight-ifsc {
    color: #d9251d;
    font-family: monospace;
    font-size: 15px;
    letter-spacing: 0.5px;
}

.payment-page-wrapper .bank-card-footer {
    font-size: 12.5px;
    color: #64748b;
    background: #f1f5f9;
    padding: 12px 16px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.payment-page-wrapper .bank-card-footer i {
    color: #0284c7;
}

/* Payment Form Styling */
.payment-page-wrapper .input-label {
    font-size: 14px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 8px;
    display: block;
}

.payment-page-wrapper .required-star {
    color: #d9251d;
}

.payment-page-wrapper .amount-input-group {
    position: relative;
    margin-bottom: 20px;
}

.payment-page-wrapper .amount-input-group .currency-symbol {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
    z-index: 5;
}

.payment-page-wrapper .amount-input-group input#amount {
    height: 56px !important;
    padding-left: 44px !important;
    padding-right: 18px !important;
    border-radius: 12px !important;
    border: 1.5px solid #cbd5e1 !important;
    font-size: 19px !important;
    font-weight: 700 !important;
    color: #0f172a !important;
    background: #ffffff !important;
    transition: all 0.25s ease !important;
}

.payment-page-wrapper .amount-input-group input#amount:focus {
    border-color: #d9251d !important;
    box-shadow: 0 0 0 4px rgba(217, 37, 29, 0.12) !important;
    outline: none !important;
}

.payment-page-wrapper .payment-submit-btn {
    width: 100% !important;
    height: 54px !important;
    background: linear-gradient(135deg, #d9251d 0%, #b91c1c 100%) !important;
    border: none !important;
    border-radius: 12px !important;
    color: #ffffff !important;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 16px !important;
    font-weight: 800 !important;
    letter-spacing: 0.5px !important;
    cursor: pointer !important;
    transition: all 0.25s ease !important;
    box-shadow: 0 8px 24px rgba(217, 37, 29, 0.3) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 10px !important;
}

.payment-page-wrapper .payment-submit-btn:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 12px 28px rgba(217, 37, 29, 0.4) !important;
    background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%) !important;
}

.payment-page-wrapper .pay-security-note {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 22px;
    padding-top: 18px;
    border-top: 1px solid #f1f5f9;
    font-size: 13px;
    font-weight: 600;
    color: #64748b;
    text-align: center;
}

.payment-page-wrapper .pay-security-note i {
    color: #10b981;
    font-size: 16px;
}

.payment-page-wrapper .invalid-feedback {
    display: none;
    width: 100%;
    margin-top: .4rem;
    font-size: 13px;
    color: #ef4444;
    font-weight: 600;
}

/* Responsive Rules */
@media (max-width: 991px) {
    .payment-page-wrapper .payment-page-top-hero {
        height: 240px;
    }
    .payment-page-wrapper .payment-hero-title {
        font-size: 26px;
    }
    .payment-page-wrapper .payment-main-section {
        padding: 55px 0;
    }
    .payment-page-wrapper .payment-card {
        padding: 28px 24px;
    }
}

@media (max-width: 576px) {
    .payment-page-wrapper .payment-page-top-hero {
        height: 200px;
    }
    .payment-page-wrapper .payment-hero-title {
        font-size: 22px;
    }
    .payment-page-wrapper .payment-main-section {
        padding: 40px 0;
    }
    .payment-page-wrapper .payment-card {
        padding: 24px 20px;
    }
    .payment-page-wrapper .bank-detail-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }
}
</style>

<div class="payment-page-wrapper">
    <!-- Top Horizontal Hero Banner (Exclusive to Payment Page) -->
    <div class="payment-page-top-hero">
        <div class="payment-hero-bg">
            <img src="https://images.unsplash.com/photo-1563013544-824ae1b704d3?auto=format&fit=crop&w=2000&q=85" alt="GOTOGO POST Secure Online Payment Banner" class="payment-hero-img">
            <div class="payment-hero-overlay">
                <div class="container">
                    <div class="payment-hero-content">
                        <span class="payment-hero-badge"><i class="fa fa-lock"></i> SECURE PAYMENT GATEWAY</span>
                        <h1 class="payment-hero-title">Online  Payment</h1>
                        <nav class="payment-hero-breadcrumb">
                            <a href="{{ route('website.index') }}">Home</a>
                            <i class="fa fa-angle-right"></i>
                            <span class="active-crumb">Payment</span>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Payment Section -->
    <section class="payment-main-section">
        <div class="container">
            <div class="row">
                <!-- Left Column: Bank Account Details Card -->
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="payment-card bank-details-card">
                        <div class="payment-card-header">
                            <div class="header-icon-wrap">
                                <i class="fa fa-university"></i>
                            </div>
                            <div class="header-text">
                                <h4>Company Account Details</h4>
                                <p>NEFT / RTGS / IMPS / NetBanking Information</p>
                            </div>
                        </div>

                        <div class="bank-details-body">
                            <div class="bank-detail-item">
                                <span class="detail-label">Bank Name</span>
                                <span class="detail-value">Axis Bank Limited</span>
                            </div>
                            <div class="bank-detail-item">
                                <span class="detail-label">Branch Name</span>
                                <span class="detail-value">Chittaranjan Park, New Delhi</span>
                            </div>
                            <div class="bank-detail-item">
                                <span class="detail-label">Account No.</span>
                                <span class="detail-value highlight-account">924020029877237</span>
                            </div>
                            <div class="bank-detail-item">
                                <span class="detail-label">IFSC Code</span>
                                <span class="detail-value highlight-ifsc">UTIB0000430</span>
                            </div>
                        </div>

                        <div class="bank-card-footer">
                            <i class="fa fa-info-circle"></i> Use these bank details for direct wire transfers & NEFT/RTGS payments.
                        </div>
                    </div>
                </div>

                <!-- Right Column: Online Payment Form Card -->
                <div class="col-lg-6 col-md-6 mb-4">
                    <div class="payment-card form-card">
                        <div class="payment-card-header">
                            <div class="header-icon-wrap pay-icon">
                                <i class="fa fa-credit-card"></i>
                            </div>
                            <div class="header-text">
                                <h4>Make Online Payment</h4>
                                <p>Instant Payment via Cards, UPI, NetBanking</p>
                            </div>
                        </div>

                        <form id="payment-form" method="POST">
                            @csrf
                            <div class="payment-form-body">
                                <label class="input-label" for="amount">Enter Payment Amount <span class="required-star">*</span></label>
                                <div class="amount-input-group">
                                    <span class="currency-symbol">₹</span>
                                    <input type="number" class="form-control NumberValidate" id="amount" placeholder="Enter Amount*">
                                </div>
                                <div class="invalid-feedback">
                                    Please Enter a Valid Amount
                                </div>

                                <div class="submit-wrap">
                                    <button value="Pay Now" id="pay-button" class="fh-btn payment-submit-btn" type="submit">
                                        <i class="fa fa-lock"></i> Pay Now Securely
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="pay-security-note">
                            <i class="fa fa-shield"></i> 100% Encrypted & Secure Transaction via Razorpay (UPI, Cards, NetBanking)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    const RAZORPAY_KEY = "{{ env('RAZORPAY_KEY') }}";
    console.log("RAZORPAY_KEY =>", RAZORPAY_KEY);
</script>

<script>
    $(document).ready(function() {
        $('#pay-button').on('click', function(e) {
            e.preventDefault();
  
            const amountInput = $('#amount').val();

            if (!amountInput || amountInput <= 0) {
                alert('Please enter a valid amount');
                return;
            }
            
            const amountInPaise = amountInput * 100;
            
            const options = {
                key: "rzp_live_hZ7MLP0RaGm3Dx",
                amount: amountInPaise,
                currency: "INR",
                name: "gotogopost.com",
                description: "Franchise Payment",
                image: "https://gotogopost.com/website/images/logonewtransparent.png",
                theme: {
                    color: "#ff7529"
                },
                method: {
                    card: true,
                    netbanking: true,
                    wallet: true,
                    upi: true,
                },
                handler: function(response) {
                    window.location.href = "{{ route('website.payment.store') }}?razorpay_payment_id=" + response.razorpay_payment_id + "&amount=" + amountInput;
                },
                modal: {
                    ondismiss: function() {
                        Swal.fire({
                            icon: 'info',
                            title: 'Payment Cancelled',
                            text: 'It looks like you cancelled the payment. Please try again.',
                            confirmButtonText: 'Okay'
                        });
                    }
                }
            };

            console.log(options);
            const rzp = new Razorpay(options);
            rzp.open();
        });
    });
</script>
@endsection
