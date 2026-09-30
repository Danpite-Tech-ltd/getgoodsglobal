@extends('backEnd.layouts.master')
@section('title', 'Measurement Unit Manage')

@section('css')
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <button type="button" class="btn btn-primary rounded-pill waves-effect waves-light" id="addUnitBtn">
                        <i class="fe-plus"></i> Add Measurement Unit
                    </button>
                </div>
                <h4 class="page-title">Measurement Unit Manage</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Title</th>
                                <th>Value</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($data as $value)
                                <tr id="unit-row-{{ $value->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $value->title }}</td>
                                    <td>{{ $value->value }}</td>
                                    <td>
                                        @if ($value->status == 1)
                                            <span class="badge bg-soft-success text-success">Active</span>
                                        @else
                                            <span class="badge bg-soft-danger text-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="button-list">
                                            <button type="button"
                                                class="btn btn-xs btn-primary waves-effect waves-light edit-unit-btn"
                                                data-id="{{ $value->id }}" title="Edit">
                                                <i class="fe-edit-1"></i>
                                            </button>
                                            <button type="button"
                                                class="btn btn-xs btn-danger waves-effect waves-light delete-unit-btn"
                                                data-id="{{ $value->id }}" title="Delete">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><!-- end card body-->
            </div><!-- end card -->
        </div><!-- end col-->
    </div>

</div>

<!-- Measurement Unit Add/Edit Modal -->
<div class="modal fade" id="unitModal" tabindex="-1" aria-labelledby="unitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unitModalLabel">Add Measurement Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="unitForm">
                @csrf
                <input type="hidden" id="unit_id" name="unit_id">
                <input type="hidden" id="unit_method" name="_method" value="POST">

                <div class="modal-body">
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="unit_title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="unit_title" name="title"
                            placeholder="e.g. Kilogram" required>
                        <div class="invalid-feedback" id="title-error"></div>
                    </div>

                    <!-- Value -->
                    <div class="mb-3">
                        <label for="unit_value" class="form-label">Value <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="unit_value" name="value"
                            placeholder="e.g. kg" required>
                        <div class="invalid-feedback" id="value-error"></div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="unit_status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="unit_status" name="status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <div class="invalid-feedback" id="status-error"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success waves-effect waves-light" id="unitSubmitBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- third party js -->
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/libs/pdfmake/build/vfs_fonts.js"></script>
<script src="{{ asset('/public/backEnd/') }}/assets/js/pages/datatables.init.js"></script>
<!-- third party js ends -->

<script>
    $(document).ready(function () {

        var baseUrl    = "{{ url('admin/mesurement') }}";
        var csrfToken  = "{{ csrf_token() }}";

        // ---- Helper: reset validation errors ----
        function resetErrors() {
            $('#unit_title, #unit_value, #unit_status').removeClass('is-invalid');
            $('#title-error, #value-error, #status-error').text('').hide();
        }

        // ---- Open ADD modal ----
        $('#addUnitBtn').on('click', function () {
            resetErrors();
            $('#unitForm')[0].reset();
            $('#unit_id').val('');
            $('#unit_method').val('POST');
            $('#unit_status').val('1');
            $('#unitModalLabel').text('Add Measurement Unit');
            $('#unitSubmitBtn').text('Save');
            $('#unitModal').modal('show');
        });

        // ---- Open EDIT modal ----
        $(document).on('click', '.edit-unit-btn', function () {
            resetErrors();
            var id = $(this).data('id');

            $.ajax({
                url: baseUrl + '/' + id + '/edit',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success && response.data) {
                        var d = response.data;
                        $('#unit_id').val(d.id);
                        $('#unit_method').val('PUT');
                        $('#unit_title').val(d.title);
                        $('#unit_value').val(d.value);
                        $('#unit_status').val(d.status);
                        $('#unitModalLabel').text('Edit Measurement Unit');
                        $('#unitSubmitBtn').text('Update');
                        $('#unitModal').modal('show');
                    }
                },
                error: function () {
                    toastr.error('Failed to fetch measurement unit details.', 'Error');
                }
            });
        });

        // ---- Form Submit (Add / Edit) ----
        $('#unitForm').on('submit', function (e) {
            e.preventDefault();
            resetErrors();

            var unitId  = $('#unit_id').val();
            var url     = unitId ? baseUrl + '/' + unitId : baseUrl;
            var label   = unitId ? 'Updating...' : 'Saving...';

            $('#unitSubmitBtn').prop('disabled', true).text(label);

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function (response) {
                    $('#unitSubmitBtn').prop('disabled', false).text(unitId ? 'Update' : 'Save');
                    if (response.success) {
                        $('#unitModal').modal('hide');
                        toastr.success(response.message, 'Success');
                        setTimeout(function () { location.reload(); }, 600);
                    }
                },
                error: function (xhr) {
                    $('#unitSubmitBtn').prop('disabled', false).text(unitId ? 'Update' : 'Save');
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var fieldMap = {
                            title:  'unit_title',
                            value:  'unit_value',
                            status: 'unit_status'
                        };
                        $.each(errors, function (field, messages) {
                            if (fieldMap[field]) {
                                $('#' + fieldMap[field]).addClass('is-invalid');
                            }
                            $('#' + field + '-error').text(messages[0]).show();
                        });
                        toastr.error('Please fix the validation errors.', 'Validation Error');
                    } else {
                        toastr.error('Something went wrong, please try again.', 'Error');
                    }
                }
            });
        });

        // ---- Delete ----
        $(document).on('click', '.delete-unit-btn', function (e) {
            e.preventDefault();
            var id  = $(this).data('id');
            var url = baseUrl + '/' + id;

            swal({
                title: 'Are you sure you want to delete this record?',
                text: 'If you delete this, it will be gone permanently.',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then(function (willDelete) {
                if (willDelete) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: { _token: csrfToken, _method: 'DELETE' },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                toastr.success(response.message, 'Success');
                                setTimeout(function () { location.reload(); }, 600);
                            } else {
                                toastr.error('Failed to delete.', 'Error');
                            }
                        },
                        error: function () {
                            toastr.error('Something went wrong, please try again.', 'Error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
