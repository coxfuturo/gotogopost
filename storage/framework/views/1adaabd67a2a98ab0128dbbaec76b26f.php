<?php $__env->startSection('content'); ?>



<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>



<style>
.track-upper{
    /*position: absolute; */
    bottom: 0px;
    padding: 0px 20px;
    width: 100%;
}

.sliderformshow {
    width: 350px; /* Increase width */
    padding: 20px;
    background: white;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
    position: relative;
}

/* Close button styling */
.custom-close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 18px;
    background: red;
    color: white;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

/* Label styling */
.sliderformshow label {
    font-size: 16px;
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
    color: #333;
}

/* Input field styling */
.sliderformshow input {
    width: 100%;
    height: 40px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    text-align: left;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Submit button styling */
.sliderformshow .btn {
    width: 100%;
    margin-top: 15px;
    padding: 10px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 10px;
    background: #1a2674; /* Dark blue */
    color: white;
    border: none;
    cursor: pointer;
    transition: 0.3s;
}

.sliderformshow .btn:hover {
    background: #12205a; /* Slightly darker blue */
}



.slick-slider {
    width: 100%;
    /* height: 100vh;  */
    overflow: hidden;
}

.slick-slide img {
    width: 100%;
    height: 100%; /* Full Screen */
    object-fit: cover;
    padding-top: 92px;
}



    .track-order-top {
        background-color: transparent;
        background-position: 100%;
        background-size: cover;
        color: #fff;
        min-height: 500px;
        position: relative;
    }

    .shipNowButton {
        width: 165px;
        height: 44px;
        border: none;
        border-radius: 10px;
        background-size: 250%;
        background-position: left;
        color: #2a2417;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition-duration: 1s;
        overflow: hidden;
        margin-top: 30px;
    }

    .shipNowButton::before {
        position: absolute;
        content: "PICKUP ENQUIRY";
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 97%;
        height: 90%;
        border-radius: 8px;
        transition-duration: .3s;
        background-color: #112D82;
        background-size: 200%;
        font-size: 14px;
    }

    .shipNowButton:hover::before {
        background-color: #112D82;
    }


    .header-sticky.header-v1 .site-header.minimized,
    .header-sticky.header-v2 .site-header.minimized {
        top: 0;
        left: 50%;
        -webkit-transform: translate(-50%, 0);
        -ms-transform: translate(-50%, 0);
        transform: translate(-50%, 0);
        width: 100%;
        position: fixed;
        z-index: 99;
        background-color: #fff;
    }

    .content-upper .nav {
        line-height: 25px;
    }

    @media screen and (max-width: 992px) {
        .content-upper {
            margin-top: 50px;
        }

        .content {
            padding: 45px 14px;
        }


        /* .track-order-top {
            padding: 15px;
            padding-bottom: 50px;
        } */


        .heading-title {
            padding-top: 0px;
            margin-top: 65px;
        }

        .mobile-app-img{
            display:flex;
            flex-wrap:wrap;
            justify-content:center;
            align-items:center

        }

    }

    .details-container {
    margin-top: 20px;
    padding: 15px;
    background: #fff;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

/* price detils style */
.price-details-container h3 {
    font-size: 17px;
    font-weight: bold;
    border-bottom: 2px solid red;
    padding-bottom: 5px;
    margin-bottom: 15px;
    color: #333;
}

.price-details-table {
    width: 100%;
    border-collapse: collapse;
}

.price-details-table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    font-size: 16px;
}

.price-details-table td:first-child {
    font-weight: bold;
    background: #f8f8f8;
    width: 40%;
}

.price-details-table td:last-child {
    text-align: right;
    font-weight: bold;
    color: #333;
}

.price-details-table tr:last-child td {
    border-bottom: none;
    font-size: 17px;
    font-weight: bold;
    color: #d32f2f;
}

.whychoose-1 .quofrm1 {
    padding: 80px 0px 121px;
    background: white !important;
    padding-bottom: 60px;

}


#price-upper-div{
    background: #f7f7f7;
    padding: 40px 30px;
}

/* price detils style */

</style>

