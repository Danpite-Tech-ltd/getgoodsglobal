@extends('backEnd.layouts.master')
@section('title', 'Resin Ratio Manage')

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
                    <button type="button" class="btn btn-primary rounded-pill waves-effect waves-light" id="addResinBtn">
                        <i class="fe-plus"></i> Add Resin Ratio
                    </button>
                </div>
                <h4 class="page-title">Resin Ratio Manage</h4>
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
                                <th>Resin</th>
                                <th>Hardener</th>
                                <th>Resin Density</th>
                                <th>Hardener Density</th>
                                <th>Wastage %</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($data as $value)
                                <tr id="resin-row-{{ $value->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $value->resin }}</td>
                                    <td>{{ $value->hardener }}</td>
                                    <td>{{ $value->resin_density }}</td>
                                    <td>{{ $value->hardener_density }}</td>
                                    <td>{{ $value->wastage }}</td>
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
                                                class="btn btn-xs btn-primary waves-effect waves-light edit-resin-btn"
                                                data-id="{{ $value->id }}" title="Edit">
                                                <i class="fe-edit-1"></i>
                                            </button>
                                            <button type="button"
                                                class="btn btn-xs btn-danger waves-effect waves-light delete-resin-btn"
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

<!-- Resin Ratio Add/Edit Modal -->
<div class="modal fade" id="resinModal" tabindex="-1" aria-labelledby="resinModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resinModalLabel">Add Resin Ratio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="resinForm">
                @csrf
                <input type="hidden" id="resin_id" name="resin_id">
                <input type="hidden" id="resin_method" name="_method" value="POST">

                <div class="modal-body">
                    <div class="row">
                        <!-- Resin -->
                        <div class="col-md-6 mb-3">
                            <label for="resin_field" class="form-label">Resin <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="resin_field" name="resin"
                                placeholder="e.g. 2" required>
                            <div class="invalid-feedback" id="resin-error"></div>
                        </div>

                        <!-- Hardener -->
                        <div class="col-md-6 mb-3">
                            <label for="hardener_field" class="form-label">Hardener <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="hardener_field" name="hardener"
                                placeholder="e.g. 1" required>
                            <div class="invalid-feedback" id="hardener-error"></div>
                        </div>

                        <!-- Resin Density -->
                        <div class="col-md-6 mb-3">
                            <label for="resin_density_field" class="form-label">Resin Density <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="resin_density_field" name="resin_density"
                                placeholder="e.g. 1.1" required>
                            <div class="invalid-feedback" id="resin_density-error"></div>
                        </div>

                        <!-- Hardener Density -->
                        <div class="col-md-6 mb-3">
                            <label for="hardener_density_field" class="form-label">Hardener Density <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="hardener_density_field" name="hardener_density"
                                placeholder="e.g. 0.9" required>
                            <div class="invalid-feedback" id="hardener_density-error"></div>
                        </div>

                        <!-- Wastage -->
                        <div class="col-md-6 mb-3">
                            <label for="wastage_field" class="form-label">Wastage % <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="wastage_field" name="wastage"
                                placeholder="e.g. 10" required>
                            <div class="invalid-feedback" id="wastage-error"></div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label for="resin_status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="resin_status" name="status" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <div class="invalid-feedback" id="status-error"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success waves-effect waves-light" id="resinSubmitBtn">Save</button>
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

        var resinBaseUrl = "{{ url('admin/resin') }}";
        var csrfToken   = "{{ csrf_token() }}";

        // ---- Helper: reset validation errors ----
        function resetErrors() {
            $('#resin_field, #hardener_field, #resin_density_field, #hardener_density_field, #wastage_field, #resin_status')
                .removeClass('is-invalid');
            $('#resin-error, #hardener-error, #resin_density-error, #hardener_density-error, #wastage-error, #status-error')
                .text('').hide();
        }

        // ---- Open ADD modal ----
        $('#addResinBtn').on('click', function () {
            resetErrors();
            $('#resinForm')[0].reset();
            $('#resin_id').val('');
            $('#resin_method').val('POST');
            $('#resin_status').val('1');
            $('#resinModalLabel').text('Add Resin Ratio');
            $('#resinSubmitBtn').text('Save');
            $('#resinModal').modal('show');
        });

        // ---- Open EDIT modal ----
        $(document).on('click', '.edit-resin-btn', function () {
            resetErrors();
            var id = $(this).data('id');

            $.ajax({
                url: resinBaseUrl + '/' + id + '/edit',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success && response.data) {
                        var d = response.data;
                        $('#resin_id').val(d.id);
                        $('#resin_method').val('PUT');
                        $('#resin_field').val(d.resin);
                        $('#hardener_field').val(d.hardener);
                        $('#resin_density_field').val(d.resin_density);
                        $('#hardener_density_field').val(d.hardener_density);
                        $('#wastage_field').val(d.wastage);
                        $('#resin_status').val(d.status);
                        $('#resinModalLabel').text('Edit Resin Ratio');
                        $('#resinSubmitBtn').text('Update');
                        $('#resinModal').modal('show');
                    }
                },
                error: function () {
                    toastr.error('Failed to fetch resin ratio details.', 'Error');
                }
            });
        });

        // ---- Form Submit (Add / Edit) ----
        $('#resinForm').on('submit', function (e) {
            e.preventDefault();
            resetErrors();

            var resinId = $('#resin_id').val();
            var url     = resinId ? resinBaseUrl + '/' + resinId : resinBaseUrl;
            var label   = resinId ? 'Updating...' : 'Saving...';

            $('#resinSubmitBtn').prop('disabled', true).text(label);

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function (response) {
                    $('#resinSubmitBtn').prop('disabled', false).text(resinId ? 'Update' : 'Save');
                    if (response.success) {
                        $('#resinModal').modal('hide');
                        toastr.success(response.message, 'Success');
                        setTimeout(function () { location.reload(); }, 600);
                    }
                },
                error: function (xhr) {
                    $('#resinSubmitBtn').prop('disabled', false).text(resinId ? 'Update' : 'Save');
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var fieldMap = {
                            resin:            'resin_field',
                            hardener:         'hardener_field',
                            resin_density:    'resin_density_field',
                            hardener_density: 'hardener_density_field',
                            wastage:          'wastage_field',
                            status:           'resin_status'
                        };
                        $.each(errors, function (field, messages) {
                            if (fieldMap[field]) {
                                $('#' + fieldMap[field]).addClass('is-invalid');
                            }
                            $('#' + field.replace('_', '_') + '-error').text(messages[0]).show();
                        });
                        toastr.error('Please fix the validation errors.', 'Validation Error');
                    } else {
                        toastr.error('Something went wrong, please try again.', 'Error');
                    }
                }
            });
        });

        // ---- Delete ----
        $(document).on('click', '.delete-resin-btn', function (e) {
            e.preventDefault();
            var id  = $(this).data('id');
            var url = resinBaseUrl + '/' + id;

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
