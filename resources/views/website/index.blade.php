@extends('website.layouts.master')


@section('content')

{{-- section one start --}}

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>



<style>
/* ==========================================================================
   GOTOGO POST - MASTER HOME PAGE DESIGN SYSTEM
   ========================================================================== */

:root {
    --primary-red: #d9251d;
    --primary-red-hover: #b91c1c;
    --dark-navy: #0b132b;
    --slate-dark: #0f172a;
    --charcoal: #1e293b;
    --slate-muted: #64748b;
    --slate-light: #94a3b8;
    --bg-light: #f8fafc;
}

body {
    font-family: 'Montserrat', sans-serif !important;
    color: var(--charcoal);
    background-color: #ffffff;
    overflow-x: hidden !important;
}

/* Hero Banner & Slider Container */
/* =========================================
   TRACK ORDER IMAGE BANNER
   ========================================= */

.track-order-top {
    position: relative;
    width: 100%;
    height: 450px;

    overflow: hidden;

    background: #ffffff;
}

.track-order-top .slick-slider {
    width: 100%;
    height: 100%;

    margin: 0 auto;
    position: relative;

    overflow: hidden;
}

.track-order-top .slick-slide {
    width: 100%;
    height: 450px !important;
    margin: 0;
    padding: 0;
    display: flex !important;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background: #ffffff;
}

.track-order-top .slick-slide img {
    width: 100% !important;
    height: 100% !important;
    max-width: 100%;
    max-height: 100%;
    display: block !important;
    object-fit: cover !important;
    object-position: center center !important;
    padding: 0 !important;
    margin: 0 !important;
    filter: brightness(0.95) contrast(1.03);
    transition: filter 0.5s ease;
}


/* =========================================
   TABLET
   ========================================= */

@media (max-width: 991px) {

    .track-order-top {
        height: 380px;
    }

    .track-order-top .slick-slide {
        height: 380px !important;
    }

    .track-order-top .slick-slide img {
        width: 100% !important;
        height: 100% !important;
    }
}


/* =========================================
   MOBILE
   ========================================= */

@media (max-width: 576px) {

    .track-order-top {
        height: 320px;
    }

    .track-order-top .slick-slide {
        height: 320px !important;
    }

    .track-order-top .slick-slide img {
        width: 100% !important;
        height: 100% !important;
    }
}
/* Welcome Header & Floating Action Buttons */
.welcome-header-wrapper {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    position: relative;
    max-width: 1200px;
    margin: 0 auto;
}

.welcome-header-wrapper .fh-section-title {
    flex: 1;
    text-align: center;
    margin: 0;
}

.welcome-header-wrapper .fh-section-title h2 {
    margin-top: 0;
    margin-bottom: 0;
    padding-bottom: 20px;
}

.welcome-header-wrapper .track-order-btn-wrap {
    position: relative;
    flex-shrink: 0;
    margin-top: -3px;
}

.shipNowButton {
    min-width: 175px;
    height: 48px;
    border: none !important;
    border-radius: 10px !important;
    background-color: var(--primary-red) !important;
    background-image: linear-gradient(135deg, #e31837 0%, #c8102e 100%) !important;
    color: #ffffff !important;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 8px 24px rgba(217, 37, 29, 0.35) !important;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    overflow: hidden;
    flex-shrink: 0;
    margin-top: -3px;
}

.shipNowButton::before {
    content: "PICKUP ENQUIRY";
    color: #ffffff;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.shipNowButton:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 12px 28px rgba(217, 37, 29, 0.45) !important;
    background-color: var(--primary-red-hover) !important;
}

.sliderBtn {
    min-width: 175px;
    height: 48px;
    border-radius: 10px !important;
    background-color: var(--primary-red) !important;
    background-image: linear-gradient(135deg, #e31837 0%, #c8102e 100%) !important;
    color: #ffffff !important;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px !important;
    font-weight: 700 !important;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border: none !important;
    box-shadow: 0 8px 24px rgba(217, 37, 29, 0.35) !important;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    padding: 0 20px !important;
}

.sliderBtn:hover {
    background-color: var(--primary-red-hover) !important;
    color: #ffffff !important;
    transform: translateY(-3px) !important;
    box-shadow: 0 12px 28px rgba(217, 37, 29, 0.45) !important;
}

/* Track Order Popup Form Card */
.sliderformshow {
    width: 360px;
    padding: 24px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.22);
    position: absolute;
    right: 0;
    bottom: calc(100% + 12px);
    border: 1px solid rgba(0, 0, 0, 0.08);
    transform: translateY(20px) scale(0.95);
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1050;
}

.sliderformshow.show-popup,
.sliderformshow.active,
.sliderformshow[style*="rotateX(0deg)"] {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
    transform: translateY(0px) scale(1) !important;
}

@media (max-width: 1199px) {
    .welcomesec .fh-section-title h2 {
        font-size: 28px !important;
    }
    .welcome-header-wrapper .shipNowButton,
    .welcome-header-wrapper .sliderBtn {
        min-width: 160px;
        height: 46px;
        font-size: 13px !important;
    }
    .welcome-header-wrapper .shipNowButton::before {
        font-size: 13px;
    }
}

@media (max-width: 991px) {
    .welcomesec .fh-section-title h2 {
        font-size: 24px !important;
    }
    .welcome-header-wrapper {
        gap: 12px;
    }
    .welcome-header-wrapper .shipNowButton,
    .welcome-header-wrapper .sliderBtn {
        min-width: 145px;
        height: 44px;
        font-size: 12px !important;
        padding: 0 10px !important;
    }
    .welcome-header-wrapper .shipNowButton::before {
        font-size: 12px;
    }
}

@media (max-width: 767px) {
    .welcome-header-wrapper {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        gap: 14px;
    }
    .welcome-header-wrapper .fh-section-title {
        width: 100%;
        order: 1;
        margin-bottom: 5px;
    }
    .welcome-header-wrapper .shipNowButton {
        order: 2;
        min-width: 145px;
        height: 44px;
        margin-top: 0;
    }
    .welcome-header-wrapper .track-order-btn-wrap {
        order: 3;
        margin-top: 0;
    }
    .welcome-header-wrapper .sliderBtn {
        min-width: 145px;
        height: 44px;
    }
    .sliderformshow {
        width: calc(100vw - 30px) !important;
        max-width: 340px !important;
        left: 50% !important;
        right: auto !important;
        bottom: calc(100% + 10px) !important;
        transform: translateX(-50%) translateY(20px) scale(0.95);
    }
    .sliderformshow.show-popup,
    .sliderformshow.active,
    .sliderformshow[style*="rotateX(0deg)"] {
        transform: translateX(-50%) translateY(0px) scale(1) !important;
    }
}

.custom-close {
    position: absolute;
    top: 12px;
    right: 14px;
    font-size: 16px;
    background: var(--primary-red) !important;
    color: white !important;
    border: none;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: transform 0.2s ease, background 0.2s ease;
    z-index: 10;
}

.custom-close:hover {
    transform: scale(1.1);
    background: var(--primary-red-hover) !important;
}

.sliderformshow label {
    font-family: 'Montserrat', sans-serif;
    font-size: 15px;
    font-weight: 700;
    color: var(--charcoal);
    margin-bottom: 8px;
    display: block;
    text-align: left;
}

.sliderformshow input {
    width: 100%;
    height: 46px;
    padding: 10px 16px;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    font-size: 15px;
    color: var(--charcoal);
    transition: all 0.2s ease;
    box-shadow: none;
}

.sliderformshow input:focus {
    border-color: var(--primary-red);
    outline: none;
    box-shadow: 0 0 0 3px rgba(217, 37, 29, 0.12);
}

.sliderformshow .btn-primary {
    width: 100%;
    height: 46px;
    margin-top: 18px;
    font-size: 15px;
    font-weight: 700;
    border-radius: 8px;
    background-color: var(--primary-red) !important;
    color: #ffffff !important;
    border: none !important;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(217, 37, 29, 0.25);
    transition: all 0.25s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.sliderformshow .btn-primary:hover {
    background-color: var(--primary-red-hover) !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(217, 37, 29, 0.35);
}

/* Welcome Section (.welcomesec) */
.welcomesec {
    padding: 80px 0;
    background-color: var(--bg-light);
}

.welcomesec .fh-section-title h2 {
    font-family: 'Montserrat', sans-serif;
    font-size: 34px;
    font-weight: 800;
    color: var(--slate-dark);
    letter-spacing: -0.5px;
}

.main-color {
    color: var(--primary-red) !important;
}

.haeadingpara {
    font-size: 17px;
    color: var(--slate-muted);
    max-width: 750px;
    margin: 0 auto 50px auto;
    line-height: 1.65;
}

.welcomesec .fh-icon-box {
    background: #ffffff;
    padding: 34px 28px;
    border-radius: 16px;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(0, 0, 0, 0.06);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
}

.welcomesec .fh-icon-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: transparent;
    transition: background 0.3s ease;
}

.welcomesec .fh-icon-box:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 38px rgba(0, 0, 0, 0.08);
    border-color: rgba(217, 37, 29, 0.2);
}