<style>
    .custom-close {
        padding: 5px;
        border-radius: 18px;
        background: #ff0000 !important;
        width: 23px;
        height: 23px;
        position: relative;
        top: -20px;
    }

    .close {
        float: right;
        font-size: 16px;
        font-weight: 400;
        line-height: 1;
        color: white;
        border: 2px solid black;

        opacity: 1;
    }

    .modal-footer {
        padding: 15px;
        text-align: right;
        border-top: none;
    }


    .select {
        position: relative;
    }

    .selectLabel {
        display: block;
        font-weight: bold;
        margin-bottom: 0.4rem;
    }

    .selectWrapper {
        position: relative;
    }

    .selectCustom {
        position: relative;
        width: 26rem;
        height: 3.5rem;
    }

    .selectCustom-trigger {
        font-size: 1.6rem;
        background-color: #fff;
        border: 1px solid #6f6f6f;
        border-radius: 0.4rem;
        cursor: pointer;
    }

    .selectCustom-trigger {
        position: relative;
        width: 100%;
        height: 100%;
        background-color: #fff;
        padding: 0.5rem 0.7rem;
        font-size: 14px;
    }

    .selectCustom-trigger::after {
        content: "▾";
        position: absolute;
        top: 0;
        line-height: 3.8rem;
        right: 0.8rem;
    }



    .selectCustom-options {
        position: absolute;
        top: calc(3.8rem + 0.8rem);
        left: 0;
        width: 100%;
        border: 1px solid #6f6f6f;
        border-radius: 0.4rem;
        background-color: #fff;
        box-shadow: 0 0 4px #e9e1f8;
        z-index: 1;
        padding: 0.8rem 0;
        display: none;
    }

    .selectCustom.isActive .selectCustom-options {
        display: block;
    }

    .selectCustom-option {
        position: relative;
        padding: 0.4rem;
        font-size: 14px;
    }

    .selectCustom-option:hover {
        background-color: #f7ecff;
    }

    .selectCustom-option:not(:last-of-type)::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0.8rem;
        width: calc(100% - 1.6rem);
        border-bottom: 1px solid #d3d3d3;
    }
    strong {
        font-weight: 600;
    }

    .title {
        font-size: 2rem;
        font-weight: 600;
        margin: 1.6rem;
        line-height: 1.2;
        text-align: center;
    }

    .card {
        margin: 1.5rem auto;
    }

    .footer {
        position: relative;
        width: 100%;
        margin-top: 60px;
        padding: 24px 16px;
        text-align: center;
        font-size: 1.4rem;
        background: white;

    }

    .header-transparent .navbar-icon .navbars-line,
    .header-transparent .navbar-icon .navbars-line:before,
    .header-transparent .navbar-icon .navbars-line:after {
        background-color: #000;
    }

    .homecounts {
        padding: 150px 0;
    }

    .track-top {
        display: flex;
        justify-content: end;
        align-items: end;

    }
</style>


<style>
    .sliderBtn {
        width: 165px;
        height: 44px;
        border: none;
        border-radius: 10px;
        background-size: 250%;
        background-position: left;
        color: #fff;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition-duration: .5s;
        overflow: hidden;
        margin-top: 30px;
        /* background: #0c1239; */
        border: 3px solid #fff;
        outline: none;
        /* padding: 10px 10px 10px 10px; */
    }

    .sliderBtn:hover {
        color: #fff;
        /* background-color: #262b90; */
    }

    .sliderbutton {
        display: flex;
        width: 100%;
    }

    .tab-content-ss button {
        border-radius: 30px;
        width: 100px;
        margin: 0 auto;
        float: right;
        font-size: 15px;
        margin-bottom: 13px;
    }

    .tab-content-ss input {
        padding: 17px;
    }

    .tab-content-ss input::placeholder {
        font-size: 15px;
    }

    .m-11 {
        margin-top: 11px;
        margin-bottom: 11px;
    }

    .sliderformremove {
        top: 8px;
        left: -10px;
    }

    .close:focus,
    .close:hover {
        cursor: pointer;
        color: #fff;
        opacity: .5;
    }

    .sliderformshow {
        padding: 0px;
        display: block;
        transform: translateY(300px) rotateX(200deg);
        transition: .4s ease-in-out;
    }

    .button-upper {
        display: flex;
        justify-content: space-between;
        align-items: end;
        padding: 0px 60px;
    }

    .topcontainerto {
        display: none;
    }

    @media (max-width: 576px) {
        .topcontainerto {
            width: 100%;
        }

        .price-inquiry-div{
            padding-left:15px;
        }
    }



    .homecounts {
    background: #0c1239; /* Dark Blue Background */
    color: white;
    text-align: center;
    padding: 50px 20px;
}

