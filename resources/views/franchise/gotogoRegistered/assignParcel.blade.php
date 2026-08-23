@extends('franchise.layouts.master')

@section('title') Assign Parcel @endsection

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

<style>
    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem; /* Adjust gap as needed */
    }
    .task {
        width: 100%; /* Each task takes full width of the grid column */
    }
    .task-container {
        display: flex;
        align-items: center;
    }
    @media (max-width: 992px) {
        .grid-container {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 500px) {
        .task-wrapper .task-list-body #task-list li .task-container {

    flex-direction: column;
}
    }
</style>

@push('add-modal-code')
<div class="col-auto float-end ms-auto">
    <a href="#" class="btn add-btn"><i class="fa-solid fa-plus"></i> Add</a>
    <div class="view-icons">
        <a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
        <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa-solid fa-bars"></i></a>
    </div>
</div>
@endpush

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="row staff-grid-row">
    <div class="task-wrapper">
        <div class="task-list-container">
            <div class="task-list-body">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="task-assign mb-3">
                                <a class="task-complete-btn task-completed" id="task_complete" href="javascript:void(0);">
                                    <i class="material-icons">check</i> Select All
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <ul id="task-list" class="grid-container">
                                @if (count($parcel) > 0)
                                    @foreach ($parcel as $item)
                                        @php
                                            $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                                            $code = $item->order_no;
                                            $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);
                                            $barcode = base64_encode($barcode);
                                        @endphp
                                        <li class="task" data="{{$item->id}}">
                                            <div class="task-container">
                                                <span class="task-action-btn task-check">
                                                    <span class="action-circle large {{ $item->bag_id == $bagId ? 'selected' : '' }}">
                                                        <i class="material-icons task-check">check</i>
                                                    </span>
                                                </span>
                                                <span>{{$item->order_no}}</span>
                                                <img src="data:image/png;base64,{{ $barcode }}" alt="Barcode" style="height:20px;margin-left:20px;"/>
                                            </div>
                                        </li>
                                    @endforeach
                                @else
                                    <p>No result found</p>
                                @endif
                            </ul>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="mt-3 d-flex justify-content-end">
    {{ $parcel->links() }}
</div>



@push('page-javascript')
<script>
    $(document).ready(function() {
        // Select All functionality

        let selectAll = true;

        $('#task_complete').on('click', function() {
            if (selectAll) {
                $('.action-circle').addClass('selected');
                $('#task_complete').html('<i class="material-icons">check</i> Unselect All');
            } else {
                $('.action-circle').removeClass('selected');
                $('#task_complete').html('<i class="material-icons">check</i> Select All');
            }
            selectAll = !selectAll;  // Toggle the state
        });

        $(document).on('click', '.action-circle', function() {
            $(this).toggleClass('selected');
        });

        $(document).on('click', '.add-btn', function() {

            var selectedParcelArray = $('#task-list .task').filter(function() {
                return $(this).find('.action-circle').hasClass('selected');
            }).map(function() {
                return $(this).attr('data');
            }).get();

            $.ajax({
                url: "{{ route('franchise.bag.assignParcel', ['id' => $bagId]) }}", 
                    type: 'POST',
                    data: {
                        selectedParcelArray: selectedParcelArray,
                        _token: '{{ csrf_token() }}'  
                    },
                    success: function(response) {
                        console.log("reached here",response)
                        window.location.href = "{{ route('franchise.bag.viewParcel', ['id' => $bagId]) }}";
                    },
                    error: function(xhr, status, error) {
                        console.log('Error:', error);
                        // Handle error response
                    }
                });
            });
        });
</script>

@endpush
@endsection