.welcomesec .fh-icon-box:hover::before {
    background: var(--primary-red);
}

.welcomesec .fh-icon-box .fh-icon img {
    height: 85px !important;
    width: auto;
    object-fit: contain;
    margin-bottom: 20px;
    transition: transform 0.3s ease;
}

.welcomesec .fh-icon-box:hover .fh-icon img {
    transform: scale(1.05);
}

.welcomesec .fh-icon-box .box-title a {
    font-family: 'Montserrat', sans-serif;
    font-size: 20px !important;
    font-weight: 700 !important;
    color: var(--slate-dark) !important;
    text-decoration: none !important;
    transition: color 0.25s ease;
}

.welcomesec .fh-icon-box:hover .box-title a {
    color: var(--primary-red) !important;
}

.welcomesec .fh-icon-box .desc p {
    font-size: 14px;
    color: var(--slate-muted);
    line-height: 1.65;
    margin-top: 12px;
}

/* Mobile App Compact Horizontal Banner Section (.homecounts) */
.homecounts.compact-app-banner {
    background: transparent !important;
    padding: 30px 0 !important;
    position: relative;
}

.app-banner-card {
    background: linear-gradient(135deg, #0b132b 0%, #0f172a 60%, #1c2541 100%) !important;
    border-radius: 16px !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    border-left: 5px solid #ff0000 !important;
    padding: 28px 36px !important;
    box-shadow: 0 14px 40px rgba(11, 19, 43, 0.22) !important;
    position: relative !important;
    overflow: hidden !important;
}

.app-banner-title {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 21px !important;
    font-weight: 800 !important;
    color: #ffffff !important;
    margin: 0 0 8px 0 !important;
    line-height: 1.35 !important;
    letter-spacing: 0.3px !important;
}

.app-banner-desc {
    font-size: 14.5px !important;
    color: #cbd5e1 !important;
    margin: 0 0 18px 0 !important;
    line-height: 1.5 !important;
}

.download-buttons {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    margin: 0 !important;
}

.download-buttons a {
    display: inline-block !important;
    margin: 0 !important;
    transition: transform 0.25s ease, filter 0.25s ease !important;
}

.download-buttons a:hover {
    transform: translateY(-2px) scale(1.03) !important;
    filter: drop-shadow(0 4px 12px rgba(0,0,0,0.4)) !important;
}

.download-buttons img {
    height: 40px !important;
    width: auto !important;
}

.app-banner-img-wrap {
    display: flex !important;
    justify-content: flex-end !important;
    align-items: center !important;
    height: 100% !important;
}

.app-banner-img {
    max-height: 145px !important;
    width: auto !important;
    max-width: 100% !important;
    object-fit: cover !important;
    border-radius: 12px !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4) !important;
    border: 2px solid rgba(255, 255, 255, 0.18) !important;
    transition: transform 0.3s ease !important;
}

.app-banner-img:hover {
    transform: scale(1.03) !important;
}

@media (max-width: 991px) {
    .app-banner-card {
        padding: 22px 26px !important;
    }
    .app-banner-title {
        font-size: 19px !important;
    }
    .app-banner-img {
        max-height: 125px !important;
    }
}

@media (max-width: 767px) {
    .app-banner-card {
        padding: 20px 20px !important;
    }
    .app-banner-content {
        text-align: center !important;
    }
    .app-banner-title {
        font-size: 17px !important;
        text-align: center !important;
    }
    .app-banner-desc {
        font-size: 13.5px !important;
        text-align: center !important;
    }
    .download-buttons {
        justify-content: center !important;
    }
    .app-banner-img-wrap {
        justify-content: center !important;
        margin-top: 18px !important;
    }
    .app-banner-img {
        max-height: 130px !important;
    }
}

/* Why Choose Us & Price Inquiry Section (.whychoose-1) */
.whychoose-1 {
    padding: 85px 0;
    background-color: #ffffff;
}

.whychoose-1 .fh-section-title h2 {
    font-family: 'Montserrat', sans-serif;
    font-size: 30px;
    font-weight: 800;
    color: var(--slate-dark);
    margin-bottom: 30px;
}

.whychoose-1 .fh-icon-box.style-2 {
    margin-bottom: 24px;
    padding-left: 20px;
    position: relative;
}

.whychoose-1 .fh-icon-box.style-2 .box-title span {
    font-family: 'Montserrat', sans-serif;
    font-size: 18px;
    font-weight: 700;
    color: var(--slate-dark);
}

.whychoose-1 .fh-icon-box.style-2 .desc p {
    font-size: 14px;
    color: var(--slate-muted);
    line-height: 1.6;
}

/* Price Inquiry Rate Calculator Widget (.quofrm1 & #price-upper-div) */
.whychoose-1 .quofrm1 {
    padding: 0 !important;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
    border: 1px solid rgba(0, 0, 0, 0.08);
}

@media (min-width: 768px) {
    .whychoose-1 .quofrm1 {
        transform: translateY(20px);
    }
}

.price-inquiry-div {
    background: var(--primary-red) !important;
    padding: 20px 24px !important;
    margin: 0 !important;
}

.price-inquiry-div h2 {
    color: #ffffff !important;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 22px !important;
    font-weight: 800 !important;
    letter-spacing: 1px;
    margin: 0 !important;
    text-transform: uppercase;
}

#price-upper-div {
    background: #ffffff !important;
    padding: 30px 28px !important;
}

#price-form .field {
    margin-bottom: 16px;
}

#price-form select,
#services.custom-dropdown {
    width: 100% !important;
    height: 44px !important;
    background-color: #ffffff !important;
    color: #333333 !important;
    border: 1px solid #d9d9d9 !important;
    border-radius: 4px !important;
    font-size: 14px !important;
    padding: 10px 12px !important;
    font-family: 'Montserrat', sans-serif !important;
    box-shadow: none !important;
    outline: none !important;
    transition: border-color 0.2s ease, box-shadow 0.2s ease !important;
    cursor: pointer !important;
}

#price-form select:focus,
#services.custom-dropdown:focus {
    border-color: var(--primary-red, #ff0000) !important;
    background-color: #ffffff !important;
    outline: none !important;
    box-shadow: 0 0 0 2px rgba(217, 37, 29, 0.15) !important;
}

#price-form select optgroup,
#services.custom-dropdown optgroup {
    background-color: #ffffff !important;
    color: #333333 !important;
    font-weight: 700 !important;
    font-size: 13.5px !important;
}

#price-form select option,
#services.custom-dropdown option {
    background-color: #ffffff !important;
    color: #333333 !important;
    font-weight: 500 !important;
    font-size: 14px !important;
    padding: 8px 10px !important;
}

#price-form .form-control:not(select),
#price-form input[type="text"] {
    width: 100%;
    height: 46px;
    padding: 10px 16px;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    font-size: 14px;
    color: var(--charcoal);
    background-color: var(--bg-light);
    transition: all 0.2s ease;
}

#price-form .form-control:not(select):focus,
#price-form input[type="text"]:focus {
    border-color: var(--primary-red);
    background-color: #ffffff;
    outline: none;
    box-shadow: 0 0 0 3px rgba(217, 37, 29, 0.12);
}

