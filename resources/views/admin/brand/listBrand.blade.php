@extends('admin.layouts.app')

@section('content')
@include('admin.brand.crud_brand.addBrandModal')
@include('admin.brand.crud_brand.editBrandModal')

    {{-- Active Modal --}}
@include('admin.moreDetails.activate_modal.activeModal')
{{-- Inactive Modal --}}
@include('admin.moreDetails.activate_modal.inactiveModal')

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Brands</h4>


    <div id="success_message"></div>


    <div class="card">
        <div class="d-flex justify-content-between  items-center">
            <div class="col-lg-3 col-md-6 mb-0 mt-4  mx-3">
                <form action="" method="get" id="searchForm">
                    <div class="d-flex gap-3">
                        <div class=" col-lg-3 input-group input-group-merge">
                            <span class="input-group-text" id="addon-search"><i class="bx bx-search"></i></span>
                            <input type="text" id="search" name="search" class="form-control"
                                placeholder="Search ..." value="{{ request()->get('search') }}" autofocus>
                        </div>
                    </div>
                </form>
            </div>
            <div class="m-3 d-flex gap-2">
                <div>
                    <button type="button" class="btn btn-primary" id="addModalBtn" data-bs-toggle="modal"
                        data-bs-target="#addBrandModal">
                        <span class="flex-center">Add <i class="bx bx-plus"></i></span>
                    </button>
                </div>
                <ul class="pagination    ">
                    <li class="">
                        <div class="btn-group">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">{{ currentLimit() }}</button>
                            <ul class="dropdown-menu" style="min-width: auto;">
                                @foreach (limits() as $limit)
                                    <li><a class="dropdown-item {{ $limit['active'] ? 'active' : '' }}"
                                            href="{{ $limit['url'] }}">{{ $limit['label'] }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>


        <div class="table-responsive text-nowrap">
            <nav class="nav-pagination" aria-label="Page navigation">
                <div class="row mb-0">
                    <div class="label col-lg-10 col-md-6 mx-3">
                        <span>Showing {{ $listBrands->firstItem() }} to {{ $listBrands->lastItem() }}
                            of total {{ $listBrands->total() }} entries</span>
                    </div>
                </div>
            </nav>
            <table class="table  " id="table_id" style="width: 100%" class="text-center">
                <thead class="text-center">
                    <tr>
                        <th>Created At</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($listBrands as $brand)
                        <tr>
                            <td>{{ $brand->created_at->format('d-m-Y') }}</td>
                            <td>{{ $brand->name }}</td>
                            <td> <a data-download-src="{{ Storage::url($brand->image_path) }} "
                                href="{{ Storage::url($brand->image_path) }} " data-fancybox
                                data-caption="brand">
                                <img width="200" id="brand-img" style="object-fit: contain"
                                    src="{{ Storage::url($brand->image_path) }} " alt="">
                            </a>



                            <td>
                                <button class="btn btn-primary btn-sm edit_brand"
                                    value="{{ $brand->id }}" data-value1="{{ $brand->name}}">Edit</button>
                                @if ($brand->is_active == 1)
                                    <button class="btn btn-success btn-sm active_brand" data-value1="{{ $brand->name}}"
                                        value="{{ $brand->id }}">Active</button>
                                @else
                                    <button class="btn btn-danger btn-sm inactive_brand" data-value1="{{ $brand->name}}"
                                        value="{{ $brand->id }}">Inactive</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $listBrands->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var selectize = $('#categorie').selectize({
                plugins: ['remove_button'],
                delimiter: ',',
                persist: false,

                onDropdownOpen: function($dropdown) {
                    // Set focus to the search input when the dropdown is opened
                    $dropdown.find('.selectize-input input[type="text"]').first().focus();
                }
            })[0].selectize;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.add_brand', function(e) {
                e.preventDefault();

                let formData = new FormData($('#AddBrandForm')[0]);
                // console.log(formData)
                $.ajax({
                    type: 'POST',
                    url: '/addNewBrand',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);

                        if (response.status == 400) {
                            response.errors.name != undefined ? $('#error_name').html(response
                                .errors.name) : $('#error_name').html('')
                            response.errors.image_path != undefined ? $('#error_image').html(response
                                .errors.image_path) : $('#error_image').html('')

                        } else {
                            $('#success_message').text(response.message)
                            $('#success_message').addClass('alert alert-success')
                            $('#success_message').text(response.message)
                            $('#addBrandModal').modal('hide')
                            $('#addBrandModal').find('input').val('')
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                    }
                })
            })


               $(document).on('click', '.edit_brand', function(e) {
                e.preventDefault();
                var brand_id = $(this).val();
                console.log(brand_id)
                $('#editBrandModal').modal('show')
                $.ajax({
                    type: 'GET',
                    url: 'editBrand/' + brand_id,
                    success: function(response) {

                        var brand = response.Brand;
                        var selectedCategories = response.selectedCategories;
                        var allCategories = response.allCategories;

                        selectize.setValue(selectedCategories);
                        if (response.status == 404) {
                            $('#success_message').html("")
                            $('#success_message').addClass('alert alert-danger')
                            $('#success_message').text(response.message)
                        } else {
                            $('#edit_id').val(response.Brand.id)
                            $('#edit_name').val(response.Brand.name)
                            $('#edit_showImg').attr("src", "{{ Storage::url('/') }}" + response.Brand.image_path);
                            var options = '';
                            allCategories.forEach(function(category) {
                                var selected = selectedCategories.includes(category.id) ?
                                    'selected' : '';
                                options += '<option value="' + category.id + '" ' +
                                    selected + '>' + category.name + '</option>';
                            });
                            $('#categorie').html(options);
                        }
                    }
                })
            })

            $(document).on('click', '.update_brand', function(e) {
                e.preventDefault();
                var brand_id = $('#edit_id').val();
                let formData = new FormData($('#EditBrandForm')[0]);
                $.ajax({
                    type: "POST",
                    url: "updateBrand/" + brand_id,
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response)
                        if (response.status == 400) {
                            response.errors.name != undefined ? $(
                                    '#error_edit_name').html(response.errors.name) :
                                $('#error_edit_name').html('')

                         } else {
                            $('#success_message').text(response.message)
                            $('#success_message').addClass('alert alert-success')
                            $('#editCategorieModal').modal('hide')

                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);

                        }
                    }
                })
            })




            $(document).on('click', '.active_brand', function(e) {
                e.preventDefault();
                var name = $(this).data('value1');
                var brand_id = $(this).val();
                $('#active_id').val(brand_id)
                $('#inactive_title').text('Inactivate  ' + name)
                $('#inactive_msg').text('Are you sure do you want to inactivate  ' + name)
                $('#activeModal').modal('show')
            })

            $(document).on('click', '.inactive_brand', function(e) {
                e.preventDefault();
                var name = $(this).data('value1');
                var brand_id = $(this).val();
                $('#inactive_id').val(brand_id)
                $('#active_title').text('Activate  ' + name)
                $('#active_msg').text('Are you sure do you want to activate  ' + name)
                $('#inactiveModal').modal('show')
            })


            $(document).on('click', '.is_active', function(e) {
                e.preventDefault();

                var brand_id = $('#active_id').val();
                console.log(brand_id)
                $.ajax({
                    type: 'POST',
                    url: 'brand_active/' + brand_id,
                    success: function(response) {
                        console.log(response);
                        $('#success_message').addClass('alert alert-success')
                        $('#success_message').text(response.message)
                        $('#activeModal').modal('hide')

                        // location.reload(true)
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                })
            })

            $(document).on('click', '.is_inactive', function(e) {
                e.preventDefault();

                var brand_id = $('#inactive_id').val();
                console.log(brand_id)
                $.ajax({
                    type: 'POST',
                    url: 'brand_inactive/' + brand_id,
                    success: function(response) {
                        console.log(response);
                        $('#success_message').addClass('alert alert-success')
                        $('#success_message').text(response.message)
                        $('#inactiveModal').modal('hide')

                        // location.reload(true)
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                })
            })

        })
    </script>
@endsection