.count-title {
    font-size: 25px;
    margin-bottom: 30px;
}

.homecounts {
    background: #0c1239; /* Dark Blue Background */
    color: white;
    text-align: center;
    padding: 50px 20px;
}

.count-title {
    font-size: 24px;
    margin-bottom: 30px;
}

.homecounts {
    background: #0c1239; /* Dark Blue Background */
    color: white;
    text-align: center;
    padding: 60px 20px;
}

.count-title {
    font-size: 26px;
    font-weight: bold;
    margin-bottom: 40px;
    text-transform: uppercase;
}

.main-color {
    color: #ff3333; /* Red Highlight */
}

.download-info {
    text-align: left;
}

.download-text {
    font-size: 18px;
    margin-bottom: 20px;
    color: #d1d1d1;
}

.download-buttons a {
    display: inline-block;
    margin-right: 10px;
}

.download-buttons img {
    width: 160px;
    transition: 0.3s;
}

.download-buttons img:hover {
    transform: scale(1.1);
}

.app-image {
    width: 100px;
    max-width: 100%;
    border-radius: 6px;
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
}

.new-track-top{
    position: absolute;
    bottom: -100px;
    z-index: 5;
}

/* Responsive Fix */
@media (max-width: 768px) {
    .download-info {
        text-align: center;
    }

    .download-buttons a {
        display: block;
        margin: 10px auto;
    }

    .track-order-top {
        min-height: 0px;
        padding: 0px;
        display: flex;
        justify-content: center;
    }

    .secpadd {
        margin-top: 50px;
    }

    .button-upper{
        padding: 0px 10px;
    }

    .main-color{
        font-size: 35px;
    }
    .secpaddlf{
        padding-bottom: 0px;
    }
}

/* .custom-dropdown {
    width: 100%;
    padding: 12px;
    border: 2px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    background-color: white;
    transition: 0.3s;
} */

/* .custom-dropdown optgroup {
    font-weight: bold;
    font-size: 18px;
    padding: 5px;
} */

/* .gotogo-group {
    color:rgb(4, 0, 0); 
    font-size: 14px;
} */

/* .india-post-group {
    color:rgb(4, 0, 0); 
    font-size: 14px;
} */

/* .custom-dropdown option {
    font-weight: bold;
    padding: 8px;
} */


</style>


<!-- End Card -->

<style>
.app-link{
        margin-left: 90px;
    }
    .fh-icon-box.style-2.icon-left .fh-icon, .fh-icon-box.style-2.icon-left .img-icon{
        margin-top: 10px;
    margin-left: 10px;
    }

    #price-upper-div{
        padding: 1px 20px;
    }

    .field_submit{
        padding-bottom: 20px;
  }

.slick-slider {
    width: 90%;
    margin: 50px auto;
    position: relative;
    overflow: hidden;
    border-radius: 15px;
    box-shadow: 0 0 30px rgba(255,255,255,0.1);
}

.slick-slide {
    transition: transform 1s ease-in-out, opacity 1s ease-in-out;
}

.slick-slide img {
    width: 100%;
    height: 500px;
    object-fit: cover;
    border-radius: 15px;
    opacity: 0;
    transform: perspective(1000px) rotateY(90deg) scale(0.8);
    filter: brightness(0.7) blur(1px);
    transition: all 1.2s cubic-bezier(0.77, 0, 0.175, 1);
}

/* Active Slide Animation */
.slick-slide.slick-active img {
    opacity: 1;
    transform: perspective(1000px) rotateY(0deg) scale(1);
    filter: brightness(1) blur(0);
    box-shadow: 0 0 40px rgba(0,255,255,0.3);
    animation: glowPulse 2s ease-in-out infinite alternate;
}

/* Glow pulse effect */
@keyframes glowPulse {
    from { box-shadow: 0 0 30px rgba(0,255,255,0.2); }
    to   { box-shadow: 0 0 60px rgba(0,255,255,0.6); }
}

/* Dots styling */
.slick-dots li button:before {
    font-size: 12px;
    color: cyan;
    opacity: 0.5;
}
.slick-dots li.slick-active button:before {
    color: #00ffff;
    opacity: 1;
}

/* ========================= */
/* 📱 MOBILE RESPONSIVE STYLES */
/* ========================= */