#price-form input[type="submit"],
#parcel-rate-submit {
    background: var(--primary-red) !important;
    color: #ffffff !important;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 15px !important;
    font-weight: 700 !important;
    height: 48px !important;
    padding: 0 36px !important;
    border-radius: 8px !important;
    border: none !important;
    box-shadow: 0 4px 14px rgba(217, 37, 29, 0.25) !important;
    transition: all 0.25s ease !important;
    cursor: pointer;
    margin-top: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

#parcel-rate-submit:hover {
    background: var(--primary-red-hover) !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(217, 37, 29, 0.35) !important;
}

/* Price Details Result Container */
.price-details-container {
    margin-top: 24px;
    padding: 20px;
    background: var(--bg-light);
    border-radius: 12px;
    border: 1.5px solid #e2e8f0;
}

.price-details-container h3 {
    font-family: 'Montserrat', sans-serif;
    font-size: 17px;
    font-weight: 700;
    color: var(--slate-dark) !important;
    border-bottom: 2px solid var(--primary-red);
    padding-bottom: 8px;
    margin-bottom: 16px;
}

.price-details-table {
    width: 100%;
    border-collapse: collapse;
}

.price-details-table td {
    padding: 10px 14px;
    border-bottom: 1px solid #e2e8f0;
    font-size: 15px;
    color: #334155;
}

.price-details-table td:first-child {
    font-weight: 600;
    color: var(--slate-muted);
    width: 45%;
}

.price-details-table td:last-child {
    text-align: right;
    font-weight: 700;
    color: var(--slate-dark);
}

.price-details-table tr:last-child td {
    border-bottom: none;
    font-size: 18px;
    font-weight: 800;
    color: var(--primary-red);
}

/* Pickup Details Modal Styling (#exampleModalCenter) */
.modal-content {
    border-radius: 16px !important;
    border: none !important;
    box-shadow: 0 24px 50px rgba(0, 0, 0, 0.25) !important;
    overflow: hidden;
}

.modal-header {
    background: var(--primary-red);
    color: #ffffff;
    padding: 18px 24px;
    border-bottom: none;
}

.modal-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 18px;
    font-weight: 700;
    color: #ffffff;
}

.modal-header .custom-close {
    top: 16px;
    right: 20px;
    background: rgba(255, 255, 255, 0.2) !important;
    color: #ffffff !important;
}

.modal-header .custom-close:hover {
    background: rgba(255, 255, 255, 0.35) !important;
}

.modal-body {
    padding: 24px;
    background-color: #ffffff;
}

.input-block label {
    font-family: 'Montserrat', sans-serif;
    font-size: 13px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 6px;
}

.input-block .form-control {
    height: 44px;
    padding: 8px 14px;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    font-size: 14px;
    color: var(--charcoal);
    transition: all 0.2s ease;
}

.input-block .form-control:focus {
    border-color: var(--primary-red);
    box-shadow: 0 0 0 3px rgba(217, 37, 29, 0.12);
}

.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #f1f5f9;
}

.pickup-submit-btn,
.modal-footer button[type="submit"] {
    background: var(--primary-red) !important;
    color: #ffffff !important;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 14px !important;
    font-weight: 700 !important;
    padding: 10px 28px !important;
    border-radius: 8px !important;
    border: none !important;
    box-shadow: 0 4px 12px rgba(217, 37, 29, 0.25) !important;
    transition: all 0.25s ease !important;
}

.pickup-submit-btn:hover,
.modal-footer button[type="submit"]:hover {
    background: var(--primary-red-hover) !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(217, 37, 29, 0.35) !important;
}

/* Custom Select Dropdown inside Modal */
.select-card {
    border: 1.5px solid #cbd5e1 !important;
    border-radius: 8px !important;
    padding: 10px 14px !important;
    box-shadow: none !important;
    margin: 0 !important;
    background: var(--bg-light);
}

.selectLabel {
    font-family: 'Montserrat', sans-serif;
    font-size: 13px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 6px;
    display: block;
}

.selectCustom {
    position: relative;
    width: 100%;
}

.selectCustom-trigger {
    font-size: 14px;
    font-weight: 500;
    color: var(--charcoal);
    background-color: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    padding: 8px 12px;
    cursor: pointer;
}

.selectCustom-options {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background-color: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    z-index: 100;
    display: none;
    max-height: 200px;
    overflow-y: auto;
}

.selectCustom.isActive .selectCustom-options {
    display: block;
}

.selectCustom-option {
    padding: 10px 14px;
    font-size: 13px;
    color: var(--charcoal);
    cursor: pointer;
    transition: background 0.15s ease;
}

.selectCustom-option:hover {
    background-color: #f1f5f9;
    color: var(--primary-red);
}

/* Testimonials Carousel Section (.testmonial-2) */
/* =========================================
   TESTIMONIAL - WHITE & BLACK THEME
   Existing classes only
   ========================================= */

.testmonial-2 {
    background: #ffffff !important;
    padding: 0 !important;
    margin: 0 !important;
    overflow: hidden !important;
}

/* Main testimonial background */
.testmonial-2 .testi-wrapper {
    height: 300px !important;
    min-height: 300px !important;
    background-color: #ffffff !important;
    background-image: none !important;
    display: flex !important;
    align-items: center !important;
    position: relative !important;
}

/* Remove dark overlay */
.testmonial-2 .testi-wrapper::before {
    display: none !important;
    content: none !important;
}

/* Content */
.testmonial-2 .testi-item {
    max-width: 1000px !important;
    margin: 0 auto !important;
    padding: 25px 35px !important;
    background: #f5f9ff !important;
    border: 1px solid #dbe7f5 !important;
    border-radius: 18px !important;
    box-shadow:
        0 10px 25px rgba(30, 80, 130, 0.10),
        0 4px 10px rgba(0, 0, 0, 0.05) !important;

    text-align: center !important;
}

/* Quote icon */
.testmonial-2 .testi-icon {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 38px !important;
    height: 38px !important;
    background: #000000 !important;
    color: #ffffff !important;
    border-radius: 50% !important;
    font-size: 16px !important;
    margin-bottom: 10px !important;
}

/* Stars */
.testmonial-2 .testi-star {
    margin-bottom: 10px !important;
}

.testmonial-2 .testi-star i {
    color: #000000 !important;
    font-size: 14px !important;
    margin-right: 2px !important;
}

/* Review text */
.testmonial-2 .testi-des {
    color: black !important;
    font-size: 18px !important;
    line-height: 1.6 !important;
    max-width: 760px !important;
    margin: 0 0 15px !important;
}

/* Client name */
.testmonial-2 .info .testi-name {
    display: block !important;

    color: #000000 !important;

    font-size: 17px !important;
    font-weight: 700 !important;

    margin-bottom: 3px !important;
}

/* Client designation */
.testmonial-2 .info .testi-job {
    display: block !important;

    color: #555555 !important;

    font-size: 13px !important;
}


/* =========================================
   MOBILE
   ========================================= */

@media (max-width: 767px) {

    .testmonial-2 .testi-wrapper {
        height: 350px !important;
        min-height: 350px !important;
    }

    .testmonial-2 .testi-item {
        margin: 0 15px !important;
        padding: 20px !important;
        border-radius: 14px !important;
    }

    .testmonial-2 .testi-des {
        font-size: 14px !important;
        line-height: 1.5 !important;
    }

    .testmonial-2 .info .testi-name {
        font-size: 15px !important;
    }

    .testmonial-2 .info .testi-job {
        font-size: 12px !important;
    }
}

