@extends('backEnd.layouts.master') @section('title', 'Product Create') @section('css')
    <style>
        .increment_btn,
        .remove_btn {
            margin-top: -17px;
            margin-bottom: 10px;
        }

        #taka {
            padding: 6px;
            line-height: 35px;
        }
    </style>
    <link href="{{ asset('public/backEnd') }}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/backEnd') }}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet"
        type="text/css" />
@endsection @section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill">Manage</a>
                    </div>
                    <h4 class="page-title">Product Create</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" class="row" data-parsley-validate="" enctype="multipart/form-data">
                            @csrf
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Product Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" id="name" required="" />
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col-end -->
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="category_id" class="form-label">Categories *</label>
                                    <select class="form-control select2 @error('category_id') is-invalid @enderror"
                                        name="category_id" value="{{ old('category_id') }}" id="category_id" required>
                                        <option value="">Select..</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->
                            <div class="col-sm-4">
                                <div class="form-group mb-3">
                                    <label for="subcategory_id" class="form-label">SubCategories (Optional)</label>
                                    <select class="form-control select2 @error('subcategory_id') is-invalid @enderror"
                                        id="subcategory_id" name="subcategory_id" data-placeholder="Choose ...">
                                        <optgroup>
                                            <option value="">Select..</option>
                                        </optgroup>
                                    </select>
                                    @error('subcategory_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->
                            <div class="col-sm-4">
                                <div class="form-group mb-3">
                                    <label for="childcategory_id" class="form-label">Child Categories (Optional)</label>
                                    <select class="form-control select2 @error('childcategory_id') is-invalid @enderror"
                                        id="childcategory_id" name="childcategory_id" data-placeholder="Choose ...">
                                        <optgroup>
                                            <option value="">Select..</option>
                                        </optgroup>
                                    </select>
                                    @error('childcategory_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->

                            <div class="col-sm-4">
                                <div class="form-group mb-3">
                                    <label for="category_id" class="form-label">Brands</label>
                                    <select id="brand_id"
                                        class="form-control select2 @error('brand_id') is-invalid @enderror"
                                        value="{{ old('brand_id') }}" name="brand_id">
                                        <option value="">Select..</option>
                                        @foreach ($brands as $value)
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group mb-3">
                                    <label for="order_by" class="form-label">Order Type</label>
                                    <select class="form-control select2 @error('order_by') is-invalid @enderror"
                                        name="order_by" id="order_by">
                                        <option value="">Select..</option>
                                        <option value="1">Bulk Quantity</option>
                                    </select>
                                    @error('order_by')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- col-end -->
                            <div class="col-sm-4">
                                <div class="form-group mb-3">
                                    <label for="new_price" class="form-label">New Price </label>
                                    <input type="text" class="form-control @error('new_price') is-invalid @enderror"
                                        name="new_price" value="{{ old('new_price') }}" id="new_price" required />
                                    @error('new_price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col-end -->
                            <div class="col-sm-4">
                                <div class="form-group mb-3">
                                    <label for="old_price" class="form-label">Old Price </label>
                                    <input type="text" class="form-control @error('old_price') is-invalid @enderror"
                                        name="old_price" value="{{ old('old_price') }}" id="old_price" required />
                                    @error('old_price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col-end -->
                            <div class="col-sm-4 d-none">
                                <div class="form-group mb-3">
                                    <label for="stock" class="form-label">Stock *</label>
                                    <input type="text" class="form-control @error('stock') is-invalid @enderror"
                                        name="stock" value="{{ old('stock') }}" id="stock" />
                                    @error('stock')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col-end -->

                            <div class="col-sm-4 mb-3">
                                <label for="image">Image *</label>

                                <div class="input-group control-group increment">
                                    <input type="file" name="productimage" id="productimage"
                                        class="form-control @error('image') is-invalid @enderror" />

                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->
                            <div class="col-sm-4 d-none">
                                <div class="form-group mb-3">
                                    <label for="pro_unit" class="form-label">Product Unit (Optional)</label>
                                    <input type="text" class="form-control @error('pro_unit') is-invalid @enderror"
                                        name="pro_unit" value="{{ old('pro_unit') }}" id="pro_unit" />
                                    @error('pro_unit')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4 d-none">
                                <div class="form-group mb-3">
                                    <label for="pro_video" class="form-label">Product Video (Optional)</label>
                                    <input type="text" class="form-control @error('pro_video') is-invalid @enderror"
                                        name="pro_video" value="{{ old('pro_video') }}" id="pro_video" />
                                    @error('pro_video')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-4 d-none">
                                <div class="form-group mb-3">
                                    <label for="pro_video" class="form-label">Product Type</label>
                                    <select class="form-control" id="type" name="type">
                                        {{-- <option value="">Choose</option>
                                        <option value="0">Single</option> --}}
                                        <option value="1">Varient</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-6">
                                <div class="form-group"
                                    style="padding: 10px;padding-top: 3px;margin:0;padding-bottom:3px;width:96%;margin-left: 8px;border-radius: 8px;padding-left: 0;margin-left: -0;">
                                    <label class="fileContainer">
                                        <span style="font-size: 20px;">Slider
                                            image</span>
                                    </label>
                                    <br>
                                    <button type="button" class="btn btn-danger d-block mb-2" style="background: red">
                                        <input type="file" onchange="prevPost_Img()" name="PostImage[]" id="PostImage"
                                            multiple>
                                    </button>
                                </div>
                            </div>
                            <div class="col-6 d-none">
                                <div class="form-group mb-3">
                                    <label for="product_weight" class="form-label">Product Weight</label>
                                    <input type="text" class="form-control @error('product_weight') is-invalid @enderror"
                                        name="product_weight" value="{{ old('product_weight') }}" id="product_weight" />
                                </div>
                            </div>
                            <div class="col-12 my-3 d-none">
                                <div class="form-group">
                                    <label for="">Specification</label>
                                    <textarea name="short_des" id="short_des" rows="3"
                                        class="summernote form-control"></textarea>
                                </div>
                            </div>

                            <!--col end -->
                            <div class="col-sm-12 mb-3">
                                <div class="form-group">
                                    <label for="description" class="form-label">Description *</label>
                                    <textarea name="description" id="description" rows="6"
                                        class="summernote form-control @error('description') is-invalid @enderror"
                                        required></textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->

                            <div class="col-lg-12 mb-4">
                                <div class="card">
                                    <div class="card-header p-0" id="headingOne">
                                        <h5 class="mb-0">
                                            <button type="button" id="collupshead" class="btn btn-link"
                                                data-bs-toggle="collapse" data-bs-target="#collapseVariant"
                                                aria-expanded="true" aria-controls="collapseOne">
                                                <h5 class="text-uppercase m-0">
                                                    Product Variant <span class="text-danger">*</span>
                                                </h5>
                                            </button>
                                        </h5>
                                    </div>

                                    <div id="collapseVariant" class="collapse show" aria-labelledby="headingOne"
                                        data-parent="#accordion">
                                        <div class="card-body p-0">

                                            {{-- Color Select Dropdown --}}
                                            <div class="p-3 border-bottom">
                                                <select id="mediavariantID" style="width: 100%;">
                                                    <option value="">Select Product Variant (Color)</option>
                                                </select>
                                            </div>

                                            {{-- Color wise variant rows will be appended here --}}
                                            <div id="variantContainer"></div>

                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!--col end -->

                            <!-- col end -->
                            <div class="col-sm-3 mb-3">
                                <div class="form-group">
                                    <label for="status" class="d-block">Status</label>
                                    <label class="switch">
                                        <input type="checkbox" value="1" id="status" name="status" checked />
                                        <span class="slider round"></span>
                                    </label>
                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->
                            <div class="col-sm-3 mb-3">
                                <div class="form-group">
                                    <label for="topsale" class="d-block">Flash Sales</label>
                                    <label class="switch">
                                        <input type="checkbox" value="1" id="topsale" name="topsale" />
                                        <span class="slider round"></span>
                                    </label>
                                    @error('topsale')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->
                            <!--<div class="col-sm-3 mb-3">-->
                            <!--    <div class="form-group">-->
                            <!--        <label for="feature_product" class="d-block">Top Trending</label>-->
                            <!--        <label class="switch">-->
                            <!--            <input type="checkbox" value="1" id="feature_product"-->
                            <!--                name="feature_product" />-->
                            <!--            <span class="slider round"></span>-->
                            <!--        </label>-->
                            <!--        @error('feature_product')-->
                            <!--            <span class="invalid-feedback" role="alert">-->
                            <!--                <strong>{{ $message }}</strong>-->
                            <!--            </span>-->
                            <!--        @enderror-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!-- col end -->
                            <!--<div class="col-sm-3 mb-3">-->
                            <!--    <div class="form-group">-->
                            <!--        <label for="deal_of_theday" class="d-block">Top Trending</label>-->
                            <!--        <label class="switch">-->
                            <!--            <input type="checkbox" value="1" id="deal_of_theday"-->
                            <!--                name="deal_of_theday" />-->
                            <!--            <span class="slider round"></span>-->
                            <!--        </label>-->
                            <!--        @error('deal_of_theday')-->
                            <!--            <span class="invalid-feedback" role="alert">-->
                            <!--                <strong>{{ $message }}</strong>-->
                            <!--            </span>-->
                            <!--        @enderror-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!-- col end -->

                            <div>
                                <input type="button" id="submit" class="btn btn-success" value="Submit" />
                            </div>
                        </form>
                    </div>
                    <!-- end card-body-->
                </div>
                <!-- end card-->
            </div>
            <!-- end col-->
        </div>
    </div>


@endsection

@section('script')
    <script src="{{ asset('public/backEnd/') }}/assets/libs/parsleyjs/parsley.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/assets/js/pages/form-validation.init.js"></script>
    <script src="{{ asset('public/backEnd/') }}/assets/libs/select2/js/select2.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/assets/js/pages/form-advanced.init.js"></script>
    <!-- Plugins js -->
    <script src="{{ asset('public/backEnd/') }}/assets/libs//summernote/summernote-lite.min.js"></script>

    <script>

$(document).ready(function () {

    // =============================================
    // CSRF Setup
    // =============================================
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var token = $("input[name='_token']").val();


    // =============================================
    // Color (Variant) Select2 — color-wise size block
    // =============================================
    $("#mediavariantID").select2({
        placeholder: "Select a Product Variant (Color)",
        templateResult: function (state) {
            if (!state.id) return state.text;
            return $('<span>' + state.text + '</span>');
        },
        ajax: {
            type: 'GET',
            url: '{{ url("admin/product/color") }}',
            processResults: function (data) {
                return { results: $.parseJSON(data).data };
            }
        }

    }).trigger("change").on("select2:select", function (e) {

        var colorID   = e.params.data.id;
        var colorName = e.params.data.text;

        // Duplicate color check
        if ($("#variantContainer").find('[data-color-id="' + colorID + '"]').length > 0) {
            toastr.warning(colorName + ' is already added.');
            return;
        }

        var colorBlock = $(
            '<div class="variant-color-block border rounded mb-3 mx-3 mt-3" data-color-id="' + colorID + '">' +

                // -- Color block header
                '<div class="d-flex justify-content-between align-items-center bg-light px-3 py-2 rounded-top">' +
                    '<div class="d-flex align-items-center">' +
                        '<strong class="text-dark">' +
                            '<i class="fa fa-tint mr-2 text-secondary"></i>' + colorName +
                        '</strong>' +
                    '</div>' +
                    '<div class="d-flex align-items-center">' +
                        '<label class="mb-0 text-muted small mr-2">Specification:</label>' +
                        '<input type="text" class="form-control form-control-sm mr-2 variant-specification"  ' +
                           'data-color-id="' + colorID + '" style="width:300px;margin-right:50px">' +
                        '<label class="mb-0 text-muted small mr-2">Color Image:</label>' +
                        '<input type="file" class="form-control form-control-sm variant-image-input" ' +
                            'data-color-id="' + colorID + '" style="width:220px;">' +
                        '<button type="button" class="btn btn-sm btn-danger remove-color-block ml-3">' +
                            '<i class="fa fa-trash"></i>' +
                        '</button>' +
                    '</div>' +
                '</div>' +

                // -- Size table for this color
                '<div class="p-3">' +
                    '<table class="table table-bordered table-striped mb-2 size-table" style="width:100%;">' +
                        '<thead class="thead-light">' +
                            '<tr>' +
                                '<th style="width:5%;">ID</th>' +
                                '<th style="width:20%;">Size / Weight</th>' +
                                '<th style="width:20%;">Stock</th>' +
                                // '<th style="width:20%;">Purchase Price</th>' +
                                '<th style="width:20%;">Regular Price</th>' +
                                '<th style="width:20%;">Sale Price</th>' +
                                '<th style="width:15%;">Action</th>' +
                            '</tr>' +
                        '</thead>' +
                        '<tbody></tbody>' +
                    '</table>' +
                    '<select class="sizevariantID w-100" data-color-id="' + colorID + '">' +
                        '<option value="">+ Add Size / Weight for ' + colorName + '</option>' +
                    '</select>' +
                '</div>' +

            '</div>'
        );

        $("#variantContainer").append(colorBlock);
        initSizeSelect2(colorBlock.find('.sizevariantID'), colorID);
    });


    // =============================================
    // Initialize Size Select2 for a color block
    // =============================================
    function initSizeSelect2($select, colorID) {
        $select.select2({
            placeholder: "Select a Size / Weight",
            templateResult: function (state) {
                if (!state.id) return state.text;
                return $('<span>' + state.text + '</span>');
            },
            ajax: {
                type: 'GET',
                url: '{{ url("admin/product/size-weight") }}',
                processResults: function (data) {
                    return { results: $.parseJSON(data).data };
                }
            }

        }).trigger("change").on("select2:select", function (e) {

            var sizeID   = e.params.data.id;
            var sizeName = e.params.data.text;
            var $block   = $(this).closest('.variant-color-block');
            var $tbody   = $block.find('.size-table tbody');

            // Duplicate size check within this color block
            if ($tbody.find('[data-size-id="' + sizeID + '"]').length > 0) {
                toastr.warning(sizeName + ' is already added for this color.');
                return;
            }

            $tbody.append(
                '<tr data-size-id="' + sizeID + '">' +
                    '<td>' +
                        '<span class="text-muted small">' + sizeID + '</span>' +
                    '</td>' +
                    '<td>' +
                        '<span>' + sizeName + '</span>' +
                    '</td>' +
                    '<td>' +
                        '<input type="number" class="form-control form-control-sm size-stock" ' +
                            'placeholder="Stock" min="0">' +
                    '</td>' +
                    // '<td>' +
                    //     '<div class="input-group input-group-sm">' +
                    //         '<input type="number" class="form-control size-purchase-price" ' +
                    //             'placeholder="0.00" min="0" step="0.01">' +
                    //         '<div class="input-group-append">' +
                    //             '<span class="input-group-text">TK</span>' +
                    //         '</div>' +
                    //     '</div>' +
                    // '</td>' +
                    '<td>' +
                        '<div class="input-group input-group-sm">' +
                            '<input type="number" class="form-control RegularPrice" ' +
                                'placeholder="0.00" min="0" step="0.01">' +
                            '<div class="input-group-append">' +
                                '<span class="input-group-text">TK</span>' +
                            '</div>' +
                        '</div>' +
                    '</td>' +
                    '<td>' +
                        '<div class="input-group input-group-sm">' +
                            '<input type="number" class="form-control size-sale-price" ' +
                                'placeholder="0.00" min="0" step="0.01">' +
                            '<div class="input-group-append">' +
                                '<span class="input-group-text">TK</span>' +
                            '</div>' +
                        '</div>' +
                    '</td>' +
                    '<td class="text-center">' +
                        '<button type="button" class="btn btn-sm btn-danger delete-size-btn">' +
                            '<i class="fa fa-trash"></i>' +
                        '</button>' +
                    '</td>' +
                '</tr>'
            );
        });
    }


    // =============================================
    // Delete size row
    // =============================================
    $(document).on("click", ".delete-size-btn", function () {
        $(this).closest("tr").remove();
    });

    // =============================================
    // Delete entire color block
    // =============================================
    $(document).on("click", ".remove-color-block", function () {
        $(this).closest(".variant-color-block").remove();
    });


    // =============================================
    // Form Submit
    // =============================================
    $(document).on("click", "#submit", function () {

        var name        = $("#name");
        var category_id = $("#category_id");
        var type        = $("#type");

        if (name.val() === '') {
            toastr.error('Product Name Should Not Be Empty');
            name.css('border', '1px solid red');
            return;
        }
        name.css('border', '1px solid #ced4da');

        if (category_id.val() === '') {
            toastr.error('Category Should Not Be Empty');
            category_id.closest('.form-group').css('border', '1px solid red');
            return;
        }
        category_id.closest('.form-group').css('border', '1px solid #ced4da');

        if (type.val() === '') {
            toastr.error('Type Should Not Be Empty');
            type.closest('.form-group').css('border', '1px solid red');
            return;
        }
        type.closest('.form-group').css('border', '1px solid #ced4da');


        var formData = new FormData();

        formData.append('name',             name.val());
        formData.append('category_id',      category_id.val());
        formData.append('subcategory_id',   $("#subcategory_id").val());
        formData.append('childcategory_id', $("#childcategory_id").val());
        formData.append('brand_id',         $("#brand_id").val());
        formData.append('order_by',         $("#order_by").val());
        formData.append('new_price',        $("#new_price").val());
        formData.append('old_price',        $("#old_price").val());
        formData.append('stock',            $("#stock").val());
        formData.append('pro_unit',         $("#pro_unit").val());
        formData.append('pro_video',        $("#pro_video").val());
        formData.append('description',      $("#description").val());
        formData.append('status',           $("#status").val());
        formData.append('type',             type.val());
        formData.append('short_des',        $("#short_des").val());
        formData.append('product_weight',   $("#product_weight").val());
        formData.append('image',            $('#productimage')[0].files[0]);

        // Gallery images
        var fileList = $('#PostImage').get(0).files;
        if (fileList.length > 0) {
            for (let i = 0; i < fileList.length; i++) {
                formData.append('PostImage[]', fileList[i]);
            }
        }


        // ---- Simple product (type == 0) ----
        if (type.val() == 0) {

            var price_two   = $("#price_two");
            var price_three = $("#price_three");

            if (price_two.val() === '') {
                toastr.error('Old Price Should Not Be Empty');
                price_two.closest('.form-group').css('border', '1px solid red');
                return;
            }
            price_two.closest('.form-group').css('border', '1px solid #ced4da');

            if (price_three.val() === '') {
                toastr.error('New Price Should Not Be Empty');
                price_three.closest('.form-group').css('border', '1px solid red');
                return;
            }
            price_three.closest('.form-group').css('border', '1px solid #ced4da');

        } else {

            // ---- Variant product ----
            var colorBlocks = $("#variantContainer .variant-color-block");

            if (colorBlocks.length === 0) {
                toastr.error('Please add at least one Product Color.');
                return;
            }

            var hasError = false;

            colorBlocks.each(function () {
                var $block    = $(this);
                var colorID   = $block.data('color-id');
                var specification = $block.find('.variant-specification').val();
                var colorName = $block.find('strong').text().trim();
                var $sizeRows = $block.find('.size-table tbody tr');

                if ($sizeRows.length === 0) {
                    toastr.error('Please add at least one Size for: ' + colorName);
                    hasError = true;
                    return false; // break each
                }

                // Color data
                formData.append('variant[' + colorID + '][colorID]', colorID);
                formData.append('variant[' + colorID + '][color]',   colorName);
                formData.append('variant[' + colorID + '][specification]', specification);

                // Color image
                var imageFile = $block.find('.variant-image-input')[0].files[0];
                if (imageFile) {
                    formData.append('variant[' + colorID + '][image]', imageFile);
                }

                // Size rows for this color
                $sizeRows.each(function () {
                    var $row = $(this);
                    var sid  = $row.data('size-id');

                    formData.append('size[' + colorID + '][' + sid + '][sizeID]',         sid);
                    formData.append('size[' + colorID + '][' + sid + '][size]',           $row.find('td:nth-child(2) span').text().trim());
                    formData.append('size[' + colorID + '][' + sid + '][stock]',          $row.find('.size-stock').val());
                    // formData.append('size[' + colorID + '][' + sid + '][purchase_price]', $row.find('.size-purchase-price').val());
                    formData.append('size[' + colorID + '][' + sid + '][RegularPrice]',     $row.find('.RegularPrice').val());
                    formData.append('size[' + colorID + '][' + sid + '][sale_price]',     $row.find('.size-sale-price').val());
                });
            });

            if (hasError) return;
        }

        formData.append('_token', token);

        $.ajax({
            type: 'POST',
            url: '{{ url("admin/products/save") }}',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response.status)

                // এখানে JSON.parse লাগবে না
                if (response.status === 'success') {
                    toastr.success(response.message);

                    setTimeout(function () {
                        window.location.href = "{{ url('admin/products/manage') }}";
                    }, 1000);

                } else {
                    toastr.error(response.message);
                }
            }
        });


    });

});


        var PostImages = [];

        function prevPost_Img() {
            var PostImage = document.getElementById('PostImage').files;

            for (i = 0; i < PostImage.length; i++) {
                if (check_duplicate(PostImage[i].name)) {
                    PostImages.push({
                        "name": PostImage[i].name,
                        "url": URL.createObjectURL(PostImage[i]),
                        "file": PostImage[i],
                    });
                } else {
                    alert(PostImage[i].name + 'is already added to your list');
                }
            }

            document.getElementById("prevFile").innerHTML = PostImage_show();

        }

        function check_duplicate(name) {
            var PostImage = true;
            if (PostImages.length > 0) {
                for (e = 0; e < PostImages.length; e++) {
                    if (PostImages[e].name == name) {
                        PostImage = false;
                        break;
                    }
                }
            }
            return PostImage;
        }

        function PostImage_show() {
            var PostImage = "";
            PostImages.forEach((i) => {
                PostImage += `<div class="postImg" style="width:25%;float:left;position:relative;">
                                    <img src="` + i.url + `" alt="" id="previewImage" style="border-radius: 10px;width:100%;padding:5px;">
                                    <span onclick="removeSelectedPostImage(` + PostImages.indexOf(i) + `)" style="position: absolute;right: 0;cursor: pointer;font-size: 31px;color: red;margin-top: -8px;margin-right: 8px;">&times</span>
                                </div>`;
            })
            return PostImage;
        }

        function removeSelectedPostImage(e) {
            PostImages.splice(e, 1);
            document.getElementById("prevFile").innerHTML = PostImage_show();
        }

        var editPostImages = [];

        function editprevPost_Img() {
            $('#viewprevFile').html('');
            var editPostImage = document.getElementById('editPostImage').files;

            for (i = 0; i < editPostImage.length; i++) {
                if (check_duplicate(editPostImage[i].name)) {
                    editPostImages.push({
                        "name": editPostImage[i].name,
                        "url": URL.createObjectURL(editPostImage[i]),
                        "file": editPostImage[i],
                    });
                } else {
                    alert(editPostImage[i].name + 'is already added to your list');
                }
            }

            document.getElementById("editprevFile").innerHTML = editPostImage_show();

        }

        function check_duplicate(name) {
            var editPostImage = true;
            if (editPostImages.length > 0) {
                for (e = 0; e < editPostImages.length; e++) {
                    if (editPostImages[e].name == name) {
                        editPostImage = false;
                        break;
                    }
                }
            }
            return editPostImage;
        }

        function editPostImage_show() {
            var editPostImage = "";
            editPostImages.forEach((i) => {
                editPostImage += `<div class="postImg" style="width:25%;float:left;position:relative;">
                                    <img src="` + i.url + `" alt="" id="previewImage" style="border-radius: 10px;width:100%;padding:5px;">
                                    <span onclick="removeSelectededitPostImage(` + editPostImages.indexOf(i) + `)" style="position: absolute;right: 0;cursor: pointer;font-size: 31px;color: red;margin-top: -8px;margin-right: 8px;">&times</span>
                                </div>`;
            })
            return editPostImage;
        }

        function removeSelectededitPostImage(e) {
            editPostImages.splice(e, 1);
            document.getElementById("editprevFile").innerHTML = editPostImage_show();
        }
    </script>

    <script>
        $(".summernote").summernote({
            placeholder: "Enter Your Text Here",
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".btn-increment").click(function () {
                var html = $(".clone").html();
                $(".increment").after(html);
            });
            $("body").on("click", ".btn-danger", function () {
                $(this).parents(".control-group").remove();
            });

        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".increment_btn").click(function () {
                var html = $(".clone_price").html();
                $(".increment_price").after(html);
            });
            $("body").on("click", ".remove_btn", function () {
                $(this).parents(".increment_control").remove();
            });

            $("#category_id").select2();
            $("#subcategory_id").select2();
            $("#childcategory_id").select2();
        });

        // category to sub
        $("#category_id").on("change", function () {
            var ajaxId = $(this).val();
            if (ajaxId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('ajax-product-subcategory') }}?category_id=" + ajaxId,
                    success: function (res) {
                        if (res) {
                            $("#subcategory_id").empty();
                            $("#subcategory_id").append('<option value="0">Choose...</option>');
                            $.each(res, function (key, value) {
                                $("#subcategory_id").append('<option value="' + key + '">' +
                                    value + "</option>");
                            });
                        } else {
                            $("#subcategory_id").empty();
                        }
                    },
                });
            } else {
                $("#subcategory_id").empty();
            }
        });

        // subcategory to childcategory
        $("#subcategory_id").on("change", function () {
            var ajaxId = $(this).val();
            if (ajaxId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('ajax-product-childcategory') }}?subcategory_id=" + ajaxId,
                    success: function (res) {
                        if (res) {
                            $("#childcategory_id").empty();
                            $("#childcategory_id").append('<option value="0">Choose...</option>');
                            $.each(res, function (key, value) {
                                $("#childcategory_id").append('<option value="' + key + '">' +
                                    value + "</option>");
                            });
                        } else {
                            $("#childcategory_id").empty();
                        }
                    },
                });
            } else {
                $("#childcategory_id").empty();
            }
        });
    </script>
@endsection
