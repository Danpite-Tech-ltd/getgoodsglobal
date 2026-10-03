@extends('backEnd.layouts.master')
@section('title', 'Shape Manage')

@section('css')
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="container-fluid">
    <!-- start Shape title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <button type="button" class="btn btn-primary rounded-pill waves-effect waves-light" id="addShapeBtn">
                        <i class="fe-plus"></i> Add Shape
                    </button>
                </div>
                <h4 class="page-title">Shape Manage</h4>
            </div>
        </div>
    </div>
    <!-- end Shape title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Input List</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($data as $key => $value)
                                <tr id="row-{{ $value->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $value->title }}</td>
                                    <td>{{ $value->type }}</td>
                                    <td>
                                        @php
                                            $inputs = is_array($value->input_list) ? $value->input_list : json_decode($value->input_list, true);
                                        @endphp
                                        @if (!empty($inputs) && is_array($inputs))
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach ($inputs as $input)
                                                    <span class="badge bg-light text-dark border">
                                                        {{ $input['title'] ?? '' }}: <strong class="text-primary">{{ $input['parameter'] ?? '' }}</strong>
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($value->status == 1)
                                            <span class="badge bg-soft-success text-success">Active</span>
                                        @else
                                            <span class="badge bg-soft-danger text-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="button-list">
                                            <button type="button" class="btn btn-xs btn-primary waves-effect waves-light edit-shape-btn" data-id="{{ $value->id }}" title="Edit">
                                                <i class="fe-edit-1"></i>
                                            </button>
                                            <button type="button" class="btn btn-xs btn-danger waves-effect waves-light delete-shape-btn" data-id="{{ $value->id }}" title="Delete">
                                                <i class="mdi mdi-close"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
</div>