/* Premium Corporate Footer Section */
.footer-widgets.widgets-area {
    background: linear-gradient(180deg, #0f172a 0%, #0b132b 100%) !important;
    color: var(--slate-light) !important;
    padding-top: 0 !important;
    border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
    position: relative;
}

.contact-widget {
    background: rgba(255, 255, 255, 0.03) !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
    padding: 36px 0 !important;
}

.contact-widget .row {
    display: flex !important;
    flex-wrap: wrap !important;
    align-items: center !important;
}

.contact-widget .contact {
    display: flex !important;
    align-items: flex-start !important;
    gap: 16px !important;
    margin-bottom: 0 !important;
    padding-top: 8px !important;
    padding-bottom: 8px !important;
}

.contact-widget .contact > i {
    color: var(--primary-red) !important;
    font-size: 22px !important;
    min-width: 46px !important;
    width: 46px !important;
    height: 46px !important;
    border-radius: 12px !important;
    background: rgba(217, 37, 29, 0.12) !important;
    border: 1px solid rgba(217, 37, 29, 0.2) !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    flex-shrink: 0 !important;
    margin-top: 2px !important;
}

.contact-widget .contact:hover > i {
    background: var(--primary-red) !important;
    color: #ffffff !important;
    transform: translateY(-2px) scale(1.05) !important;
    box-shadow: 0 6px 16px rgba(217, 37, 29, 0.35) !important;
}

.contact-widget .contact p {
    color: var(--slate-light) !important;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    margin: 0 0 4px 0 !important;
    line-height: 1.4 !important;
}

.contact-widget .contact h4 {
    color: #ffffff !important;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 15px !important;
    font-weight: 700 !important;
    margin: 0 !important;
    line-height: 1.5 !important;
    letter-spacing: 0.3px !important;
}

@media (max-width: 767px) {
    .contact-widget {
        padding: 24px 0 !important;
    }
    
    .contact-widget .contact {
        margin-bottom: 20px !important;
    }

    .contact-widget .contact:last-child {
        margin-bottom: 0 !important;
    }
}

.footer-logo {
    display: inline-block !important;
    padding: 6px 0 !important;
}

.footer-logo img {
    max-height: 55px !important;
    width: auto !important;
    object-fit: contain !important;
    background: transparent !important;
    filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.3)) !important;
    transition: transform 0.25s ease !important;
}

.footer-logo:hover img {
    transform: scale(1.04) !important;
}

.footer-sidebars {
    padding: 60px 0 40px !important;
}

.footer-sidebar .widget-title {
    font-family: 'Montserrat', sans-serif !important;
    font-size: 18px !important;
    font-weight: 700 !important;
    color: #ffffff !important;
    margin-bottom: 24px !important;
    position: relative !important;
    padding-bottom: 10px !important;
    letter-spacing: 0.5px !important;
}

.footer-sidebar .widget-title::after {
    content: '' !important;
    position: absolute !important;
    bottom: 0 !important;
    left: 0 !important;
    width: 36px !important;
    height: 3px !important;
    background-color: var(--primary-red) !important;
    border-radius: 2px !important;
}

.footer-sidebar .textwidget p,
.footer-sidebar .footform p {
    color: var(--slate-light) !important;
    font-size: 14px !important;
    line-height: 1.7 !important;
}

.footer-sidebar .widget_nav_menu ul.menu {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
}

.footer-sidebar .widget_nav_menu ul.menu li {
    margin-bottom: 12px !important;
}

.footer-sidebar .widget_nav_menu ul.menu li a {
    color: #cbd5e1 !important;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    text-decoration: none !important;
    display: inline-flex !important;
    align-items: center !important;
    transition: all 0.25s ease !important;
}

.footer-sidebar .widget_nav_menu ul.menu li a::before {
    content: '\f105' !important;
    font-family: 'FontAwesome' !important;
    margin-right: 10px !important;
    color: var(--primary-red) !important;
    font-size: 13px !important;
    transition: transform 0.2s ease !important;
}

.footer-sidebar .widget_nav_menu ul.menu li a:hover {
    color: var(--primary-red) !important;
    transform: translateX(6px) !important;
}

.footer-sidebar .widget_nav_menu ul.menu li a:hover::before {
    transform: translateX(3px) !important;
}

.fh-contact-box.type-social ul {
    list-style: none !important;
    padding: 0 !important;
    margin: 16px 0 0 0 !important;
    display: flex !important;
    gap: 10px !important;
}

.fh-contact-box.type-social ul li {
    margin: 0 !important;
}

.fh-contact-box.type-social ul li a {
    width: 40px !important;
    height: 40px !important;
    border-radius: 50% !important;
    background: rgba(255, 255, 255, 0.08) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    color: #ffffff !important;
    font-size: 16px !important;
    text-decoration: none !important;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

.fh-contact-box.type-social ul li a:hover {
    background: var(--primary-red) !important;
    border-color: var(--primary-red) !important;
    color: #ffffff !important;
    transform: translateY(-3px) !important;
    box-shadow: 0 6px 18px rgba(217, 37, 29, 0.4) !important;
}

.widget_mc4wp_form_widget .subscribe {
    display: flex !important;
    align-items: center !important;
    margin-top: 16px !important;
}

.widget_mc4wp_form_widget input[type="email"] {
    background: rgba(255, 255, 255, 0.08) !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    border-radius: 8px 0 0 8px !important;
    color: #ffffff !important;
    height: 46px !important;
    padding: 0 16px !important;
    font-size: 14px !important;
    flex: 1 !important;
    outline: none !important;
    transition: border-color 0.2s ease !important;
}

.widget_mc4wp_form_widget input[type="email"]:focus {
    border-color: var(--primary-red) !important;
    background: rgba(255, 255, 255, 0.12) !important;
}

.widget_mc4wp_form_widget input[type="submit"] {
    background: var(--primary-red) !important;
    border: none !important;
    border-radius: 0 8px 8px 0 !important;
    color: #ffffff !important;
    font-family: 'Montserrat', sans-serif !important;
    font-weight: 700 !important;
    font-size: 14px !important;
    height: 46px !important;
    padding: 0 22px !important;
    cursor: pointer !important;
    transition: background 0.25s ease !important;
}

.widget_mc4wp_form_widget input[type="submit"]:hover {
    background: var(--primary-red-hover) !important;
}

.site-footer {
    background: #070c1b !important;
    padding: 22px 0 !important;
    border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
}

.site-footer .site-info {
    color: var(--slate-light) !important;
    font-family: 'Montserrat', sans-serif !important;
    font-size: 14px !important;
}

.site-footer .site-info a {
    color: #ffffff !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    transition: color 0.2s ease !important;
}

.site-footer .site-info a:hover {
    color: var(--primary-red) !important;
}
</style>


{{-- add code of vikas --}}
<div class="track-order-top">
    <div class="slick-slider">
        <div class="slick-slide">
            <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=2400&q=90"
                 alt="Global Logistics Shipping">
        </div>

        <div class="slick-slide">
            <img src="https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&w=2400&q=90"
                 alt="Transport & Delivery Services">
        </div>

        <div class="slick-slide">
            <img src="https://images.unsplash.com/photo-1616401784845-180882ba9ba8?auto=format&fit=crop&w=2400&q=90"
                 alt="Courier Logistics">
        </div>

        <div class="slick-slide">
            <img src="https://content.jdmagicbox.com/v2/comp/mumbai/y4/022pxx22.xx22.110530154445.m7y4/catalogue/maruti-cargo-and-courier-andheri-east-mumbai-courier-services-lq3iwntxlf.jpg"
                 alt="Seamless Logistics Solutions">
        </div>
    </div>

</div> 





@push('script')
<script>
    $(document).ready(function() {
        $('.sliderBtn').click(function(e) {
            e.stopPropagation();
            $('.sliderformshow').toggleClass('show-popup');
            if ($('.sliderformshow').hasClass('show-popup')) {
                $('.sliderformshow').css('transform', 'translateY(0px) rotateX(0deg)');
            } else {
                $('.sliderformshow').css('transform', 'translateY(300px) rotateX(200deg)');
            }
        });

        $('.sliderformremove').click(function(e) {
            e.stopPropagation();
            $('.sliderformshow').removeClass('show-popup');
            $('.sliderformshow').css('transform', 'translateY(300px) rotateX(200deg)');
        });

        $(document).click(function(e) {
            if (!$(e.target).closest('.track-order-btn-wrap').length) {
                $('.sliderformshow').removeClass('show-popup');
                $('.sliderformshow').css('transform', 'translateY(300px) rotateX(200deg)');
            }
        });
    });
</script>

<script>
    const $tabs = document.querySelectorAll('.ai-tabs__link'),
        $line = document.querySelector('.ai-tabs__line'),

        getPos = ($currentTarget) => {
            const $parentContainer = document.querySelector('.ai-tabs__tab-header ul'),
                currentWidth = $currentTarget.offsetWidth,
                currentPos = {
                    top: $currentTarget.offsetTop - $parentContainer.offsetTop,
                    left: $currentTarget.offsetLeft - $parentContainer.offsetLeft,
                };
            return currentPos;
        },

        onLoadLine = () => {
            const $onLoadActive = document.querySelector('.ai-tabs--active'),
                divId = $onLoadActive.getAttribute('href');

            animateLine($onLoadActive, $line);
            document.querySelector(divId).classList.add('ai-tabs__content--active');
        },

        animateLine = ($currentTarget, $l) => {
            const widthOfLine = $l.offsetWidth,
                currentWidth = $currentTarget.offsetWidth,
                currentPos = getPos($currentTarget);

            $l.style.left = `${currentPos.left}px`;
            $l.style.width = `${currentWidth}px`;
        },

        setActive = (e, $tabs) => {
            e.preventDefault();
            const divId = e.currentTarget.getAttribute('href');

            $tabs.forEach(($tab) => {
                $tab.classList.remove('ai-tabs--active');
            });

            document.querySelectorAll('.ai-tabs__content').forEach(($content) => {
                $content.classList.remove('ai-tabs__content--active');
            });

            e.currentTarget.classList.add('ai-tabs--active');
            document.querySelector(divId).classList.add('ai-tabs__content--active');
        };

    $tabs.forEach(($tab) => {
        onLoadLine();
        $tab.addEventListener('click', (e) => {
            setActive(e, $tabs);
            animateLine(e.currentTarget, $line);
        });
    });
</script>

<script>
    function showTab(tabId) {
        // Get all tab content elements
        var tabContents = document.querySelectorAll('.tab-pane');


        for (var i = 0; i < tabContents.length; i++) {

            tabContents[i].style.display = 'none';


            if (tabContents[i].id === tabId) {

                tabContents[i].style.display = 'block';
            }
        }


        var tabLinks = document.querySelectorAll('.nav-link');

        console.log(tabLinks);
        for (var j = 0; j < tabLinks.length; j++) {

            tabLinks[j].classList.remove('active-tab');

            if (tabLinks[j].getAttribute('data-id') === tabId) {

                tabLinks[j].classList.add('active-tab');
            }
        }
    }

    // Show the first tab initially
    showTab('1');
</script>
@endpush

<!-- Modal start-->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Pickup Details</h5>
                <button type="button" class="close custom-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <form action="{{route('website.pickupDetailsStore')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Name <span class="text-danger">*</span></label>
                                    <input class="form-control" name="name" placeholder="Enter name" type="text" value="{{old('name')}}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                    <input class="form-control" name="email" placeholder="Enter email" type="text" value="{{old('email')}}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Phone <span class="text-danger">*</span></label>
                                    <input class="form-control" name="phone" placeholder="Enter phone" type="text" value="{{old('phone')}}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label">City <span class="text-danger">*</span></label>
                                    <input class="form-control" id="city" name="city" placeholder="Enter city" type="text" value="{{old('city')}}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label">State <span class="text-danger">*</span></label>
                                    <input class="form-control" id="state" name="state" placeholder="Enter City" type="text" value="{{old('city')}}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Pincode <span class="text-danger">*</span></label>
                                    <input class="form-control" id="pincode" name="pincode" placeholder="Enter Pincode" type="text" value="{{old('pincode')}}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class  align mb-3">
                                    <label class="col-form-label">Address <span class="text-danger">*</span></label>
                                    <input class="form-control" name="address" placeholder="Enter Address" type="text" value="{{old('address')}}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="select">
                                        <span class="selectLabel">Nearest Service Available</span>
                                        <div class="selectWrapper">
                                            <div class="selectCustom js-selectCustom">
                                                <div class="selectCustom-trigger">Select Service Location</div>
                                                <div class="selectCustom-options">

                                                </div>
                                            </div>
                                            <input type="hidden" name="franchiseID" id="serviceLocation">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal- footer                           <button style="background: #ff0000;
                        border: none;" type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal end-->

@push('script')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Check if session has a success message
        @if(session('success'))
            Swal.fire({
                icon: "success",
                title: "Success!",
                text: "{{ session('success') }}",
                confirmButtonText: "OK"
            });
        @endif

        // Check if session has an error message
        @if(session('error'))
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "{{ session('error') }}",
                confirmButtonText: "OK"
            });
        @endif
    });
