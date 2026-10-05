@extends('admin.layouts.master')

@section('title', 'Sales Marketing Management')

@push('add-modal-code')
<div class="col-auto float-end ms-auto">
    <a href="{{ route('admin.m_manager.create') }}"
       class="btn btn-primary btn-rounded shadow-sm px-4 py-2 font-weight-semibold">
        <i class="fa-solid fa-plus me-1"></i> Add Manager
    </a>
</div>
@endpush

@section('content')

<style>
    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        display: block;
        margin-bottom: 1rem;
    }

    div.dataTables_wrapper div.dataTables_filter input {
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        padding: 6px 12px;
        outline: none;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .card {
        border: none;
        box-shadow:
            0 4px 6px -1px rgba(0, 0, 0, 0.05),
            0 2px 4px -1px rgba(0, 0, 0, 0.03);
        border-radius: 12px;
    }

    .custom-table thead th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 16px;
        white-space: nowrap;
    }

    .custom-table tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
    }

    .avatar img {
        border-radius: 50%;
        object-fit: cover;
        width: 40px;
        height: 40px;
        border: 2px solid #e2e8f0;
    }

    .action-icon {
        color: #64748b;
        background: #f8fafc;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: background 0.2s;
    }

    .action-icon:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .dropdown-menu {
        border: none;
        box-shadow:
            0 10px 15px -3px rgba(0, 0, 0, 0.1),
            0 4px 6px -4px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 6px;
    }

    .dropdown-item {
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 13px;
        color: #334155;
        font-weight: 500;
    }

    .dropdown-item:hover {
        background-color: #f1f5f9;
        color: #0f172a;
    }

    .manager-id {
        font-size: 11px;
        color: #64748b;
    }
</style>


<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered custom-table datatable table-hover">

                        <thead>
                            <tr>
                                <th>SN#</th>
                                <th class="text-start">Profile</th>
                                <th class="text-start">Name</th>
                                <th class="text-start">Phone</th>
                                <th class="text-start">Email</th>
                                <th class="text-start">City</th>
                                <th class="text-start">State</th>
                                <th class="text-start">Created At</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($managers as $i => $data)

                            <tr>

                                <td class="text-start font-weight-medium">
                                    {{ $i + 1 }}
                                </td>

                                <td class="text-start">
                                    <h2 class="table-avatar m-0">

                                        @if ($data->managerkyc && $data->managerkyc->photo)

                                            <a href="#" class="avatar">
                                                <img
                                                    src="{{ asset('admin/m_manager/'.$data->generated_id.'/'.$data->managerkyc->photo) }}"
                                                    alt="Profile">
                                            </a>

                                        @else

                                            <a href="#" class="avatar">
                                                <img
                                                    src="{{ asset('admin/assets/img/profiles/avatar-19.jpg') }}"
                                                    alt="Default Avatar">
                                            </a>

                                        @endif

                                    </h2>
                                </td>

                                <td class="text-start">

                                    <h2 class="table-avatar m-0">

                                        <a href="{{ route('admin.m_manager.view', $data->id) }}"
                                           class="text-dark font-weight-semibold text-decoration-none">

                                            {{ $data->name }}

                                        </a>

                                        <span class="d-block manager-id">
                                            ID: {{ $data->generated_id ?? $data->id }}
                                        </span>

                                    </h2>

                                </td>

                                <td class="text-start">
                                    {{ $data->mobile ?? '-' }}
                                </td>

                                <td class="text-start">
                                    {{ $data->email ?? '-' }}
                                </td>

                                <td class="text-start">
                                    {{ $data->city ?? '-' }}
                                </td>

                                <td class="text-start">
                                    {{ $data->state ?? '-' }}
                                </td>

                                <td class="text-start" style="font-size: 13px;">

                                    @if($data->created_at)
                                        {{ date('d M Y, h:i A', strtotime($data->created_at)) }}
                                    @else
                                        -
                                    @endif

                                </td>

                                <td class="text-center">

                                    <button
                                        class="btn btn-sm btn-{{ $data->status == 1 ? 'success' : 'danger' }}"
                                        onclick="status_update('{{ $data->id }}', '{{ $data->status }}')"
                                        style="width: 80px; height: 32px;">

                                        {{ $data->status == 1 ? 'Active' : 'Inactive' }}

                                    </button>

                                </td>

                                <td class="text-center">

                                    <div class="dropdown dropdown-action">

                                        <a href="#"
                                           class="action-icon dropdown-toggle"
                                           data-bs-toggle="dropdown"
                                           aria-expanded="false">

                                            <i class="material-icons"
                                               style="font-size: 18px;">
                                                more_vert
                                            </i>

                                        </a>

                                        <div class="dropdown-menu dropdown-menu-end">

                                            <a class="dropdown-item"
                                               href="{{ route('admin.m_manager.view', $data->id) }}">

                                                <i class="fa-regular fa-eye m-r-5 text-info"></i>
                                                View

                                            </a>

                                            <a class="dropdown-item"
                                               href="{{ route('admin.m_manager.edit', $data->id) }}">

                                                <i class="fa-solid fa-pencil m-r-5 text-primary"></i>
                                                Edit

                                            </a>

                                            <a class="dropdown-item"
                                               href="{{ route('admin.m_manager.securityDetails', $data->id) }}">

                                                <i class="fa-regular fa-credit-card m-r-5 text-secondary"></i>
                                                Advance Amount

                                            </a>

                                            <a class="dropdown-item"
                                               href="{{ route('admin.m_manager.creditDetails', $data->id) }}">

                                                <i class="fa-regular fa-credit-card m-r-5 text-secondary"></i>
                                                Credit Amount

                                            </a>

                                            <div class="dropdown-divider"></div>

                                            <a class="dropdown-item text-danger"
                                               href="#"
                                               onclick="delete_modal('{{ $data->id }}')">

                                                <i class="fa-regular fa-trash-can m-r-5"></i>
                                                Delete

                                            </a>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="10" class="text-center py-4">

                                    <div class="text-muted">

                                        <i class="fa-solid fa-users-slash fa-2x mb-2"></i>

                                        <p class="mb-0">
                                            No Sales Marketing Manager found.
                                        </p>

                                    </div>

                                </td>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>
        </div>

    </div>
