@extends('admin.layouts.master')

@section('title') {{ $listBuilderClass::$name }} @endsection

@section('content')
@php   @endphp
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-dark py-3 text-white" style="flex-direction: row-reverse;">
                <div class="card-widgets">
                    <div class="btn-group btn-group-md" role="group">
                        @if($listBuilderClass::createUrl())
                        {{-- @can($listBuilderClass::$permissionPrefix . '-create')--}}
                        <a href="{{ $listBuilderClass::createUrl() }}" class="btn btn-secondary waves-effect waves-light font-weight-bold">
                            <i class="uil uil-plus"></i> Create
                        </a>
                        {{-- @endcan--}}
                        @endif
                    </div>

                </div>
                <h5 class="card-title mb-0 text-white">{{ $listBuilderClass::$name }}</h5>
            </div>
            <div id="filters">
                <div class="card-body">
                    <form action="{{ request()->fullUrl() }}" id="filterForm">
                        <div class="row">
                            @foreach($listBuilderClass::columns() as $column)
                            @if($column->filterType)
                            {!! $column->render() !!}
                            @endif
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <a href="{{ request()->url() }}" class="btn btn-danger waves-effect waves-light font-weight-bold">
                                    Reset
                                </a>
                                <button type="submit" name="filter" value="filter" onclick="shouldExport = false;" class="btn btn-primary waves-effect waves-light font-weight-bold">
                                    Apply Filter
                                </button>
                                <!-- <button type="submit" name="export" value="csv" onclick="shouldExport = true;" class="btn btn-secondary waves-effect waves-light font-weight-bold float-right">
                                    Export
                                </button> -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
            {!! $listBuilderClass::beforeDataTable() !!}
                <div class="table-responsive">
                    <table class="table table-striped custom-table datatable" id="dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                @foreach($listBuilderClass::columns() as $column)
                                {{-- @if($column->hasPermission($listBuilderClass::$permissionPrefix))--}}
                                <th>{{ $column->title() }}</th>
                                {{-- @endif--}}
                                @endforeach
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-datatable')
<script>
        var dataTable = $('#dataTable').DataTable({
            ajax: {
                url: "{{request()->fullurl()}}",
            },
            columns: [
                {data: 'DT_RowIndex', width: '5%'},
                    @foreach($listBuilderClass::columns() as $column)
{{--                    @if($column->hasPermission($listBuilderClass::$permissionPrefix))--}}
                {
                    data: "{{ $column->property }}"
                },
{{--                @endif--}}
                @endforeach
            ]
        });

    </script>
@endpush
