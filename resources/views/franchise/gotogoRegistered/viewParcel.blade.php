@extends('franchise.layouts.master')

@section('title')  {{\App\Models\Admin::GOTOGO_POST_REGISTERED}} All Parcel @endsection

@section('content')


<style>
    .submit-section {
    text-align: center;
    margin-top: 0px;
    float: left;
    width: 100%;
}

.selected{
    background: #35BA67 !important;
    border: 1px solid #35BA67 !important;
    color: #ffffff;
}

.task-wrapper .task-list-body #task-list li .task-container {
    display: flex;
    justify-content: space-between;
    background: #ffffff;
    width: 100%;
    border: 1px solid #eaeaea;
    border-bottom: none;
    box-sizing: border-box;
    position: relative;
    padding: 8px 15px;
    -webkit-transition: all 0.2s ease;
    -ms-transition: all 0.2s ease;
    transition: all 0.2s ease;
}
</style>



@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Search Filter -->
<div class="row filter-row">
    <div class="col-sm-6 col-md-3">
        <div class="input-block mb-3 form-focus">
            <input type="text" class="form-control floating input1">
            <label class="focus-label">Pin Code</label>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="input-block mb-3 form-focus">
            <input type="text" class="form-control floating input2">
            <label class="focus-label">Name</label>
        </div>
    </div>

    <div class="col-sm-6 col-md-3">
        <div class="d-grid">
            <a href="#" class="btn btn-success search"> Search </a>
        </div>
    </div>
</div>
<!-- Search Filter -->


<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped custom-table datatable">
                        <thead>
                            <tr>
                                <th>SN#</th>
                                <th class="text-center">Parcel No</th>
                                <th class="text-center">Barcode No</th>
                                <th class="text-center">Barcode</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($parcel as $i => $data)

                        @php
                            $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                            $code =   $data->order_no;
                            $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);
                            $barcode = base64_encode($barcode);
                        @endphp
                            <tr>
                                <td>{{$i+1}}</td>
                                <td>
                                    <h2 class="table-avatar">
                                        <a href="#">{{$data->id}}</a>
                                    </h2>
                                </td>
                                <td>{{$data->order_no}}</td>
                                <td><img  src="data:image/png;base64,{{ $barcode }}" alt="Barcode" style="height:20px;margin-left:20px;"/> </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




@push('page-javascript')
<script>
    

    
</script>

@endpush
@endsection
