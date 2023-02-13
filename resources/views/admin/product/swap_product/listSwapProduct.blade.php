@extends('admin.layouts.app')

{{-- @yield('title', 'Product') --}}

@section('content')


    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span>Individual Swap Products</h4>

    <!-- Basic Bootstrap Table -->

    <div id="success_message"></div>

    <div class="card">
        <div class="d-flex justify-content-between  items-center">
            <div class="col-lg-3 col-md-6 mb-0 mt-3  mx-3">
                <form action="" method="get" id="searchForm">
                    <div class="d-flex gap-3">
                        <div class=" col-lg-3 input-group input-group-merge">
                            <span class="input-group-text" id="addon-search"><i class="bx bx-search"></i></span>
                            <input type="text" id="search" name="search" class="form-control"
                                placeholder="Search ..." value="{{ request()->get('search') }}" autofocus>
                        </div>

                        {{-- <div class="col-lg-3 input-group input-group-merge">
                        <span class="input-group-text" id="addon-search"><i class="bx bx-search"></i></span>
                        <input type="date" value="{{ request()->get('date') }}" name="date" class="form-control" placeholder="Filter By Date">
                    </div> --}}

                        {{-- <div class="col-lg-3 input-group input-group-merge">
                        <span class="input-group-text" id="addon-search"><i class="bx bx-search"></i></span>
                       <select name="store_name" class="form-select" >
                        <option value="">Select All Categories</option>

                       </select>
                    </div> --}}
                        {{-- <div class="">
                        <button type="submit" class="btn btn-dark">Filter</button>
                    </div> --}}
                    </div>

                </form>
            </div>
            <div class="m-3">
                <ul class="pagination  mb-0  ">
                    <li class="" style="padding-left: 290px">
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
                <div class="row mt-3 ">
                    <div class="label col-lg-10 col-md-6 mx-3">
                        <span>Showing {{ $listSwapProduct->firstItem() }} to {{ $listSwapProduct->lastItem() }}
                            of total {{ $listSwapProduct->total() }} entries</span>
                    </div>
                </div>
            </nav>
            <table class="table" id="table_id" style="width: 100%">
                <thead class="text-center">
                    <tr>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listSwapProduct', ['sort' => 'created_at', 'direction' => $sortColumn == 'created_at' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            Created  At
                             @if ($sortColumn == 'created_at')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listSwapProduct', ['sort' => 'id', 'direction' => $sortColumn == 'id' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                       product id
                             @if ($sortColumn == 'id')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listSwapProduct', ['sort' => 'name', 'direction' => $sortColumn == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                           product name
                             @if ($sortColumn == 'name')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listSwapProduct', ['sort' => 'user_id', 'direction' => $sortColumn == 'user_id' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            individual id
                             @if ($sortColumn == 'user_id')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listSwapProduct', ['sort' => 'subcategory_id', 'direction' => $sortColumn == 'subcategory_id' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            Sub
                             @if ($sortColumn == 'subcategory_id')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listSwapProduct', ['sort' => 'condition', 'direction' => $sortColumn == 'condition' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            Condition
                             @if ($sortColumn == 'condition')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        {{-- <th>view bids</th> --}}
                        <th>View Product</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($listSwapProduct as $key => $swapProduct)
                        <tr>
                            <td>{{ $swapProduct->created_at->format('Y-m-d ') }} </td>
                            <td>{{ $swapProduct->id }}</td>
                            <td>{{ $swapProduct->name }} </td>
                            <td><a href="individualDetail/{{ $swapProduct->user_id }}" class="btn btn-dark btn-sm">
                                    {{ $swapProduct->user_id }}
                                </a></td>
                            <td>{{ $swapProduct->subcategorie->name }} </td>
                            <td>{{ $swapProduct->condition }}</td>
                            <td>
                                @if ($swapProduct->type == 'sell')
                                    <a href="/swapProductDetail/{{ $swapProduct->id }}"
                                        class="btn btn-info btn-sm">View sell Details</a>
                                @elseif($swapProduct->type == 'bid')
                                    <a href="/swapProductDetail/{{ $swapProduct->id }}" class="btn btn-info btn-sm">View
                                        bid Details</a>
                                @else
                                    <a href="/swapProductDetail/{{ $swapProduct->id }}" class="btn btn-info btn-sm">View
                                        swap Details</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $listSwapProduct->links() }}
                </div>
            </div>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $('#search').on('keyup',function(){
                    const z = $('#search').val();
                    console.log(z)
                    if(z.length<1){
                        location.href = 'swapProduct'
                    }
                 });
            // $.fn.dataTable.ext.errMode = 'throw';
            // var table = $('#table_id').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     ajax: "swapProduct",
            //     columns: [{
            //             "title": "#",
            //             render: function(data, type, row, meta) {
            //                 return meta.row + meta.settings._iDisplayStart + 1;
            //             }
            //         },
            //         {
            //             data: 'name'
            //         },
            //         {
            //             data: null,
            //             render: function(data, row, type) {

            //                 return `${data.user.first_name  + " " + data.user.last_name}`

            //             }
            //         },
            //         {
            //             data: 'category.type'
            //         },
            //         {
            //             data: 'subcategorie.name'
            //         },
            //         {
            //             data: 'condition'
            //         },
            //         {
            //             data: null,
            //             render: function(data, row, type) {
            //                 return `
            //                 <a href="/productImages/${data.id}" class="btn btn-info btn-sm">Add Images</a>
            //                   `;
            //             }
            //         },
            //         {
            //             data: null,
            //             render: function(data, row, type) {
            //                 return `
            //                 <a href="/swapProductDetail/${data.id}" class="btn btn-dark btn-sm">View Details</a>
            //                   `;
            //             }
            //         },
            //         {
            //             data: null,
            //             render: function(data, row, type) {
            //                 return `
            //                 <a href="/viewSwapProduct/${data.id}" class="btn btn-success btn-sm">View Swap</a>
            //                   `;
            //             },
            //             searchable: true,
            //             orderable: true
            //         },

            //         {
            //             data: null,
            //             render: function(data, row, type) {
            //                 console.log(data)
            //                 return `
            //                 <button type="button" value="${data.id}" class="edit_swapProduct  btn btn-primary editbtn btn-sm "><i class="fa fa-edit"></i></button>

            //                   `;
            //                 //   <button type="button" value="${data.id}" class="delete_swapProduct btn btn-danger deletebtn btn-sm "><i class="fa fa-trash"></i></button>
            //             }
            //         }
            //     ]
            // });


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Edit Product
            $(document).on('click', '.edit_swapProduct', function(e) {
                e.preventDefault();
                var swapProduct_id = $(this).val();
                // console.log(swapProduct_id)
                $('#editSwapProductModal').modal('show')
                $.ajax({
                    type: 'GET',
                    url: 'editSwapProduct/' + swapProduct_id,
                    success: function(response) {
                        console.log(response);
                        if (response.status == 404) {
                            $('#success_message').html("")
                            $('#success_message').addClass('alert alert-danger')
                            $('#success_message').text(response.message)
                        } else {
                            $('#edit_swap_id').val(response.swapProduct.id)
                            $('#edit_swap_individual_id').val(response.swapProduct.user_id)
                            $('#edit_swap_store').val(response.users.full_name).change()
                            if (response.users.type == '2') {
                                $('#edit_swap_store').removeAttr('disabled')
                                $('#edit_swap_store').val(response.swapProduct.user_id)
                                console.log(response.swapProduct.user_id);
                            } else {
                                $('#edit_swap_store').attr('disabled', 'disabled')
                                $('#edit_swap_store').val('null')
                                $('#edit_swap_individual_id').val(response.swapProduct.user_id)
                                console.log(response.swapProduct.user_id);
                            }
                            $('#edit_swap_category').val(response.swapProduct.category_id)
                            $('#edit_swap_subcategory').val(response.swapProduct.subcategory_id)
                            $('#edit_swap_name').val(response.swapProduct.name)
                            $('#edit_swap_condition').val(response.swapProduct.condition)
                            $('#edit_swap_description').val(response.swapProduct.description)
                        }
                    }
                })
            })


            //  Update Product
            $(document).on('click', '.update_swapProduct', function(e) {
                e.preventDefault();
                $(this).text('Updating')
                var product_id = $('#edit_swap_id').val();
                var data = {
                    'store': $('#edit_swap_individual_id').val(),
                    'category': $('#edit_swap_category').val(),
                    'subcategory': $('#edit_swap_subcategory').val(),
                    'name': $('#edit_swap_name').val(),
                    'condition': $('#edit_swap_condition').val(),
                    'description': $('#edit_swap_description').val(),
                }
                console.log(data)

                $.ajax({
                    type: "POST",
                    url: "/updateSwapProduct/" + product_id,
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response)

                        if (response.status == 400) {
                            const store = $('#edit_swap_store').val();
                            store == '' ? $('#error_edit_swap_store').html(response.errors
                                .store) : $('#error_edit_swap_store').html('')
                            const category = $('#edit_swap_category').val();
                            category == '' ? $('#error_edit_swap_category').html(response
                                    .errors.category) : $('#error_edit_swap_category')
                                .html('')
                            const subcategory = $('#edit_swap_subcategory').val();
                            subcategory == '' ? $('#error_edit_swap_subcategory').html(
                                response.errors.subcategory) : $(
                                '#error_edit_swap_subcategory').html('')
                            const name = $('#edit_swap_name').val();
                            name == '' ? $('#error_edit_swap_name').html(response
                                .errors.name) : $('#error_edit_name').html(
                                '')
                            const condition = $('#edit_swap_condition').val();
                            condition == '' ? $('#error_edit_swap_condition').html(
                                response.errors.condition) : $(
                                '#error_edit_swap_condition').html('')

                            const description = $('#edit_swap_description').val();
                            description == '' ? $('#error_edit_swap_description').html(response
                                    .errors.description) : $('#error_edit_swap_description')
                                .html('')
                        } else if (response.status == 404) {
                            $('#update_error_message').html('');
                            $('#update_error_message').addClass('alert alert-danger');
                            $('#update_error_message').text('response.message');
                            $('.update_product').text('Update')
                        } else {
                            $('#success_message').text(response.message)
                            $('#success_message').addClass('alert alert-success')
                            $('#success_message').text(response.message)
                            $('#editSwapProductModal').modal('hide')
                            $('#editSwapProductModal').find('input').val('')
                            $('#editSwapProductModal').find('textarea').val('')
                            $('.update_sellingProduct').text('Update')

                            table.ajax.reload();
                        }
                    }
                })
            })


            //delete Modal
            $(document).on('click', '.delete_swapProduct', function(e) {
                e.preventDefault();

                var swapProduct_id = $(this).val();
                // var store = $(this).val();
                console.log(swapProduct_id)
                $('#delete_id').val(swapProduct_id)
                $('#title_product_delete').text('Are you sure?')
                $('#deleteSwapProductModal').modal('show')
            })

            // delete Product
            $(document).on('click', '.delete_swapProduct_btn', function(e) {
                e.preventDefault();

                var swapProduct_id = $('#delete_id').val();

                $.ajax({
                    type: 'GET',
                    url: 'deleteSwapProduct/' + swapProduct_id,
                    success: function(response) {
                        // console.log(response);
                        $('#success_message').addClass('alert alert-success')
                        $('#success_message').text(response.message)
                        $('#deleteSwapProductModal').modal('hide')

                        table.ajax.reload();
                    }
                })
            })


        })
    </script>
@endsection
