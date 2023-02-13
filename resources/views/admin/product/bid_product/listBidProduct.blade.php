@extends('admin.layouts.app')

{{-- @yield('title', 'Product') --}}

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span>Individual Bid Products</h4>

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
                        <span>Showing {{ $listBidProduct->firstItem() }} to {{ $listBidProduct->lastItem() }}
                            of total {{ $listBidProduct->total() }} entries</span>
                    </div>
                </div>
            </nav>
            <table class="table" id="table_id" style="width: 100%">
                <thead class="text-center">
                    <tr>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listBidProduct', ['sort' => 'created_at', 'direction' => $sortColumn == 'created_at' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            Created At
                             @if ($sortColumn == 'created_at')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listBidProduct', ['sort' => 'id', 'direction' => $sortColumn == 'id' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            prodct id
                             @if ($sortColumn == 'id')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listBidProduct', ['sort' => 'name', 'direction' => $sortColumn == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                        product name  Created At
                             @if ($sortColumn == 'name')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listBidProduct', ['sort' => 'user_id', 'direction' => $sortColumn == 'user_id' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
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
                            href="{{ route('admin.product.listBidProduct', ['sort' => 'subcategory_id', 'direction' => $sortColumn == 'subcategory_id' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
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
                            href="{{ route('admin.product.listBidProduct', ['sort' => 'condition', 'direction' => $sortColumn == 'condition' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
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
                            href="{{ route('admin.product.listBidProduct', ['sort' => 'bid_starting_price', 'direction' => $sortColumn == 'bid_starting_price' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            starting price
                             @if ($sortColumn == 'bid_starting_price')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listBidProduct', ['sort' => 'countdown', 'direction' => $sortColumn == 'countdown' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            countdown
                             @if ($sortColumn == 'countdown')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listBidProduct', ['sort' => 'money_collection', 'direction' => $sortColumn == 'money_collection' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                           money collection
                             @if ($sortColumn == 'money_collection')
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
                    @foreach ($listBidProduct as $key => $bidProduct)
                        <tr>
                            <td>{{ $bidProduct->created_at->format('Y-m-d ') }} </td>
                            <td>{{ $bidProduct->id }}</td>
                            <td>{{ $bidProduct->name }} </td>
                            <td><a href="individualDetail/{{ $bidProduct->user_id }}" class="btn btn-dark btn-sm">
                                    {{ $bidProduct->user_id }}
                                </a></td>
                            <td>{{ $bidProduct->subcategorie->name }} </td>
                            <td>{{ $bidProduct->condition }}</td>
                            <td>{{ $bidProduct->bid_starting_price }}</td>
                            <td>    <div  data-countdown="{{ $bidProduct->countdown }}"></div></td>
                            <td>{{ $bidProduct->money_collection }}</td>
                            {{-- <td> <a href="/viewBidProduct/{{ $bidProduct->id }}" class="btn btn-success btn-sm">View Bids</a></td> --}}
                            <td>
                                @if ($bidProduct->type == 'sell')
                                    <a href="/bidProductDetail/{{ $bidProduct->id }}"
                                        class="btn btn-info btn-sm">View sell Details</a>
                                @elseif($bidProduct->type == 'bid')
                                    <a href="/bidProductDetail/{{ $bidProduct->id }}" class="btn btn-info btn-sm">View
                                        bid Details</a>
                                @else
                                    <a href="/swapProductDetail/{{ $bidProduct->id }}" class="btn btn-info btn-sm">View
                                        swap Details</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $listBidProduct->links() }}
                </div>
            </div>
        </div>
    </div>

    <!--/ Basic Bootstrap Table -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $('[data-countdown]').each(function() {
  var $this = $(this), finalDate = $(this).data('countdown');
  $this.countdown(finalDate, function(event) {
    $this.html(event.strftime('%D:%H:%M:%S'));

  });
});
$('#search').on('keyup',function(){
                    const z = $('#search').val();
                    console.log(z)
                    if(z.length<1){
                        location.href = 'bidProduct'
                    }
                 });

            // $.fn.dataTable.ext.errMode = 'throw';
            // var table = $('#table_id').DataTable({
            //     processing: true,
            //     serverSide: true,

            //     ajax: "bidProduct",
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
            //             data: 'subcategorie.name'
            //         },

            //         // {
            //         //     data: 'condition'
            //         // },
            //         {
            //             data: 'bid_starting_price'
            //         },
            //         {
            //             data:null,
            //             render: function(data, type, row, meta) {

            //                 return data.day+":"+data.hour+":"+data.minute
            //             },searchable: true, orderable: true
            //         },


            //         {
            //             data: null,
            //             render: function(data, row, type) {
            //                 return `
            //                 <a href="/productImages/${data.id}" class="btn btn-info btn-sm">Add Images</a>
            //                   `;
            //             },searchable: true, orderable: true
            //         },
            //         {
            //             data: null,
            //             render: function(data, row, type) {
            //                 return `
            //                 <a href="/bidProductDetail/${data.id}" class="btn btn-dark btn-sm">View Details</a>
            //                   `;
            //             }
            //         },


            //         {
            //             data: null,
            //             render: function(data, row, type) {
            //                 return `
            //                 <a href="/viewBidProduct/${data.id}" class="btn btn-success btn-sm">View Bids</a>
            //                   `;
            //             },searchable: true, orderable: true
            //         },

            //         {
            //             data: null,
            //             render: function(data, row, type) {
            //                 console.log(data)
            //                 return `
            //                 <button type="button" value="${data.id}" class="edit_BidProduct  btn btn-primary editbtn btn-sm "><i class="fa fa-edit"></i></button>

            //                   `;
            //             },searchable: true, orderable: true
            //             //   <button type="button" value="${data.id}" class="delete_BidProduct btn btn-danger deletebtn btn-sm "><i class="fa fa-trash"></i></button>
            //         }
            //     ]

            // });

            // $.ajaxSetup({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         }
            //     });

            // //Add Product
            // $(document).on('click', '.add_Bid_product', function(e) {
            //     e.preventDefault();
            //     let formData = new FormData($('#AddBidProductForm')[0]);

            //     $.ajax({
            //         type: "POST",
            //         url: "/addNewBidProduct",
            //         data: formData,
            //         contentType: false,
            //         processData: false,
            //         dataType: 'json',
            //         success: function(response) {
            //             console.log(response)


            //             if (response.status == 400) {
            //                 const store = $('#store').val();
            //                 store == '' ? $('#error_store').html(response.errors.store) : $('#error_store').html('')
            //                 const category = $('#category').val();
            //                 category == '' ? $('#error_category').html(response.errors.category) : $('#error_category').html('')
            //                 const subCategory = $('#subCategory').val();
            //                 subCategory == '' ? $('#error_subCategory').html(response.errors.subCategory) : $('#error_subCategory').html('')
            //                 const name = $('#name').val();
            //                 name == '' ? $('#error_name').html(response.errors.name) : $('#error_name').html('')
            //                 const condition = $('#condition').val();
            //                 condition == '' ? $('#error_condition').html(response.errors.condition) : $('#error_condition').html('')
            //                 const day = $('#day').val();
            //                 day == '' ? $('#error_day').html(response.errors.day) : $('#error_day').html('')
            //                 const hour = $('#hour').val();
            //                 hour == '' ? $('#error_hour').html(response.errors.hour) : $('#error_hour').html('')
            //                 const minute = $('#minute').val();
            //                 minute == '' ? $('#error_minute').html(response.errors.minute) : $('#error_minute').html('')
            //                 const bid_starting_price = $('#bid_starting_price').val();
            //                 bid_starting_price == '' ? $('#error_bid_starting_price').html(response.errors.bid_starting_price) : $('#error_bid_starting_price').html('')
            //                 const description = $('#description').val();
            //                 description == '' ? $('#error_description').html(response.errors.description) : $('#error_description').html('')


            //             } else {
            //                 $('#success_message').text(response.message)
            //                 $('#success_message').addClass('alert alert-success')
            //                 $('#success_message').text(response.message)
            //                 $('#addBidProductModal').modal('hide')
            //                 $('#addBidProductModal').find('input').val('')
            //                 $('#addBidProductModal').find('textarea').val('')
            //                 $('#addBidProductModal').find('select').val('')

            //                  table.ajax.reload();
            //             }
            //         }
            //     })
            // })
            //  //Show Product
            // //  $(document).on('click', '.show_product', function(e) {
            // //     e.preventDefault();
            // //     var product_id = $(this).val();
            // //     console.log(product_id)
            // //     $('#showProductModal').modal('show')
            // //     $.ajax({
            // //         type: 'GET',
            // //         url: 'editProduct/' + product_id,
            // //         success: function(response) {
            // //             console.log(response);
            // //             if (response.status == 404) {
            // //                 $('#success_message').html("")
            // //                 $('#success_message').addClass('alert alert-danger')
            // //                 $('#success_message').text(response.message)
            // //             } else {
            // //                 $('#edit_id').val(response.product.id)
            // //                 $('#showIamge').attr("src", "admin/assets/img/description/"+response.product.description )

            // //             }
            // //         }

            // //     })
            // // })


            //  //Edit Product
            //  $(document).on('click', '.edit_BidProduct', function(e) {
            //     e.preventDefault();
            //     var bidProduct_id = $(this).val();
            //     // console.log(bidProduct_id)
            //     $('#editBidProductModal').modal('show')
            //     $.ajax({
            //         type: 'GET',
            //         url: 'editBidProduct/' + bidProduct_id,
            //         success: function(response) {
            //             console.log(response);
            //             if (response.status == 404) {
            //                 $('#success_message').html("")
            //                 $('#success_message').addClass('alert alert-danger')
            //                 $('#success_message').text(response.message)
            //             } else {
            //                 $('#edit_bid_id').val(response.bidProduct.id)
            //                 $('#edit_bid_individual_id').val(response.bidProduct.user_id)
            //                 // $('#edit_store').val(response.bidProduct.user_id)
            //                 if (response.users.type == '2') {
            //                     $('#edit_bid_store').removeAttr('disabled')
            //                     $('#edit_bid_store').val(response.bidProduct.user_id)
            //                     console.log(response.bidProduct.user_id);
            //                 } else {
            //                     $('#edit_bid_store').attr('disabled', 'disabled')
            //                     $('#edit_bid_store').val('null')
            //                     $('#edit_bid_individual_id').val(response.bidProduct.user_id)
            //                     console.log(response.bidProduct.user_id);
            //                 }
            //                 $('#edit_bid_category').val(response.bidProduct.category_id)
            //                 $('#edit_bid_subcategory').val(response.bidProduct.subcategory_id)
            //                 $('#edit_bid_name').val(response.bidProduct.name)
            //                 $('#edit_bid_condition').val(response.bidProduct.condition)
            //                 $('#edit_day').val(response.bidProduct.day)
            //                 $('#edit_hour').val(response.bidProduct.hour)
            //                 $('#edit_minute').val(response.bidProduct.minute)
            //                 $('#edit_bid_starting_price').val(response.bidProduct.bid_starting_price)
            //                 $('#edit_bid_description').val(response.bidProduct.description)
            //             }
            //         }

            //     })
            // })


            //  //Update Product
            //  $(document).on('click', '.update_Bid_product', function(e) {
            //     e.preventDefault();
            //     $(this).text('Updating')
            //     var bidProduct_id = $('#edit_bid_id').val();
            //     let formData = new FormData($('#UpdateBidProductForm')[0]);


            //     $.ajaxSetup({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         }
            //     });

            //     $.ajax({
            //         type: "POST",
            //         url: "/updateBidProduct/"+bidProduct_id,
            //         data: formData,
            //         cache: false,
            //         contentType: false,
            //         processData: false,
            //         success: function(response) {
            //             console.log(response)

            //             if (response.status == 400) {
            //                 const store = $('#edit_bid_store').val();
            //                 store == '' ? $('#error_edit_bid_store').html(response.errors.store) : $('#error_edit_bid_store').html('')
            //                 const category = $('#edit_bid_category').val();
            //                 category == '' ? $('#error_edit_bid_category').html(response.errors.category) : $('#error_edit_bid_category').html('')
            //                 const subcategory = $('#edit_bid_subcategory').val();
            //                 subcategory == '' ? $('#error_edit_bid_subcategory').html(response.errors.subCategory) : $('#error_edit_bid_subcategory').html('')
            //                 const name = $('#edit_bid_name').val();
            //                 name == '' ? $('#error_edit_bid_name').html(response.errors.name) : $('#error_edit_bid_name').html('')
            //                 const product_type = $('#edit_product_bid_type').val();
            //                 product_type == '' ? $('#error_edit_bid_product_type').html(response.errors.product_type) : $('#error_edit_bid_product_type').html('')
            //                 const condition = $('#edit_bid_condition').val();
            //                 condition == '' ? $('#error_edit_bid_condition').html(response.errors.condition) : $('#error_edit_bid_condition').html('')
            //                 const bid_starting_price = $('#edit_bid_starting_price').val();
            //                 bid_starting_price == '' ? $('#error_edit_bid_starting_price').html(response.errors.bid_starting_price) : $('#error_edit_bid_starting_price').html('')
            //                 const description = $('#edit_bid_description').val();
            //                 description == '' ? $('#error_edit_bid_description').html(response.errors.description) : $('#error_edit_bid_description').html('')
            //                 const day = $('#edit_day').val();
            //                 day == '' ? $('#error_edit_day').html(response.errors.day) : $('#error_edit_day').html('')
            //                 const hour = $('#edit_hour').val();
            //                 hour == '' ? $('#error_edit_hour').html(response.errors.hour) : $('#error_edit_hour').html('')
            //                 const minute = $('#edit_minute').val();
            //                 minute == '' ? $('#error_edit_minute').html(response.errors.minute) : $('#error_edit_minute').html('')
            //             }

            //             else if (response.status == 404) {
            //                 $('#update_error_message').html('');
            //                 $('#update_error_message').addClass('alert alert-danger');
            //                 $('#update_error_message').text('response.message');
            //                 $('.update_product').text('Update')
            //             }

            //             else {
            //                 $('#success_message').text(response.message)
            //                 $('#success_message').addClass('alert alert-success')
            //                 $('#success_message').text(response.message)
            //                 $('#editBidProductModal').modal('hide')
            //                 $('#editBidProductModal').find('input').val('')
            //                 $('#error_edit_bid_store').html('')
            //                 $('#error_edit_bid_category').html('')
            //                 $('#error_edit_bid_subcategory').html('')
            //                 $('#error_edit_bid_name').html('')
            //                 $('#error_edit_bid_condition').html('')
            //                 $('#error_edit_bid_starting_price').html('')
            //                 $('#error_edit_bid_description').html('')
            //                 $('#error_edit_day').html('')
            //                 $('#error_edit_hour').html('')
            //                 $('#error_edit_minute').html('')
            //                 $('.update_product').text('Update')
            //                 // fetchProduct()

            //                  table.ajax.reload();
            //             }
            //         }
            //     })
            // })


            // //delete Modal
            // $(document).on('click', '.delete_BidProduct', function(e) {
            //     e.preventDefault();

            //     var bidProduct_id = $(this).val();
            //     // var store = $(this).val();
            //     console.log(bidProduct_id)
            //     $('#delete_id').val(bidProduct_id)
            //     $('#title_product_delete').text('Are you sure?')
            //     $('#deleteBidProductModal').modal('show')
            // })

            // $(document).on('click' , '.delete_BidProduct_btn' , function(e) {
            //     e.preventDefault();

            //     var product_id = $('#delete_id').val();

            //     $.ajax({
            //         type: 'GET',
            //         url: 'deleteBidProduct/'+product_id,
            //         success: function (response) {
            //             // console.log(response);
            //             $('#success_message').addClass('alert alert-success')
            //             $('#success_message').text(response.message)
            //             $('#deleteBidProductModal').modal('hide')

            //              table.ajax.reload();
            //         }
            //     })
            // })
            //               //search
            // // $("#myInput").on("keyup", function() {
            // //     var value = $(this).val().toLowerCase();
            // //     $("#myTable tr").filter(function() {
            // //         $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            // //     });
            // // });

            // // $('#category').on('change', function(){
            // //    let category_id = $(this).val();

            // //    $.ajax({
            // //     type: 'POST',
            // //     url: '/getSubCategorys',
            // //     data:'category_id='+category_id+'&_token={{ csrf_token() }}',
            // //     success: function (response){
            // //         $('#subcategory').html(response)
            // //     }
            // //    })
            // // })

            // // $('#edit_category').on('change', function(){
            // //    let category_id = $(this).val();

            // //    $.ajax({
            // //     type: 'POST',
            // //     url: '/getSubCategorys',
            // //     data:'category_id='+category_id+'&_token={{ csrf_token() }}',
            // //     success: function (response){
            // //         $('#edit_subcategory').html(response)
            // //     }
            // //    })
            // // })

        })
    </script>
@endsection