/* For tablets and smaller laptops */
@media (max-width: 1024px) {
    .slick-slider {
        width: 95%;
        margin: 30px auto;
    }

    .slick-slide img {
        height: 400px;
    }
}

/* For mobile devices */
@media (max-width: 768px) {
    .slick-slider {
        width: 100%;
        margin: 20px auto;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0,255,255,0.1);
    }

    .slick-slide img {
        height: 300px;
        border-radius: 10px;
    }

    .slick-dots li button:before {
        font-size: 10px;
    }

    .count-title{
        font-size: 22px !important;
    }
    .app-link{
        margin-left: 0px !important;
    }
    .fh-icon-box.style-2.icon-left .fh-icon, .fh-icon-box.style-2.icon-left .img-icon{
        margin-top: 30px !important;
    margin-left: 10px !important;
    }
    #price-upper-div{
        padding: 1px 20px;
    }

    .price-inquiry-div h2{
        text-align: center;
    }

    .paddbtm40 {
    padding-bottom: 0px !important;
}
  .field_submit{
        padding-bottom: 20px;
  }
}

/* For very small phones */
@media (max-width: 480px) {
    .slick-slider {
        margin: 10px auto;
    }

    .slick-slide img {
        height: 220px;
        border-radius: 8px;
    }

    .slick-dots li button:before {
        font-size: 9px;
    }
    .track-order-top, .slick-slider{
        margin-bottom: 0px;
    }
    .new-track-top{
        bottom: 0;
    }
}

</style>



<div class="track-order-top">
   
    <div class="slick-slider">
        <div class="slick-slide"><img src="<?php echo e(url('website/images/bg/go2go1.jpeg')); ?>" alt="Slide 1"></div>
        <div class="slick-slide"><img src="<?php echo e(url('website/images/bg/go2go2.jpeg')); ?>" alt="Slide 2"></div>
c        <div class="slick-slide"><img src="<?php echo e(url('website/images/bg/go2go4.jpeg')); ?>" alt="Slide 4"></div>
        <div class="slick-slide"><img src="<?php echo e(url('website/images/bg/go2go5.jpeg')); ?>" alt="Slide 5"></div>
    </div>

    <div class="new-track-top col-lg-12">
        <div class="row" style="display: flex; justify-content: end;">
            <div class="col-md-4" style="position:relative;overflow: hidden;">
                <div class="content content-upper sliderformshow">
                    <button type="button" class="close custom-close sliderformremove" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <div class="tab-content-ss">
                        <div id="1" class="container-fluid tab-pane">
                            <form action="<?php echo e(route('website.trackholder')); ?>">
                                <div class="form-group m-11">
                                    <label for="exampleFormControlInput1"><strong>Enter Article No.</strong></label>
                                    <input type="text" class="form-control is-valid" id="trackOrder" name="trackOrder" placeholder="Enter Article No.">
                                </div>
                                <button type="submit" style=" backgrond:     class #1a2674;" class="btn btn-primary font-20 mt-5">submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="button-upper ">
            <button data-toggle="modal" data-target="#exampleModalCenter" class="shipNowButton">
            </button>
            <button type="button" class="sliderBtn fh-btn btn">Track Order</button>
        </div>
    </div>

</div> 





