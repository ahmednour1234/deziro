@extends('admin.layouts.app')

{{-- @yield('title', 'Product') --}}

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span>Individual Selling Products</h4>

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
                        <span>Showing {{ $listSellingProduct->firstItem() }} to {{ $listSellingProduct->lastItem() }}
                            of total {{ $listSellingProduct->total() }} entries</span>
                    </div>
                </div>
            </nav>
            <table class="table" id="table_id" style="width: 100%">
                <thead class="text-center">
                    <tr>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listSellingProduct', ['sort' => 'created_at', 'direction' => $sortColumn == 'created_at' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">Created
                            At @if ($sortColumn == 'created_at')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listSellingProduct', ['sort' => 'id', 'direction' => $sortColumn == 'id' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                Product id
                            @if ($sortColumn == 'id')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listSellingProduct', ['sort' => 'name', 'direction' => $sortColumn == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                Product name
                            @if ($sortColumn == 'name')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listSellingProduct', ['sort' => 'user_id', 'direction' => $sortColumn == 'user_id' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                 Individual id
                            @if ($sortColumn == 'user_id')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listSellingProduct', ['sort' => 'category_id', 'direction' => $sortColumn == 'category_id' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                               Sub
                            @if ($sortColumn == 'category_id')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listSellingProduct', ['sort' => 'condition', 'direction' => $sortColumn == 'condition' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                condition
                            @if ($sortColumn == 'condition')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listSellingProduct', ['sort' => 'price', 'direction' => $sortColumn == 'price' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                              price
                            @if ($sortColumn == 'price')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listSellingProduct', ['sort' => 'money_collection', 'direction' => $sortColumn == 'money_collection' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                             money_collection
                            @if ($sortColumn == 'money_collection')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th>View Product</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($listSellingProduct as $key => $sellingProduct)
                        <tr>
                            <td>{{ $sellingProduct->created_at->format('Y-m-d ') }} </td>
                            <td>{{ $sellingProduct->id }}</td>
                            <td>{{ $sellingProduct->name }} </td>
                            <td><a href="individualDetail/{{ $sellingProduct->user_id }}" class="btn btn-dark btn-sm">
                                    {{ $sellingProduct->user_id }}
                                </a></td>
                            <td>{{ $sellingProduct->category->name }} </td>
                            <td>{{ $sellingProduct->condition }}</td>
                            <td>{{ $sellingProduct->price }}</td>
                            <td>{{ $sellingProduct->money_collection }}</td>
                            <td>
                                @if ($sellingProduct->type == 'sell')
                                    <a href="/sellingProductDetail/{{ $sellingProduct->id }}"
                                        class="btn btn-info btn-sm">View sell Details</a>
                                @elseif($sellingProduct->type == 'bid')
                                    <a href="/bidProductDetail/{{ $sellingProduct->id }}" class="btn btn-info btn-sm">View
                                        bid Details</a>
                                @else
                                    <a href="/swapProductDetail/{{ $sellingProduct->id }}" class="btn btn-info btn-sm">View
                                        swap Details</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $listSellingProduct->links() }}
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
                        location.href = 'sellingProduct'
                    }
                 });

            // $.fn.dataTable.ext.errMode = 'throw';
            // var table = $('#table_id').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     ajax: "sellingProduct",
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

            //                     return `${data.user.first_name  + " " + data.user.last_name}`

            //             }
            //         },
            //         // {
            //         //     data: 'category.type'
            //         // },
            //         {
            //             data: 'category.name'
            //         },
            //         {
            //             data: 'quantity'
            //         },
            //         // {
            //         //     data: 'condition'
            //         // },
            //         {
            //             data: 'price'
            //         },
            //         {
            //             data: null,
            //             render: function(data, row, type) {
            //                 return `
            //                 <a href="/productImages/${data.id}" class="btn btn-info btn-sm">Add Images</a>
            //                   `;
            //             }
            //         },

            //            {
            //             data: null,
            //             render: function(data, row, type) {
            //                 return `
            //                 <a href="/sellingProductDetail/${data.id}" class="btn btn-dark btn-sm">View Details</a>
            //                   `;
            //             }
            //         },

            //         {
            //             data: null,
            //             render: function(data, row, type) {
            //                 // console.log(data)
            //                 return `
            //                 <button type="button" value="${data.id}" class="edit_sellingProduct  btn btn-primary editbtn btn-sm "><i class="fa fa-edit"></i></button>`;
            //                 //   <button type="button" value="${data.id}" class="delete_sellingProduct btn btn-danger deletebtn btn-sm "><i class="fa fa-trash"></i></button>
            //             }
            //         }

            //     ]

            // });


            // $.ajaxSetup({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     }
            // });

            // //Add Product
            // $(document).on('click', '.add_stelling_product', function(e) {
            //     e.preventDefault();
            //     var data = {
            //         'store': $('.store').val(),
            //         'category': $('.category').val(),
            //         'category': $('.category').val(),
            //         'name': $('.name').val(),
            //         'condition': $('.condition').val(),
            //         'quantity': $('.quantity').val(),
            //         'price': $('.price').val(),
            //         'description': $('.description').val(),
            //     }
            //     console.log(data)

            //     // let formData = new FormData($('#addProductForm')[0]);
            //     // console.log(formData)
            //     $.ajax({
            //         type: "POST",
            //         url: "/addNewSellingProduct",
            //         data: data,
            //         dataType: 'json',
            //         success: function(response) {
            //             // console.log(response)
            //             if (response.status == 400) {
            //                 const store = $('#store').val();
            //                 store == '' ? $('#error_store').html(response.errors.store) : $(
            //                     '#error_store').html('')
            //                 const category = $('#category').val();
            //                 category == '' ? $('#error_category').html(response.errors
            //                     .category) : $('#error_category').html('')
            //                 const category = $('#category').val();
            //                 category == '' ? $('#error_subCategory').html(response.errors
            //                     .category) : $('#error_subCategory').html('')
            //                 const name = $('#name').val();
            //                 name == '' ? $('#error_name').html(response.errors.name) : $(
            //                     '#error_name').html('')
            //                 const condition = $('#condition').val();
            //                 condition == '' ? $('#error_condition').html(response.errors
            //                     .condition) : $('#error_condition').html('')
            //                 const quantity = $('#quantity').val();
            //                 quantity == '' ? $('#error_quantity').html(
            //                     response.errors.quantity) : $(
            //                     '#error_quantity').html('')
            //                 const price = $('#price').val();
            //                 price == '' ? $('#error_price').html(response.errors.price) : $(
            //                     '#error_price').html('')
            //                 const description = $('#description').val();
            //                 description == '' ? $('#error_description').html(response.errors
            //                     .description) : $('#error_description').html('')


            //             } else {
            //                 $('#success_message').text(response.message)
            //                 $('#success_message').addClass('alert alert-success')
            //                 $('#success_message').text(response.message)
            //                 $('#addSellingProductModal').modal('hide')
            //                 $('#addSellingProductModal').find('input').val('')
            //                 $('#addSellingProductModal').find('textarea').val('')
            //                 $('#addSellingProductModal').find('select').val('')

            //                  table.ajax.reload();
            //             }
            //         }
            //     })
            // })


            // //Edit Product
            // $(document).on('click', '.edit_sellingProduct', function(e) {
            //     e.preventDefault();
            //     var sellingProduct_id = $(this).val();
            //     // console.log(sellingProduct_id)
            //     $('#editSellingProductModal').modal('show')
            //     $.ajax({
            //         type: 'GET',
            //         url: 'editSellingProduct/' + sellingProduct_id,
            //         success: function(response) {
            //             console.log(response);

            //             if (response.status == 404) {
            //                 $('#success_message').html("")
            //                 $('#success_message').addClass('alert alert-danger')
            //                 $('#success_message').text(response.message)
            //             } else {
            //                 $('#edit_selling_id').val(response.sellingProduct.id)
            //                 $('#edit_selling_individual_id').val(response.sellingProduct.user_id)
            //                 // $('#edit_store').val(response.sellingProduct.user_id)
            //                 if (response.users.type == '2') {
            //                     $('#edit_selling_store').removeAttr('disabled')
            //                     $('#edit_selling_store').val(response.sellingProduct.user_id)
            //                     console.log(response.sellingProduct.user_id);
            //                 } else {
            //                     $('#edit_selling_store').attr('disabled', 'disabled')
            //                     $('#edit_selling_store').val('null')
            //                     $('#edit_selling_individual_id').val(response.sellingProduct.user_id)
            //                     console.log(response.sellingProduct.user_id);
            //                 }
            //                 $('#edit_selling_category').val(response.sellingProduct.category_id)
            //                 $('#edit_selling_subcategory').val(response.sellingProduct.category_id)
            //                 $('#edit_selling_name').val(response.sellingProduct.name)
            //                 $('#edit_selling_condition').val(response.sellingProduct.condition)
            //                 $('#edit_selling_quantity').val(response.sellingProduct
            //                     .quantity)
            //                 $('#edit_selling_price').val(response.sellingProduct.price)
            //                 $('#edit_selling_description').val(response.sellingProduct.description)

            //             }
            //         }
            //     })
            // })


            // //Update User
            // $(document).on('click', '.update_sellingProduct', function(e) {
            //     e.preventDefault();
            //     $(this).text('Updating')
            //     var product_id = $('#edit_selling_id').val();
            //     var data = {
            //         'store': $('#edit_selling_individual_id').val(),
            //         'category': $('#edit_selling_category').val(),
            //         'category': $('#edit_selling_subcategory').val(),
            //         'name': $('#edit_selling_name').val(),
            //         'condition': $('#edit_selling_condition').val(),
            //         'quantity': $('#edit_selling_quantity').val(),
            //         'price': $('#edit_selling_price').val(),
            //         'description': $('#edit_selling_description').val(),
            //     }
            //     console.log(data)
            //     $.ajax({
            //         type: "POST",
            //         url: "/updateSellingProduct/" + product_id,
            //         data: data,
            //         dataType: 'json',
            //         success: function(response) {
            //             console.log(response)

            //             if (response.status == 400) {
            //                 const store = $('#edit_selling_store').val();
            //                 store == '' ? $('#error_edit_selling_store').html(response.errors.store) : $('#error_edit_selling_store').html('')
            //                 const category = $('#edit_selling_category').val();
            //                 category == '' ? $('#error_edit_selling_category').html(response
            //                         .errors.category) : $('#error_edit_selling_category')
            //                     .html('')
            //                 const category = $('#edit_selling_subcategory').val();
            //                 category == '' ? $('#error_edit_selling_subcategory').html(
            //                     response.errors.category) : $(
            //                     '#error_edit_selling_subcategory').html('')
            //                 const name = $('#edit_selling_name').val();
            //                 name == '' ? $('#error_edit_selling_name').html(response
            //                     .errors.name) : $('#error_edit_selling_name').html(
            //                     '')
            //                 const type = $('#edit_selling_type').val();
            //                 type == '' ? $('#error_edit_selling_type').html(response
            //                     .errors.type) : $('#error_edit_selling_type').html(
            //                     '')
            //                 const condition = $('#edit_selling_condition').val();
            //                 condition == '' ? $('#error_edit_selling_condition').html(
            //                     response.errors.condition) : $(
            //                     '#error_edit_selling_condition').html('')
            //                 const description = $('#edit_selling_description').val();
            //                 description == '' ? $('#error_edit_selling_description').html(response
            //                         .errors.description) : $('#error_edit_description')
            //                     .html('')
            //             } else if (response.status == 404) {
            //                 $('#update_error_message').html('');
            //                 $('#update_error_message').addClass('alert alert-danger');
            //                 $('#update_error_message').text('response.message');
            //                 $('.update_product').text('Update')
            //             } else {
            //                 $('#success_message').text(response.message)
            //                 $('#success_message').addClass('alert alert-success')
            //                 $('#success_message').text(response.message)
            //                 $('#editSellingProductModal').modal('hide')
            //                 $('#editSellingProductModal').find('input').val('')
            //                 $('#editSellingProductModal').find('textarea').val('')
            //                 $('.update_sellingProduct').text('Update')

            //                  table.ajax.reload();
            //             }
            //         }
            //     })
            // })


            // //delete Modal
            // $(document).on('click', '.delete_sellingProduct', function(e) {
            //     e.preventDefault();

            //     var sellingProduct_id = $(this).val();
            //     // var store = $(this).val();
            //     console.log(sellingProduct_id)
            //     $('#delete_id').val(sellingProduct_id)
            //     $('#title_product_delete').text('Are you sure?')
            //     $('#deleteSellingProductModal').modal('show')
            // })
            // delete Product
            // $(document).on('click', '.delete_sellingProduct_btn', function(e) {
            //     e.preventDefault();

            //     var sellingProduct_id = $('#delete_id').val();

            //     $.ajax({
            //         type: 'GET',
            //         url: 'deleteSellingProduct/' + sellingProduct_id,
            //         success: function(response) {
            //             // console.log(response);
            //             $('#success_message').addClass('alert alert-success')
            //             $('#success_message').text(response.message)
            //             $('#deleteSellingProductModal').modal('hide')

            //              table.ajax.reload();
            //         }
            //     })
            // })

            //search
            // $("#myInput").on("keyup", function() {
            //     var value = $(this).val().toLowerCase();
            //     $("#myTable tr").filter(function() {
            //         $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            //     });
            // });

            // $('#category').on('change', function(){
            //    let category_id = $(this).val();

            //    $.ajax({
            //     type: 'POST',
            //     url: '/getSubCategorys',
            //     data:'category_id='+category_id+'&_token={{ csrf_token() }}',
            //     success: function (response){
            //         $('#category').html(response)
            //     }
            //    })
            // })

            // $('#edit_category').on('change', function(){
            //    let category_id = $(this).val();

            //    $.ajax({
            //     type: 'POST',
            //     url: '/getSubCategorys',
            //     data:'category_id='+category_id+'&_token={{ csrf_token() }}',
            //     success: function (response){
            //         $('#edit_subcategory').html(response)
            //     }
            //    })
            // })

        })
    </script>
@endsection
