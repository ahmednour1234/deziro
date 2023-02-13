@extends('admin.layouts.app')

{{-- @yield('title', 'Product') --}}

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Individual request Products</h4>

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
                        <span>Showing {{ $listRequestProduct->firstItem() }} to {{ $listRequestProduct->lastItem() }}
                            of total {{ $listRequestProduct->total() }} entries</span>
                    </div>
                </div>
            </nav>

            <table class="table" id="table_id" style="width: 100%">
                <thead class="text-center">
                    <tr>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listRequestProduct', ['sort' => 'created_at', 'direction' => $sortColumn == 'created_at' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
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
                            href="{{ route('admin.product.listRequestProduct', ['sort' => 'id', 'direction' => $sortColumn == 'id' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
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
                            href="{{ route('admin.product.listRequestProduct', ['sort' => 'name', 'direction' => $sortColumn == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
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
                            href="{{ route('admin.product.listRequestProduct', ['sort' => 'user_id', 'direction' => $sortColumn == 'user_id' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
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
                            href="{{ route('admin.product.listRequestProduct', ['sort' => 'type', 'direction' => $sortColumn == 'type' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                         product type
                             @if ($sortColumn == 'type')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listRequestProduct', ['sort' => 'subcategory_id', 'direction' => $sortColumn == 'subcategory_id' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
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
                            href="{{ route('admin.product.listRequestProduct', ['sort' => 'condition', 'direction' => $sortColumn == 'condition' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            Condition
                             @if ($sortColumn == 'condition')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        {{-- <th>Price</th> --}}
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listRequestProduct', ['sort' => 'money_collection', 'direction' => $sortColumn == 'money_collection' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            money collection
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
                    @foreach ($listRequestProduct as $key => $requestProduct)
                        <tr>
                            <td>{{ $requestProduct->created_at->format('Y-m-d ') }} </td>
                            <td>{{ $requestProduct->id }}</td>
                            <td>{{ $requestProduct->name }} </td>
                            <td><a href="individualDetail/{{ $requestProduct->user_id }}" class="btn btn-dark btn-sm">
                                    {{ $requestProduct->user_id }}
                                </a></td>
                            <td>{{ $requestProduct->type }} </td>
                            <td>{{ $requestProduct->subcategorie->name }} </td>
                            <td>{{ $requestProduct->condition }}</td>
                            {{-- <td>{{ $requestProduct->price }}</td> --}}
                            <td>{{ $requestProduct->money_collection }}</td>
                            <td>
                                @if ($requestProduct->type == 'sell')
                                    <a href="/sellingProductDetail/{{ $requestProduct->id }}"
                                        class="btn btn-info btn-sm">View sell Details</a>
                                @elseif($requestProduct->type == 'bid')
                                    <a href="/bidProductDetail/{{ $requestProduct->id }}" class="btn btn-info btn-sm">View
                                        bid Details</a>
                                @else
                                    <a href="/swapProductDetail/{{ $requestProduct->id }}" class="btn btn-info btn-sm">View
                                        swap Details</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $listRequestProduct->links() }}
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
                        location.href = 'product'
                    }
                 });


            // $.ajaxSetup({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     }
            // });

            //     $(document).on('click' , '.approve_product' , function(e) {
            //         e.preventDefault();
            //         var product_id = $(this).val();
            //         // console.log(product_id)
            //         $('#approve_id').val(product_id)
            //         $('#approveProductModal').modal('show')
            //     });

            //     $(document).on('click' , '.reject_product' , function(e) {
            //         e.preventDefault();
            //         var product_id = $(this).val();
            //         // console.log(product_id)
            //         $('#reject_id').val(product_id)
            //         $('#rejectProductModal').modal('show')
            //     });

            //     $(document).on('click' , '.reject_btn' , function(e){
            //         e.preventDefault();

            //         var product_id = $('#reject_id').val();
            //         var data = {
            //             'reason': $('.reason').val(),

            //         }

            //         $.ajax({
            //             type: 'POST',
            //             url: 'reject_product/' + product_id,
            //             data: data,
            //             dataType: 'json',
            //             success: function(response) {
            //                 console.log(response)

            //                 if (response.status == 400) {
            //                     const reason = $('#reason').val();
            //                     reason == '' ? $('#error_reason').html(response.errors.reason) : $('#error_reason').html('')
            //                 } else if (response.status == 404) {
            //                     $('#update_error_message').html('');
            //                     $('#update_error_message').addClass('alert alert-danger');
            //                     $('#update_error_message').text('response.message');
            //                     $('.update_user').text('Update')
            //                 } else {
            //                     $('#success_message').text(response.message)
            //                     $('#success_message').addClass('alert alert-success')
            //                     $('#success_message').text(response.message)
            //                     $('#rejectProductModal').modal('hide')
            //                     $('#editStoreModal').find('input').val('')
            //                     $('.update_user').text('Update')
            //                     // fetchUser()
            //                       table.ajax.reload();

            //                 }
            //             }
            //         })

            // })

            // $(document).on('click' , '.approve_btn' , function(e){
            //         e.preventDefault();

            //         var product_id = $('#approve_id').val();
            //         let formData = new FormData($('#approveProductForm')[0]);
            //         console.log(product_id)

            //         $.ajax({
            //             type: 'POST',
            //             url: 'approve_product/' + product_id,
            //             data: formData,
            //             cache: false,
            //             contentType: false,
            //             processData: false,
            //             success: function(response) {
            //                 console.log(response)

            //                 if (response.status == 404) {
            //                     $('#update_error_message').html('');
            //                     $('#update_error_message').addClass('alert alert-danger');
            //                     $('#update_error_message').text('response.message');
            //                     $('.update_user').text('Update')
            //                 } else {
            //                     $('#success_message').text(response.message)
            //                     $('#success_message').addClass('alert alert-success')
            //                     $('#success_message').text(response.message)
            //                     $('#approveProductModal').modal('hide')
            //                     $('#approveProductModal').find('input').val('')
            //                     $('.update_user').text('Update')
            //                     // fetchUser()
            //                       table.ajax.reload();

            //                 }
            //             }
            //         })

            // })
        });
    </script>
@endsection