</div>


{{-- DELETE MODAL --}}

<div class="modal custom-modal fade"
     id="delete_modal"
     role="dialog">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg"
             style="border-radius: 12px;">

            <div class="modal-body text-center p-4">

                <div class="form-header mb-3">

                    <div class="mb-3">
                        <i class="fa-solid fa-triangle-exclamation text-danger fa-3x"></i>
                    </div>

                    <h3 class="fw-bold">
                        Delete Manager
                    </h3>

                    <p class="text-muted">
                        Are you sure you want to delete this record?
                        This action cannot be undone.
                    </p>

                </div>

                <div class="modal-btn delete-action">

                    <div class="row g-2">

                        <div class="col-6">

                            <a href=""
                               id="delete_button"
                               class="btn btn-danger w-100 py-2">

                                Delete

                            </a>

                        </div>

                        <div class="col-6">

                            <a href="javascript:void(0);"
                               data-bs-dismiss="modal"
                               class="btn btn-light w-100 py-2">

                                Cancel

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


@push('page-javascript')

<script type="text/javascript">

    function delete_modal(id)
    {
        const deleteUrl =
            "{{ route('admin.m_manager.delete', ['id' => ':id']) }}"
            .replace(':id', id);

        $('#delete_button').attr('href', deleteUrl);

        $('#delete_modal').modal('show');
    }


    function status_update(id, status)
    {
        let update_status;

        if (status === 1 || status === '1') {
            update_status = 0;
        } else {
            update_status = 1;
        }

        $.ajax({

            url: "{{ route('admin.m_manager.status') }}",

            type: "POST",

            data: {
                "_token": "{{ csrf_token() }}",
                id: id,
                status: update_status
            },

            success: function(data)
            {
                if (data.success == true) {

                    Swal.fire({
                        title: 'Success!',
                        text: 'Status updated successfully',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });

                } else {

                    Swal.fire(
                        'Error!',
                        'Something went wrong',
                        'error'
                    );
                }
            },

            error: function()
            {
                Swal.fire(
                    'Error!',
                    'Unable to update status',
                    'error'
                );
            }

        });
    }

</script>

@endpush

@endsection