</script>



<script>
    const elSelectCustom = document.getElementsByClassName("js-selectCustom")[0];
    const elSelectCustomValue = elSelectCustom.children[0];
    const elSelectCustomOptions = elSelectCustom.children[1];
    const defaultLabel = elSelectCustomValue.getAttribute("data-value");

    // Listen for each custom option click
    Array.from(elSelectCustomOptions.children).forEach(function(elOption) {
        elOption.addEventListener("click", (e) => {
            // Update custom select text too
            elSelectCustomValue.textContent = e.target.textContent;
            // Close select
            elSelectCustom.classList.remove("isActive");
        });
    });

    // Toggle select on label click
    elSelectCustomValue.addEventListener("click", (e) => {
        elSelectCustom.classList.toggle("isActive");
    });

    // close the custom select when clicking outside.
    document.addEventListener("click", (e) => {
        const didClickedOutside = !elSelectCustom.contains(event.target);
        if (didClickedOutside) {
            elSelectCustom.classList.remove("isActive");
        }
    });
</script>
<script>
    $('#pincode').on('change', function() {
        var pincode = $(this).val();
        var city = $('#city').val();
        var state = $('#state').val();

        if (pincode.length === 6) {
            $.ajax({
                url: "{{ route('website.getPinCodes') }}",
                type: 'Post',
                data: {
                    pincode: pincode,
                    city: city,
                    state: state,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    let data = response.map((element) => {
                        return `<div class="selectCustom-option" data-value="${element.franchiseInfo.id}">${element.franchiseInfo.address}</div>`;
                    }).join('');
                    $('.selectCustom-options').html(data);
                },
                error: function(xhr, status, error) {
                
                    console.error("Error:", error);
                }
            });
        }

    });
</script>

<script>
    $(document).ready(function() {
        $('.selectCustom').on('click', '.selectCustom-option', function() {
            var selectedValue = $(this).attr('data-value');
            var selectedText = $(this).text();
            $('.selectCustom-trigger').text(selectedText);
            $('#serviceLocation').val(selectedValue);
            $('.js-selectCustom').removeClass('isActive');
        });

        $('.selectCustom-trigger').click(function() {
            $('.selectCustom-options').toggleClass('isActive');
        });
    });
</script>
@endpush

{{-- section one end --}}

<style>
    /* Default box styling (optional, for reference) */
/* .fh-icon-box {
    border: 2px solid transparent;
    transition: all 0.3s ease;
    padding: 20px;
    border-radius: 8px;
}

/* Hover effect */
.fh-icon-box:hover {
    border-color: #ff0000; /* red border on hover */
    box-shadow: 0 0 15px rgba(255, 0, 0, 0.3); /* subtle red glow */
    transform: translateY(-5px); /* lift the box slightly */
}

/* Optional: change title color on hover */
.fh-icon-box:hover .box-title a {
    color: #ff0000 !important;
} */

</style>
<!-- Welcome sec -->
<div class="welcomesec secpadd">
    <div class="container">
        <div class="welcome-header-wrapper">
            <button data-toggle="modal" data-target="#exampleModalCenter" class="shipNowButton" type="button">
            </button>
            
            <div class="fh-section-title clearfix text-center version-dark paddbtm20">
                <h2>Welcome to GOTOGO <span class="main-color">POST</span></h2>
            </div>
            
            <div class="track-order-btn-wrap">
                <button type="button" class="sliderBtn fh-btn btn">Track Order</button>
                <div class="content content-upper sliderformshow">
                    <button type="button" class="close custom-close sliderformremove" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="tab-content-ss">
                        <div id="1" class="container-fluid tab-pane">
                            <form action="{{route('website.trackholder')}}">
                                <div class="form-group m-11">
                                    <label for="exampleFormControlInput1"><strong>Enter Article No.</strong></label>
                                    <input type="text" class="form-control is-valid" id="trackOrder" name="trackOrder" placeholder="Enter Article No.">
                                </div>
                                <button type="submit" class="btn btn-primary font-20 mt-5">submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p class="haeadingpara text-center paddbtm40">GOTOGO POST is more than logistics.