<?php $__env->startPush('script'); ?>
<script>
    $(document).ready(function() {
        $('.sliderBtn').click(function() {
            $('.sliderformshow').css('transform', 'translateY(0px) rotateX(0deg)');
            $('.topcontainerto').css('display', 'block');
        });

        $('.sliderformremove').click(function() {
            $('.sliderformshow').css('transform', 'translateY(300px) rotateX(200deg)');
            $('.topcontainerto').css('display', 'none');
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
<?php $__env->stopPush(); ?>

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
                    <form action="<?php echo e(route('website.pickupDetailsStore')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Name <span class="text-danger">*</span></label>
                                    <input class="form-control" name="name" placeholder="Enter name" type="text" value="<?php echo e(old('name')); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                    <input class="form-control" name="email" placeholder="Enter email" type="text" value="<?php echo e(old('email')); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Phone <span class="text-danger">*</span></label>
                                    <input class="form-control" name="phone" placeholder="Enter phone" type="text" value="<?php echo e(old('phone')); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label">City <span class="text-danger">*</span></label>
                                    <input class="form-control" id="city" name="city" placeholder="Enter city" type="text" value="<?php echo e(old('city')); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label">State <span class="text-danger">*</span></label>
                                    <input class="form-control" id="state" name="state" placeholder="Enter City" type="text" value="<?php echo e(old('city')); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-block mb-3">
                                    <label class="col-form-label">Pincode <span class="text-danger">*</span></label>
                                    <input class="form-control" id="pincode" name="pincode" placeholder="Enter Pincode" type="text" value="<?php echo e(old('pincode')); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class  align mb-3">
                                    <label class="col-form-label">Address <span class="text-danger">*</span></label>
                                    <input class="form-control" name="address" placeholder="Enter Address" type="text" value="<?php echo e(old('address')); ?>" required>
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

<?php $__env->startPush('script'); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Check if session has a success message
        <?php if(session('success')): ?>
            Swal.fire({
                icon: "success",
                title: "Success!",
                text: "<?php echo e(session('success')); ?>",
                confirmButtonText: "OK"
            });
        <?php endif; ?>

        // Check if session has an error message
        <?php if(session('error')): ?>
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "<?php echo e(session('error')); ?>",
                confirmButtonText: "OK"
            });
        <?php endif; ?>
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
                url: "<?php echo e(route('website.getPinCodes')); ?>",
                type: 'Post',
                data: {
                    pincode: pincode,
                    city: city,
                    state: state,
                    _token: '<?php echo e(csrf_token()); ?>'
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
<?php $__env->stopPush(); ?>



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
        <div class="fh-section-title clearfix  text-center version-dark paddbtm20">
            <h2>Welcome to GOTOGO <span class="main-color">POST</span></h2>
        </div>
        <p class="haeadingpara text-center paddbtm40">GOTOGO POST is more than logistics.
<br /> We help optimize your packaging, manage material sourcing, and offer much more to support your business.
        </p>
        <div class="welservices row">
            <div class="col-md-4 col-sm-6">
                <div class="fh-icon-box icon-type-theme_icon style-1 version-dark hide-button icon-left">
                    <span class="fh-icon"><img alt="1764" src="<?php echo e(asset('website/images/express.png')); ?>" style="height:100px;"></span>
                    <h4 class="box-title"><a href="#" style="color:#000">Express Parcels</a></h4>
                    <div class="desc">
                        <p>From time-critical express delivery to cost-effective ground solutions, our Express Parcels Vertical serves both C2C and B2B customers. We handle everything from documents to part-truck-load shipments, offering reliable day-definite and time-definite options for all your shipping needs.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="fh-icon-box icon-type-theme_icon style-1 version-dark hide-button icon-left">
                    <span class="fh-icon"><img alt="1764" src="<?php echo e(asset('website/images/inter.png')); ?>" style="height:100px;"></span>
                    <h4 class="box-title"><a href="#" style="color:#000">International Shipping</a></h4>
                    <div class="desc">
                        <p>We provide a variety of international shipping options for both individual and business customers. From urgent document delivery to budget-friendly parcel solutions, we meet all international shipping needs with reliable and flexible services.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="fh-icon-box icon-type-theme_icon style-1 version-dark hide-button icon-left">
                    <span class="fh-icon"><img alt="1764" src="<?php echo e(asset('website/images/pracel.png')); ?>" style="height:100px;"></span>
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

<section class="homecounts">
    <div class="container">
        <h2 class="count-title">
            <span class="main-color">GOTOGOPOST</span> - Download Our Mobile App for Seamless Services!
        </h2>

        <div class="row align-items-center mobile-app-img" style="display:flex">
            <!-- Left Section: Download Info -->
            <div class="col-lg-6 col-md-6 app-link" style="">
                <div class="download-info">
                    <p class="download-text">
                        Experience the best courier & logistics services with our mobile app.
                    </p>
                    <div class="download-buttons">
                        <a href="https://play.google.com/store/apps/details?id=com.gotogopost.customer&pcampaignid=web_share" target="_blank" class="download-btn">
                            <img src="<?php echo e(asset('website/images/google-play.svg')); ?>" alt="Google Play">
                        </a>
                        <a href="#" class="download-btn">
                            <img src="<?php echo e(asset('website/images/apple-store.svg')); ?>" alt="App Store">
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Section: App Image -->
            <div class="col-lg-6 col-md-6 text-center">
                <img src="<?php echo e(asset('website/images/app-img.jpg')); ?>" alt="App Preview" class="app-image">
            </div>
        </div>
    </div>
</section>



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
                                        <select id="services" name="service" class="form-control custom-dropdown" style="color:#000;border:none">
                                            <!-- <option value="" style="color:#fff;" disabled selected>Select Service</option> -->
                                            <optgroup label="GOTOGO POST BOOKING SERVICES" class="gotogo-group" style="color:#000;background:#fff;">
                                                <option value="1" style="color:#fff;background:#ff0000; text-justify: inter-word;text-align: justify;">Gotogo Super Speed Packet</option>
                                                <option value="3" style="color:#fff;background:#ff0000;">Gotogo Express Parcel</option>
                                                <option value="4" style="color:#fff;background:#ff0000;">Gotogo Secure Parcel</option>
                                            </optgroup>
                                            <optgroup label="ALL INDIA-POST SERVICES" class="india-post-group" style="color:#000;background:#fff">
                                                <option value="5" style="color:#fff;background:#ff0000;">Speed Post-Inland Document</option>
                                                <option value="6" style="color:#fff;background:#ff0000;">Speed Post-Parcel Domestic</option>
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
<!--why choose us end -->

<!--partener -->
<!-- <section class="partener-1 bluebg">
    <div class="container">
        <div class="fh-partner clearfix">
            <div class="list-item slide-partener">
                <div class="partner-item">
                    <div class="partner-content">
                        <a href="#" target="_self"><img alt="1764" src="<?php echo e(asset('website/images/partener/partner-4.png')); ?>"></a>
                    </div>
                </div>
                <div class="partner-item">
                    <div class="partner-content">
                        <a href="#" target="_self"><img alt="1763" src="<?php echo e(asset('website/images/partener/partner-4.png')); ?>"></a>
                    </div>
                </div>
                <div class="partner-item">
                    <div class="partner-content">
                        <a href="#" target="_self"><img alt="1762" src="<?php echo e(asset('website/images/partener/partner-4.png')); ?>"></a>
                    </div>
                </div>
                <div class="partner-item">
                    <div class="partner-content">
                        <a href="#" target="_self"><img alt="1761" src="<?php echo e(asset('website/images/partener/partner-4.png')); ?>"></a>
                    </div>
                </div>
                <div class="partner-item">
                    <div class="partner-content">
                        <a href="#" target="_self"><img alt="1765" src="<?php echo e(asset('website/images/partener/partner-4.png')); ?>"></a>
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
                        <span class="testi-icon"><i class="flaticon-quotations"></i></span>
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
                        <span class="testi-icon"><i class="flaticon-quotations "></i></span>
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
                        <span class="testi-icon"><i class="flaticon-quotations "></i></span>
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
                        <a href="#"><img src="<?php echo e(asset('website/images/blogs/blog-img2.jpeg')); ?>" alt="" /></a>
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
                        <a href="#"><img src="<?php echo e(asset('website/images/blogs/blog-8.jpg')); ?>" alt="" /></a>
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
                        <a href="#"><img src="<?php echo e(asset('website/images/blogs/blog-8.jpg')); ?>" alt="" /></a>
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
                        <a href="#"><img src="<?php echo e(asset('website/images/blogs/blog-8.jpg')); ?>" alt="" /></a>
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
                        <a href="#"><img src="<?php echo e(asset('website/images/blogs/blog-8.jpg')); ?>" alt="" /></a>
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
                        <a href="#"><img src="<?php echo e(asset('website/images/blogs/blog-8.jpg')); ?>" alt="" /></a>
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
                        <a href="#"><img src="<?php echo e(asset('website/images/blogs/blog-8.jpg')); ?>" alt="" /></a>
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
                        <a href="#"><img src="<?php echo e(asset('website/images/blogs/blog-8.jpg')); ?>" alt="" /></a>
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




<?php $__env->startPush('script'); ?>

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
            var csrfToken = "<?php echo e(csrf_token()); ?>";

           

            $.ajax({
                type: 'POST',
                url: "<?php echo e(route('website.getPrice')); ?>",
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
            autoplaySpeed: 3000,
            dots: true,
            arrows: false,
            fade: true
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



<?php $__env->stopPush(); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('website.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\GotogoPost\gotogopost_backup\resources\views/website/index.blade.php ENDPATH**/ ?>