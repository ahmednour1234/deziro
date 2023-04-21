@extends('admin.layouts.app')



@section('content')
    {{-- Active Modal --}}
    @include('admin.moreDetails.activate_modal.activeModal')
    {{-- Inactive Modal --}}
    @include('admin.moreDetails.activate_modal.inactiveModal')

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span>Store Products</h4>

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


                        <div class="col-lg-3 input-group input-group-merge">
                            <select name="status" class="form-select" value="{{ request()->get('status') }}">
                                <option value="">Select All</option>
                                <option value="pending" {{ request()->get('status') == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="active" {{ request()->get('status') == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ request()->get('status') == 'inactive' ? 'selected' : '' }}>
                                    Inactive</option>
                                <option value="sold" {{ request()->get('status') == 'sold' ? 'selected' : '' }}>
                                    Sold</option>
                            </select>
                        </div>
                        <div class="">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>

                </form>
            </div>
            <div class="m-3 d-flex gap-2">
                {{-- <a href="/createStore" class=""> --}}
                <div>

                </div>
                {{-- </a> --}}
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
                <div class="row mt-3 ">
                    <div class="label col-lg-10 col-md-6 mx-3">
                        <span>Showing {{ $listStoreProduct->firstItem() }} to {{ $listStoreProduct->lastItem() }}
                            of total {{ $listStoreProduct->total() }} entries</span>
                    </div>
                </div>
            </nav>
            <table class="table" id="table_id" style="width: 100%">
                <thead class="text-center">
                    <tr>
                        <th><a class="text-dark"
                                href="{{ route('admin.storeProduct.listStoreProduct', [
                                    'sort' => 'created_at',
                                    'direction' => $sortColumn == 'created_at' && $sortDirection == 'asc' ? 'desc' : 'asc',
                                    'status' => request()->get('status'),
                                ]) }}">Created
                                At @if ($sortColumn == 'created_at')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th><a class="text-dark"
                                href="{{ route('admin.storeProduct.listStoreProduct', ['sort' => 'id', 'direction' => $sortColumn == 'id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'status' => request()->get('status')]) }}">
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
                                href="{{ route('admin.storeProduct.listStoreProduct', ['sort' => 'name', 'direction' => $sortColumn == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc', 'status' => request()->get('status')]) }}">
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
                                href="{{ route('admin.storeProduct.listStoreProduct', ['sort' => 'user_id', 'direction' => $sortColumn == 'user_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'status' => request()->get('status')]) }}">
                                Store id
                                @if ($sortColumn == 'user_id')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th><a class="text-dark"
                                href="{{ route('admin.storeProduct.listStoreProduct', ['sort' => 'category_id', 'direction' => $sortColumn == 'category_id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'status' => request()->get('status')]) }}">
                                cat
                                @if ($sortColumn == 'category_id')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th>Brand</th>
                        <th><a class="text-dark"
                                href="{{ route('admin.storeProduct.listStoreProduct', ['sort' => 'quantity', 'direction' => $sortColumn == 'quantity' && $sortDirection == 'asc' ? 'desc' : 'asc', 'status' => request()->get('status')]) }}">
                                quantity
                                @if ($sortColumn == 'quantity')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th><a class="text-dark"
                                href="{{ route('admin.storeProduct.listStoreProduct', ['sort' => 'price', 'direction' => $sortColumn == 'price' && $sortDirection == 'asc' ? 'desc' : 'asc', 'status' => request()->get('status')]) }}">
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
                                href="{{ route('admin.storeProduct.listStoreProduct', ['sort' => 'special_price', 'direction' => $sortColumn == 'special_price' && $sortDirection == 'asc' ? 'desc' : 'asc', 'status' => request()->get('status')]) }}">
                                special price
                                @if ($sortColumn == 'special_price')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th>View Product</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($listStoreProduct as $key => $sellingProduct)
                        <tr>
                            <td>{{ $sellingProduct->created_at->format('Y-m-d ') }} </td>
                            <td>{{ $sellingProduct->id }}</td>
                            <td>{{ $sellingProduct->name }} </td>
                            <td><a href="/userDetail/{{ $sellingProduct->user_id }}" class="btn btn-info btn-sm">
                                    {{ $sellingProduct->user_id }}
                                </a></td>
                            <td>{{ $sellingProduct->category->name }} </td>
                            <td>{{ $sellingProduct->brand->name }} </td>
                            <td>{{ $sellingProduct->quantity }}</td>
                            <td>{{ $sellingProduct->price }}</td>
                            <td>{{ $sellingProduct->special_price }}</td>
                            <td> <a href="/productDetail/{{ $sellingProduct->id }}" class="btn btn-info btn-sm">View More
                                    Details</a></td>
                            <td>
                                @if ($sellingProduct->status == 'active')
                                    <button class="btn btn-sm btn-success active_product" value="{{ $sellingProduct->id }}"
                                        data-value1={{ $sellingProduct->name }}>{{ $sellingProduct->status }}</button>
                                @elseif($sellingProduct->status == 'inactive')
                                    <button class="btn btn-sm btn-danger inactive_product"
                                        value="{{ $sellingProduct->id }}"
                                        data-value1={{ $sellingProduct->name }}>{{ $sellingProduct->status }}</button>
                                @else
                                    <button class="btn btn-sm btn-dark">{{ $sellingProduct->status }}</button>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $listStoreProduct->links() }}
                </div>
            </div>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.active_product', function(e) {
                e.preventDefault();
                var name = $(this).data('value1');
                var product_id = $(this).val();
                $('#active_id').val(product_id)
                $('#inactive_title').text('Inactivate  ' + name)
                $('#inactive_msg').text('Are you sure do you want to inactivate  ' + name)
                $('#activeModal').modal('show')
            })

            $(document).on('click', '.is_active', function(e) {
                e.preventDefault();

                var product_id = $('#active_id').val();
                console.log(product_id)
                $.ajax({
                    type: 'POST',
                    url: 'inactiveProduct/' + product_id,
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

            $(document).on('click', '.inactive_product', function(e) {
                e.preventDefault();
                var name = $(this).data('value1');
                var user_id = $(this).val();
                $('#inactive_id').val(user_id)
                $('#active_title').text('Activate  ' + name)
                $('#active_msg').text('Are you sure do you want to activate  ' + name)
                $('#inactiveModal').modal('show')
            })

            $(document).on('click', '.is_inactive', function(e) {
                e.preventDefault();

                var product_id = $('#inactive_id').val();
                console.log(product_id)
                $.ajax({
                    type: 'POST',
                    url: 'activeProduct/' + product_id,
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