<br /> We help optimize your packaging, manage material sourcing, and offer much more to support your business.
        </p>
        <div class="welservices row">
            <div class="col-md-4 col-sm-6">
                <div class="fh-icon-box icon-type-theme_icon style-1 version-dark hide-button icon-left">
                    <span class="fh-icon"><img alt="1764" src="{{ asset('website/images/express.png') }}" style="height:100px;"></span>
                    <h4 class="box-title"><a href="#" style="color:#000">Express Parcels</a></h4>
                    <div class="desc">
                        <p>From time-critical express delivery to cost-effective ground solutions, our Express Parcels Vertical serves both C2C and B2B customers. We handle everything from documents to part-truck-load shipments, offering reliable day-definite and time-definite options for all your shipping needs.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="fh-icon-box icon-type-theme_icon style-1 version-dark hide-button icon-left">
                    <span class="fh-icon"><img alt="1764" src="{{ asset('website/images/inter.png') }}" style="height:100px;"></span>
                    <h4 class="box-title"><a href="#" style="color:#000">International Shipping</a></h4>
                    <div class="desc">
                        <p>We provide a variety of international shipping options for both individual and business customers. From urgent document delivery to budget-friendly parcel solutions, we meet all international shipping needs with reliable and flexible services.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="fh-icon-box icon-type-theme_icon style-1 version-dark hide-button icon-left">
                    <span class="fh-icon"><img alt="1764" src="{{ asset('website/images/pracel.png') }}" style="height:100px;"></span>
                    <h4 class="box-title"><a href="#" style="color:#000">Integrated E-Commerce Logistics</a></h4>
                    <div class="desc">
                        <p>Tailored for the booming e-commerce sector, our vertical supports aggregators, D2C brands, and B2C customers. We offer solutions for both time-sensitive e-commerce shipments and cost-effective deliveries for lower-value B2C orders.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Welcome sec   end-->
<!-- special services comment code by vikas  -->
<!-- <section class="special_services bluebg secpadd">
    <div class="container">
        <div class="row paddbtm40">
            <div class="col-md-4 col-sm-12">
                <div class="fh-section-title clearfix  text-left version-light">
                    <h2>Our Special SERVICES</h2>
                </div>
            </div>
            <div class="col-md-8 col-sm-12">
                <div class="hdrgtpara lftredbrdr">
                    <p>Our warehousing services are known nationwide to be one of the most reliable, safe and affordable, because we take pride in delivering the best of warehousing services, at the most reasonable prices.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="fh-service-box icon-type-theme_icon style-1">
                    <span class="fh-icon"><i class="flaticon-open-cardboard-box"></i></span>
                    <h4 class="service-title"><a href="#" class="link" target="_blank">Packaging And Storage</a></h4>
                    <div class="desc">
                        <p>Package and store your things effectively and securely to make sure them in storage.</p>
                    </div>
                </div>
                <div class="fh-service-box icon-type-theme_icon style-1">
                    <span class="fh-icon"><i class="flaticon-buildings"></i></span>
                    <h4 class="service-title"><a href="#" class="link" target="_blank">Warehousing</a></h4>
                    <div class="desc">
                        <p>Package and store your things effectively and securely to make sure them in storage.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="fh-service-box icon-type-theme_icon style-1">
                    <span class="fh-icon"><i class="flaticon-transport-9"></i></span>
                    <h4 class="service-title"><a href="#" class="link" target="_blank">Cargo</a></h4>
                    <div class="desc">
                        <p><br><br><br></p>
                    </div>
                </div>
                <div class="fh-service-box icon-type-theme_icon style-1">
                    <span class="fh-icon"><i class="flaticon-transport-2"></i></span>
                    <h4 class="service-title"><a href="#" class="link" target="_blank">Door to Door Delivery</a></h4>
                    <div class="desc">
                        <p>Our expertise in transport management and planning allows us to design a solution.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="fh-service-box icon-type-theme_icon style-1">
                    <span class="fh-icon"><i class="flaticon-international-delivery"></i></span>
                    <h4 class="service-title"><a href="#" class="link" target="_blank">Worldwide Transport</a></h4>
                    <div class="desc">
                        <p>Specialises in international freight forwarding of merchandise and associated general logistic services.</p>
                    </div>
                </div>
                <div class="fh-service-box icon-type-theme_icon style-1">
                    <span class="fh-icon"><i class="flaticon-transport-1"></i></span>
                    <h4 class="service-title"><a href="#" class="link" target="_blank">Ground Transport</a></h4>
                    <div class="desc">
                        <p>Ground transportation options for all visitors, no matter your needs, schedule or destination.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->
<!-- special services end -->

<script>
$(document).ready(function(){

    $('.fh-icon-box').hover(
        function() {
            // Hover In
            $(this).css({
                'border': '2px solid #ff0000',
                'background-color': '#ff0000',   // use background-color instead of background
                'box-shadow': '0 0 15px rgba(255, 0, 0, 0.3)',
                'transform': 'translateY(-5px)',
                'transition': 'all 0.3s ease'
            });

            // Force text color changes
            $(this).find('.box-title a, .desc p, .fh-icon img').css('color', '#fff');
            $(this).find('.box-title a').css('color', '#fff');
        },
        function() {
            // Hover Out
            $(this).css({
                'border': '2px solid transparent',
                'background-color': '#fff',
                'box-shadow': 'none',
                'transform': 'translateY(0)'
            });
            $(this).find('.box-title a, .desc p').css('color', '#000');
        }
    );

});
</script>
<!--home counters -->
{{-- <section class="homecounts">
    <div class="container">
        <h2 class="count-title"><span class="main-color">GOTOGOPOST</span> is a Global Supplier of Transport and logistics Solutions.</h2>
    </div>
</section> --}}



<!--home counters end -->
<style>
    .custom-dropdown,
.custom-dropdown option,
.custom-dropdown optgroup {
  text-align: justify;
  text-justify: inter-word;
}