<!-- Shape Add/Edit Modal -->
<div class="modal fade" id="shapeModal" tabindex="-1" aria-labelledby="shapeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shapeModalLabel">Add Shape</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="shapeForm" class="d-flex flex-column overflow-hidden">
                @csrf
                <input type="hidden" id="shape_id" name="shape_id">
                <input type="hidden" id="_method" name="_method" value="POST">

                <div class="modal-body overflow-auto">
                    <div class="mb-3">
                        <label for="shape_title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="shape_title" name="title" placeholder="Enter shape title" required>
                        <div class="invalid-feedback" id="title-error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="shape_type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="shape_type" name="type" required>
                            <option value="rectangle">Rectangle</option>
                            <option value="circle">Circle</option>
                            <option value="custom">Custom</option>
                        </select>
                        <div class="invalid-feedback" id="type-error"></div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0 fw-semibold">Input List <span class="text-danger">*</span></label>
                            <button type="button" class="btn btn-xs btn-success waves-effect waves-light" id="addInputRowBtn">
                                <i class="fe-plus"></i> Add Item
                            </button>
                        </div>
                        <div class="p-2 border rounded bg-light">
                            <div class="d-flex gap-2 mb-1 px-1 text-muted small fw-semibold">
                                <div class="flex-grow-1">Title <span class="text-danger">*</span></div>
                                <div class="flex-grow-1">Parameter <span class="text-danger">*</span></div>
                                <div style="width: 38px;" class="text-center">Action</div>
                            </div>
                            <div id="inputListContainer">
                                <!-- Dynamic rows go here -->
                            </div>
                        </div>
                        <div class="text-danger small mt-1" id="input_list-error" style="display: none;"></div>
                    </div>

                    <div class="mb-3">
                        <label for="shape_status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="shape_status" name="status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <div class="invalid-feedback" id="status-error"></div>
                    </div>
                </div>

                <div class="modal-footer flex-shrink-0">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success waves-effect waves-light" id="submitBtn">Save</button>
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
    $(document).ready(function() {
        var rowIndex = 0;

        function updateRemoveButtons() {
            var rows = $('#inputListContainer .input-item-row');
            if (rows.length <= 1) {
                rows.find('.remove-input-row-btn').prop('disabled', true).addClass('disabled');
            } else {
                rows.find('.remove-input-row-btn').prop('disabled', false).removeClass('disabled');
            }
        }

        function addInputRow(title = '', parameter = '') {
            rowIndex++;
            var safeTitle = (title || '').toString().replace(/"/g, '&quot;');
            var safeParam = (parameter || '').toString().replace(/"/g, '&quot;');
            var rowHtml = `
                <div class="d-flex align-items-center gap-2 mb-2 input-item-row">
                    <div class="flex-grow-1">
                        <input type="text" class="form-control input-item-title" name="input_list[${rowIndex}][title]" value="${safeTitle}" placeholder="e.g. Length" required>
                    </div>
                    <div class="flex-grow-1">
                        <input type="text" class="form-control input-item-param" name="input_list[${rowIndex}][parameter]" value="${safeParam}" placeholder="e.g. l" required>
                    </div>
                    <div style="width: 38px;" class="text-center">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-input-row-btn" title="Remove Item">
                            <i class="fe-trash-2"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#inputListContainer').append(rowHtml);
            updateRemoveButtons();
        }

        $('#addInputRowBtn').click(function() {
            addInputRow('', '');
        });

        $(document).on('click', '.remove-input-row-btn', function() {
            var rows = $('#inputListContainer .input-item-row');
            if (rows.length <= 1) {
                toastr.warning('At least one input field is required.', 'Warning');
                return;
            }
            $(this).closest('.input-item-row').remove();
            updateRemoveButtons();
        });

        function resetFormErrors() {
            $('#shape_title').removeClass('is-invalid');
            $('#shape_type').removeClass('is-invalid');
            $('#shape_status').removeClass('is-invalid');
            $('.input-item-title, .input-item-param').removeClass('is-invalid');
            $('#title-error').text('').hide();
            $('#type-error').text('').hide();
            $('#status-error').text('').hide();
            $('#input_list-error').text('').hide();
        }

        // Open Modal for Add
        $('#addShapeBtn').click(function() {
            resetFormErrors();
            $('#shapeForm')[0].reset();
            $('#shape_id').val('');
            $('#_method').val('POST');
            $('#shape_type').val('rectangle');
            $('#shape_status').val('1');
            $('#shapeModalLabel').text('Add Shape');
            $('#submitBtn').text('Save');

            $('#inputListContainer').empty();
            rowIndex = 0;
            addInputRow('', '');

            $('#shapeModal').modal('show');
        });

        // Open Modal for Edit
        $(document).on('click', '.edit-shape-btn', function() {
            resetFormErrors();
            var id = $(this).data('id');
            var editUrl = "{{ url('admin/shape') }}/" + id + "/edit";

            $.ajax({
                url: editUrl,
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    // Optional loading state
                },
                success: function(response) {
                    if (response.success && response.data) {
                        $('#shape_id').val(response.data.id);
                        $('#_method').val('PUT');
                        $('#shape_title').val(response.data.title);
                        $('#shape_type').val(response.data.type);
                        $('#shape_status').val(response.data.status);
                        $('#shapeModalLabel').text('Edit Shape');
                        $('#submitBtn').text('Update');

                        $('#inputListContainer').empty();
                        rowIndex = 0;

                        var list = response.data.input_list;
                        if (typeof list === 'string') {
                            try {
                                list = JSON.parse(list);
                            } catch(e) {
                                list = [];
                            }
                        }

                        if (Array.isArray(list) && list.length > 0) {
                            list.forEach(function(item) {
                                addInputRow(item.title || '', item.parameter || '');
                            });
                        } else {
                            addInputRow('', '');
                        }

                        $('#shapeModal').modal('show');
                    }
                },
                error: function(xhr) {
                    toastr.error('Failed to fetch shape details.');
                }
            });
        });

        // Form Submit (Add / Edit via AJAX)
        $('#shapeForm').on('submit', function(e) {
            e.preventDefault();
            resetFormErrors();

            var rows = $('#inputListContainer .input-item-row');
            if (rows.length === 0) {
                $('#input_list-error').text('At least one input item is required.').show();
                toastr.error('At least one input item is required.', 'Validation Error');
                return;
            }

            var hasEmpty = false;
            rows.each(function() {
                var title = $(this).find('.input-item-title').val().trim();
                var param = $(this).find('.input-item-param').val().trim();
                if (!title) {
                    $(this).find('.input-item-title').addClass('is-invalid');
                    hasEmpty = true;
                }
                if (!param) {
                    $(this).find('.input-item-param').addClass('is-invalid');
                    hasEmpty = true;
                }
            });

            if (hasEmpty) {
                $('#input_list-error').text('All title and parameter fields are required.').show();
                toastr.error('Please fill in both title and parameter for all input items.', 'Validation Error');
                return;
            }

            var shapeId = $('#shape_id').val();
            var url = shapeId ? "{{ url('admin/shape') }}/" + shapeId : "{{ route('shape.store') }}";
            var btnText = shapeId ? 'Updating...' : 'Saving...';
            $('#submitBtn').prop('disabled', true).text(btnText);

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                success: function(response) {
                    $('#submitBtn').prop('disabled', false).text(shapeId ? 'Update' : 'Save');
                    if (response.success) {
                        $('#shapeModal').modal('hide');
                        toastr.success(response.message, 'Success');
                        setTimeout(function() {
                            location.reload();
                        }, 600);
                    }
                },
                error: function(xhr) {
                    $('#submitBtn').prop('disabled', false).text(shapeId ? 'Update' : 'Save');
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        if (errors.title) {
                            $('#shape_title').addClass('is-invalid');
                            $('#title-error').text(errors.title[0]).show();
                        }
                        if (errors.type) {
                            $('#shape_type').addClass('is-invalid');
                            $('#type-error').text(errors.type[0]).show();
                        }
                        if (errors.status) {
                            $('#shape_status').addClass('is-invalid');
                            $('#status-error').text(errors.status[0]).show();
                        }
                        if (errors.input_list) {
                            $('#input_list-error').text(errors.input_list[0]).show();
                        }
                        $.each(errors, function(key, msgs) {
                            if (key.indexOf('input_list.') === 0) {
                                $('#input_list-error').text(msgs[0]).show();
                            }
                        });
                        toastr.error('Please check validation errors.', 'Validation Error');
                    } else {
                        toastr.error('Something went wrong, please try again.', 'Error');
                    }
                }
            });
        });

        // Delete Shape via AJAX
        $(document).on('click', '.delete-shape-btn', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var deleteUrl = "{{ url('admin/shape') }}/" + id;

            swal({
                title: "Are you sure you want to delete this shape?",
                text: "If you delete this, it will be gone permanently.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: deleteUrl,
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE'
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message, 'Success');
                                setTimeout(function() {
                                    location.reload();
                                }, 600);
                            } else {
                                toastr.error('Failed to delete shape.', 'Error');
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Something went wrong, please try again.', 'Error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection