<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Smarthr - Bootstrap Admin Template">
    <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <title>@yield('title')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.css" rel="stylesheet" type="text/css" />

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('admin/assets/img/favicon.png')}}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('admin/assets/css/bootstrap.min.css')}}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{asset('admin/assets/plugins/fontawesome/css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin/assets/plugins/fontawesome/css/all.min.css')}}">

    <!-- Lineawesome CSS -->
    <link rel="stylesheet" href="{{asset('admin/assets/css/line-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin/assets/css/material.css')}}">
    <link rel="stylesheet" href="{{asset('admin/assets/css/ckeditor.css')}}">


    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{asset('admin/assets/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('admin/assets/css/select2.min.css')}}">

    <!-- Datetimepicker CSS -->
    <link rel="stylesheet" href="{{asset('admin/assets/css/bootstrap-datetimepicker.min.css')}}">
    <!-- Tagsinput CSS -->
    <link rel="stylesheet" href="{{asset('admin/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{asset('admin/assets/css/style.css')}}">


</head>


<style>
  .btn-primary
 {
    background-color: #de0114 !important;
    border: 1px solid #de0114 !important;
 }

 .add-btn {
     background-color: #de0114 !important;
    border: 1px solid #de0114 !important;
 }

   /* Sidebar base style */
[data-sidebar=dark] .sidebar {
    box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2);
    background-color: #fff !important;
}

/* Menu title style */
.sidebar .sidebar-menu ul li.menu-title,
.two-col-bar .sidebar-menu ul li.menu-title {
    color: #fff;
    font-size: 14px;
    opacity: 1;
    padding: 5px 15px;
    white-space: nowrap;
}

/* Link default color */
.sidebar .sidebar-menu ul li a,
.two-col-bar .sidebar-menu ul li a {
    color: #fff;
    transition: all 0.2s ease-in-out;
}

/* Hover style for <li> */
.sidebar .sidebar-menu ul li:hover,
.two-col-bar .sidebar-menu ul li:hover {
    background-color: #fff !important;
}

/* Hover style for <a> inside li */
.sidebar .sidebar-menu ul li a:hover,
.two-col-bar .sidebar-menu ul li a:hover {
    color: #000 !important;
}

/* Active link */
.sidebar .sidebar-menu ul li.active a,
.two-col-bar .sidebar-menu ul li.active a {
    color: #000 !important;
    background-color: #fff !important;
}

/* Sidebar hover behavior for submenus */
.sidebar-menu ul.sidebar-vertical:hover li ul li a {
    color: #000 !important;
    background-color: #fff !important;
}

/* Submenu icons color on hover */
.sidebar-menu ul.sidebar-vertical:hover li ul li a i {
    color: #000 !important;
}

/* Active submenu link */
.sidebar-menu ul.sidebar-vertical li ul li a.active {
    color: #000 !important;
    background-color: #fff !important;
}

</style>

<style>
    /* Modal Tracking Styles */
    .tracking-container {
        padding: 15px;
        background: #f9f9f9;
        border-radius: 8px;
        margin: 20px 0;
    }

    .step-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #ccc;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 14px;
        margin-right: 10px;
    }

    .step-title {
        font-weight: bold;
        margin: 0;
        font-size: 15px;
        color: #333;
    }

    .step-date,
    .step-location {
        margin: 0;
        font-size: 14px;
        color: #777;
    }

    .tracking-steps .tracking-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .tracking-steps .tracking-item .step-icon {
        width: 40px;
        height: 40px;
    }

    
    .tracking-steps li.in-progress .step-icon {
        background-color: #1e3d59; /* Yellow for current step */
    }

    .tracking-steps li .step-icon {
        background-color: #ccc; /* Gray for pending steps */
    }

    .tracking-steps li .step-icon {
        transition: background-color 0.3s ease;
    }
    .slimscroll{
        background: #1e3d59 !important;
    }
</style>

<body>
    <!-- Main Wrapper -->
    <div class="main-wrapper">

        @include('franchise.layouts.header')
        @include('franchise.layouts.sidebar')

        <!-- Page Wrapper -->
        <div class="page-wrapper">
            <div class="content container-fluid">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col">
                            <h3 class="page-title">@yield('title')</h3>
                            <ul class="breadcrumb">
                                @foreach(Request::segments() as $segment)
                                <li class="breadcrumb-item"><a href="{{ url(implode('/', array_slice(Request::segments(), 0, $loop->iteration))) }}"> {{ ucwords(str_replace('-', ' ', $segment)) }}</a></li>
                                @endforeach
                            </ul>
                        </div>

                        @yield('header-right-part')


                        @stack('add-modal-code')

                    </div>

                </div>
                <!-- /Page Header -->

                @yield('content')

            </div>
        </div>
        <!-- /Page Wrapper -->

    </div>
    <!-- /Main Wrapper -->

    @include('franchise.layouts.customizer')

    <!-- jQuery -->

    <script src="{{asset('admin/assets/js/jquery-3.7.0.min.js')}}"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{asset('admin/assets/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Slimscroll JS -->
    <script src="{{asset('admin/assets/js/jquery.slimscroll.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/select2.min.js')}}"></script>

    <script src="{{asset('admin/assets/js/moment.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/bootstrap-datetimepicker.min.js')}}"></script>

    <script src="{{asset('admin/assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/ckeditor.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.min.js"></script>
    <!-- Tagsinput JS -->
    {{-- <script src="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script> --}}


    @stack('page-javascript')

    <!-- Theme Settings JS -->
    <script src="{{asset('admin/assets/js/layout.js')}}"></script>
    <script src="{{asset('admin/assets/js/theme-settings.js')}}"></script>
    <script src="{{asset('admin/assets/js/greedynav.js')}}"></script>

    <!-- Custom JS -->
    <script src="{{asset('admin/assets/js/app.js')}}"></script>
    <script src="{{asset('franchise/js/custom.js')}}"></script>


    <!-- <script src="{{ asset('js/app.min.js') }}"></script> -->
    <!-- <script src="{{asset('js/scripts.js')}}"></script> -->
    @stack('page-datatable')


    @if(Session::has('success'))
    <script>
        Swal.fire('Success!', "{{ Session::get('success') }}", 'success');

    </script>
    @endif


    @if(Session::has('error'))
    <script>
        Swal.fire('Error!', "{{ Session::get('error') }}", 'error')
    </script>
    @endif

</body>

</html>