</style>
<!--why choose us -->
<section class="whychoose-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-md-6  secpaddlf">
                <div class="fh-section-title clearfix  text-left version-dark paddbtm40">
                    <h2>Why Choosing us?</h2>
                </div>
                <div class="fh-icon-box  style-2 icon-left has-line">
                    <span class="fh-icon"><i class="flaticon-international-delivery"></i></span>
                    <h4 class="box-title"><span>Global supply Chain Solutions</span></h4>
                    <div class="desc">
                        <p>Efficiently unleash cross-media information without cross-media value.</p>
                    </div>
                </div>
                <div class="fh-icon-box  style-2 icon-left has-line">
                    <span class="fh-icon"><i class="flaticon-people"></i></span>
                    <h4 class="box-title"><span>24 Hours - Technical Support</span></h4>
                    <div class="desc">
                        <p>Specialises in international freight forwarding of merchandise and associated logistic services</p>
                    </div>
                </div>
                <div class="fh-icon-box  style-2 icon-left has-line">
                    <span class="fh-icon"><i class="flaticon-route"></i></span>
                    <h4 class="box-title"><span>Mobile Shipment Tracking</span></h4>
                    <div class="desc">
                        <p>We Offers intellgent concepts for road and tail and well as complex special transport services</p>
                    </div>
                </div>
                <div class="fh-icon-box  style-2 icon-left">
                    <span class="fh-icon"><i class="flaticon-open-cardboard-box"></i></span>
                    <h4 class="box-title"><span>Careful Handling of Valuable Goods</span></h4>
                    <div class="desc">
                        <p>GOTOGO POST are transported at some stage of their journey along the world’s roads</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-lg-offset-2 col-md-6 quofrm1  secpaddlf">

                <div class="fh-section-title clearfix  text-left version-dark paddbtm40 price-inquiry-div" style="background:#ff0000;">
                    <h2 style="color: #fff;font-size:30px;padding:5px;text-align:center"> PRICE INQUIRY </h2>
                </div>

                <div id="price-upper-div" style="background:#ff0000;">
                    <form  id="price-form"onsubmit="return false;">
                        <div class="fh-form-1 fh-form">
                            <div class="row fh-form-row">
                                <div class="col-md-6 col-xs-12 col-sm-12">
                                    <p class="field">
                                        <!-- <select id="services" placeholder="Service*" name=""> -->
                                        <select id="services" name="service" class="form-control custom-dropdown">
                                            <optgroup label="GOTOGO POST BOOKING SERVICES" class="gotogo-group">
                                                <option value="1">Gotogo Super Speed Packet</option>
                                                <option value="3">Gotogo Express Parcel</option>
                                                <option value="4">Gotogo Secure Parcel</option>
                                            </optgroup>
                                            <optgroup label="ALL INDIA-POST SERVICES" class="india-post-group">
                                                <option value="5">SP Inland Domestic</option>
                                                <option value="6">SP Parcel Domestic</option>
                                                <option value="6">SP Parcel Business</option>
                                            </optgroup>
                                        </select>
                                    </p>
                                </div> 
                                <!-- <div class="col-md-6 col-xs-12 col-sm-12">
                                    <p class="field">
                                        <select id="services" name="service" class="form-control custom-dropdown" required>
                                            <option value="" disabled selected>Select Service</option>
                                            <optgroup label="GOTOGO POST BOOKING SERVICES" class="gotogo-group">
                                                <option value="1">GOTOGO Speed Packet</option>
                                                <option value="3">GOTOGO Express Package</option>
                                                <option value="4">GOTOGO Secure Packet</option>
                                            </optgroup>
                                            <optgroup label="GOTOGO ALL INDIA POST SERVICES" class="india-post-group">
                                                <option value="6">GOTOGO Speed Packet</option>
                                                <option value="7">GOTOGO Business Package</option>
                                            </optgroup>
                                        </select>
                                    </p>
                                </div> -->
                                
                                <div class="col-md-6 col-xs-12 col-sm-12">
                                    <p class="field">
                                        <input name="weight" id="Weight" value="" placeholder="Weight in gm*" type="text">
                                    </p>
                                </div>
                                 <div class="col-md-6 col-xs-12 col-sm-12">
                                    <p class="field">
                                        <input name="width" id="width" value="" placeholder="Width in gm*" type="text">
                                    </p>
                                </div>
                                 <div class="col-md-6 col-xs-12 col-sm-12">
                                    <p class="field">
                                        <input name="length" id="length" value="" placeholder="Length in gm*" type="text">
                                    </p>
                                </div>
                                 <div class="col-md-6 col-xs-12 col-sm-12">
                                    <p class="field">
                                        <input name="height" id="height" value="" placeholder="Height in gm*" type="text">
                                    </p>
                                </div>
                                <div class="col-md-6 col-xs-12 col-sm-12">
                                    <p class="field">
                                        <input name="from" id="FromPincode" value="" placeholder="From Pincode*" type="text">
                                    </p>
                                </div>
                                <div class="col-md-6 col-xs-12 col-sm-12">
                                    <p class="field">
                                        <input name="to" value="" id="ToPincode" placeholder="To Pincode*" type="text">
                                    </p>
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                    <p class="field submit field_submit">
                                        <input value="Submit" id="parcel-rate-submit" class="fh-btn" type="submit" style="background: #fff;color: #000">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="invalid-feedback">
                        Please provide consignee pincode
                    </div>
                    <style>
                        table td{
                            background: #fff;
                            color: #000;
                        }
                    </style>
                    <div id="result-container" class="price-details-container" style="display:none">
                        <h3 style="color:#fff">Price Details</h3>
                        <table class="price-details-table">
                            <tr><td>Zone</td><td id="zone">D</td></tr>
                            <tr><td>Amount</td><td id="price">90</td></tr>
                            <tr><td>GST</td><td id="gst">9</td></tr>
                            <tr><td>Total</td><td id="total">97</td></tr>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>
</section>


<section class="homecounts compact-app-banner">
    <div class="container">
        <div class="app-banner-card">
            <div class="row align-items-center">
                <!-- Left Section: Download Info -->
                <div class="col-lg-7 col-md-7 col-sm-12 app-banner-content">
                    <h3 class="app-banner-title">
                        <span class="main-color">GOTOGOPOST</span> &ndash; Download Our Mobile App for Seamless Services!
                    </h3>
                    <p class="app-banner-desc">
                        Experience the best courier &amp; logistics services with our mobile app.
                    </p>
                    <div class="download-buttons">
                        <a href="https://play.google.com/store/apps/details?id=com.gotogopost.customer&pcampaignid=web_share" target="_blank" class="download-btn">
                            <img src="{{ asset('website/images/google-play.svg') }}" alt="Google Play">
                        </a>
                        <a href="#" class="download-btn">
                            <img src="{{ asset('website/images/apple-store.svg') }}" alt="App Store">
                        </a>
                    </div>
                </div>

                <!-- Right Section: App Image -->
                <div class="col-lg-5 col-md-5 col-sm-12 text-right app-banner-img-col">
                    <div class="app-banner-img-wrap">
                        <img src="https://plus.unsplash.com/premium_photo-1681760173535-5c82d39ce645?q=80&w=784&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" onerror="this.onerror=null;this.src='{{ asset('website/images/app-img.jpg') }}';" alt="GOTOGOPOST Mobile App" class="app-banner-img">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--why choose us end -->

<!--partener -->
<!-- <section class="partener-1 bluebg">
    <div class="container">
        <div class="fh-partner clearfix">
            <div class="list-item slide-partener">
                <div class="partner-item">
                    <div class="partner-content">
                        <a href="#" target="_self"><img alt="1764" src="{{ asset('website/images/partener/partner-4.png') }}"></a>
                    </div>
                </div>
                <div class="partner-item">
                    <div class="partner-content">
                        <a href="#" target="_self"><img alt="1763" src="{{ asset('website/images/partener/partner-4.png') }}"></a>
                    </div>
                </div>
                <div class="partner-item">
                    <div class="partner-content">
                        <a href="#" target="_self"><img alt="1762" src="{{ asset('website/images/partener/partner-4.png') }}"></a>
                    </div>
                </div>
                <div class="partner-item">
                    <div class="partner-content">
                        <a href="#" target="_self"><img alt="1761" src="{{ asset('website/images/partener/partner-4.png') }}"></a>
                    </div>
                </div>
                <div class="partner-item">
                    <div class="partner-content">
                        <a href="#" target="_self"><img alt="1765" src="{{ asset('website/images/partener/partner-4.png') }}"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->
<!--partener end -->
<style>
    .owl-theme .owl-controls {
    margin-top: -80px;
    }
</style>
<!--testimonials -->
<section class="testmonial-2">
    <div class="fh-testimonials-carousel fh-testimonials column-2">
        <div class="testi-list  single-slide">
            <div class="testi-wrapper" style="background-image:url(images/main-slider/testi-1.jpg)">
                <div class="container">
                    <div class="testi-item">
                       
                        <div class="testi-content">
                            <div class="testi-star">
                                <i class="fa fa-star fa-md"></i>
                                <i class="fa fa-star fa-md"></i><i class="fa fa-star fa-md"></i>
                                <i class="fa fa-star fa-md"></i><i class="fa fa-star fa-md"></i>
                            </div>
                            <div class="testi-des">These guys are just the coolest company ever! They were aware of our transported for road and tail and well as complex transport services.</div>
                            <div class="info clearfix">
                                <span class="testi-name">Magdalena Donowan</span>
                                <span class="testi-job">CFD Engineer</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testi-wrapper" style="background-image:url(images/main-slider/testi-3.jpg)">
                <div class="container">
                    <div class="testi-item">
                        
                        <div class="testi-content">
                            <div class="testi-star">
                                <i class="fa fa-star fa-md"></i>
                                <i class="fa fa-star fa-md"></i><i class="fa fa-star fa-md"></i>
                                <i class="fa fa-star fa-md"></i><i class="fa fa-star fa-md"></i>
                            </div>
                            <div class="testi-des">The shipping process with this crew was a pleasurable experience! They did all in time and with no safety incidents. Thank you so much guys!</div>
                            <div class="info clearfix">
                                <span class="testi-name">Emilia Crena</span>
                                <span class="testi-job">CEO, VIP Construction, Australia</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testi-wrapper" style="background-image:url(images/main-slider/testi-2.jpg)">
                <div class="container">
                    <div class="testi-item">
                       
                        <div class="testi-content">
                            <div class="testi-star">
                                <i class="fa fa-star fa-md"></i>
                                <i class="fa fa-star fa-md"></i><i class="fa fa-star fa-md"></i>
                                <i class="fa fa-star fa-md"></i><i class="fa fa-star fa-md"></i>
                            </div>
                            <div class="testi-des">Their performance on our project was extremely successful. As a result of this collaboration, the project was built with exceptional quality &amp; delivered.</div>
                            <div class="info clearfix">
                                <span class="testi-name">Orlando E. Dougles</span>
                                <span class="testi-job">CEO, Green Valley Inc, London</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--testimonilas-->

<!--news -->
<!-- <section class="testmonial-1 secpadd">
    <div class="container">
        <div class="fh-section-title clearfix  text-left version-dark paddbtm40">
            <h2>FEATURED NEWS</h2>
        </div>
        <div class="fh-latest-post carousel">
            <div class="post-list  news-slide">
                <div class="item-latest-post clearfix">
                    <div class="entry-thumbnail">
                        <a href="#"><img src="{{ asset('website/images/blogs/blog-img2.jpeg') }}" alt="" /></a>
                        <div class="entry-time">
                            <span class="day">09</span>
                            <span class="month">Aug</span>
                        </div>
                    </div>
                    <div class="entry-summary">
 
                        <h2 class="entry-title"><a href="#">Air Cargo May Become Short-term Solution</a></h2>
                       
                    </div>
                </div>
                <div class="item-latest-post clearfix">
                    <div class="entry-thumbnail">
                        <a href="#"><img src="{{ asset('website/images/blogs/blog-8.jpg') }}" alt="" /></a>
                        <div class="entry-time">
                            <span class="day">09</span>
                            <span class="month">Aug</span>
                        </div>
                    </div>
                    <div class="entry-summary">
                        <h2 class="entry-title"><a href="#">We introduce new boat and flight service</a></h2>
                    </div>
                </div>
                <div class="item-latest-post clearfix">
                    <div class="entry-thumbnail">
                        <a href="#"><img src="{{ asset('website/images/blogs/blog-8.jpg') }}" alt="" /></a>
                        <div class="entry-time">
                            <span class="day">26</span>
                            <span class="month">Jul</span>
                        </div>
                    </div>
                    <div class="entry-summary">
                        <h2 class="entry-title"><a href="#">Introduce new boat service in this spring</a></h2>
                    </div>
                </div>
                <div class="item-latest-post clearfix">
                    <div class="entry-thumbnail">
                        <a href="#"><img src="{{ asset('website/images/blogs/blog-8.jpg') }}" alt="" /></a>
                        <div class="entry-time">
                            <span class="day">26</span>
                            <span class="month">Jul</span>
                        </div>
                    </div>
                    <div class="entry-summary">
                        <h2 class="entry-title"><a href="#">Goods will be contain in certified safe warehouse</a></h2>
                    </div>
                </div>
                <div class="item-latest-post clearfix">
                    <div class="entry-thumbnail">
                        <a href="#"><img src="{{ asset('website/images/blogs/blog-8.jpg') }}" alt="" /></a>
                        <div class="entry-time">
                            <span class="day">26</span>
                            <span class="month">Jul</span>
                        </div>
                    </div>
                    <div class="entry-summary">
                        
                        <h2 class="entry-title"><a href="#">Our customer around the world satisty with it</a></h2>
                        
                    </div>
                </div>
                <div class="item-latest-post clearfix">
                    <div class="entry-thumbnail">
                        <a href="#"><img src="{{ asset('website/images/blogs/blog-8.jpg') }}" alt="" /></a>
                        <div class="entry-time">
                            <span class="day">25</span>
                            <span class="month">Jul</span>
                        </div>
                    </div>
                    <div class="entry-summary">
                        
                        <h2 class="entry-title"><a href="#">We ensures you best the quality services</a></h2>
                        
                    </div>
                </div>
                <div class="item-latest-post clearfix">
                    <div class="entry-thumbnail">
                        <a href="#"><img src="{{ asset('website/images/blogs/blog-8.jpg') }}" alt="" /></a>
                        <div class="entry-time">
                            <span class="day">09</span>
                            <span class="month">Aug</span>
                        </div>
                    </div>
                    <div class="entry-summary">
                        <h2 class="entry-title"><a href="#">Our Cargo trucking service</a></h2>
                    </div>
                </div>
                <div class="item-latest-post clearfix">
                    <div class="entry-thumbnail">
                        <a href="#"><img src="{{ asset('website/images/blogs/blog-8.jpg') }}" alt="" /></a>
                        <div class="entry-time">
                            <span class="day">09</span>
                            <span class="month">Aug</span>
                        </div>
                    </div>
                    <div class="entry-summary">
                       
                        <h2 class="entry-title"><a href="#">Delivering logistic services</a></h2>
                
                    </div>
                </div>

            </div>
        </div>
    </div>
</section> -->
<!--news ends -->




@push('script')

<script>
    $(document).ready(function() {
        $('.invalid-feedback').hide();

        $('#parcel-rate-submit').on('click', function(e) {
            
            e.preventDefault();
            const from = $('#FromPincode').val();
            const to = $('#ToPincode').val();
            const weight = $('#Weight').val();
            const height = $('#height').val();
            const length = $('#length').val();
            const width = $('#width').val();
            const service_type = $('#services').val(); 

            $('.validerror-destination').text('Please enter a valid pincode').hide();
            $('.validerror-origin').text('Please enter a valid pincode').hide();

            if (!from) {
                $('#FromPincode').next('.invalid-feedback').show();
                return;
            } else {
                $('#FromPincode').next('.invalid-feedback').hide();
            }

            if (!to) {
                $('#ToPincode').next('.invalid-feedback').show();
                return;
            } else {
                $('#ToPincode').next('.invalid-feedback').hide();
            }

            if (!weight) {
                $('#Weight').next('.invalid-feedback').show();
                return;
            } else {
                $('#Weight').next('.invalid-feedback').hide();
            }

            const data = {
                originPincode: from,
                destinationPincode: to,
                packageWeight: weight,
                packageHeight: height,
                packageWidth: width,
                packageLength: length,
                service_type: service_type
            };

            let url = $('#price-form').attr('action');
            var csrfToken = "{{ csrf_token() }}";

           

            $.ajax({
                type: 'POST',
                url: "{{ route('website.getPrice') }}",
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: data,
                success: function(responseData) {
                    if (responseData.status === 'success') {
                        $('.invalid-feedback').hide();
                        $("#result-container").css("display", "block");
                        $('#zone').text(responseData.zone);
                        $('#price').text('₹ ' + responseData.price);
                        $('#gst').text('₹ ' + responseData.gst);
                        $('#total').text('₹ ' + responseData.total);
                    } else {
                        $("#result-container").css("display", "none");
                        $('.invalid-feedback').text('Please enter a valid pincode').show();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX error:', textStatus, errorThrown);
                    $('.error-message').text("An error occurred. Please try again.").show();
                }
            });
        });
    });
</script>


 <script>
    var swiper = new Swiper('.swiper-container', {
        loop: true,
        autoplay: {
            delay: 3000, // 3 seconds per slide
            disableOnInteraction: false
        },
        effect: 'fade'
    });
</script> 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script>
    $(document).ready(function(){
        $('.slick-slider').slick({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
            speed: 800,
            fade: true,
            arrows: false,
            dots: false,
            pauseOnHover: false
        });
    });
</script>



<script>
    const query = window.location.search;

    if (query.startsWith('?/')) {
        const parts = query.substring(2).split('/');
        const mailCode = parts[0];
        const phone = parts[1];

        if (mailCode && phone) {
            const redirectUrl = `/franchise/downloadAttachmentView/${mailCode}/${phone}`;
            window.location.href = redirectUrl;
        }
    }
</script>



@endpush


@endsection