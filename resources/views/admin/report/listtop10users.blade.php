@extends('admin.layouts.app')


@section('content')




<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
            /</a></span> User Report
</h4>

<!-- Basic Bootstrap Table -->

@if (Session::has('success'))
<div class="alert alert-success">
    <ul>
        <li>{{ Session::get('success') }}</li>
    </ul>
</div>
@endif

<div class="card">
    <div class="d-flex justify-content-between  items-center">
        <div class="col-lg-2 col-md-6 mb-0 mt-4  mx-3">
            <form action="" method="get" id="searchForm">
                <div class="d-flex gap-3">
                    <div class=" col-lg-2 input-group input-group-merge">
                        <span class="input-group-text" id="addon-search"><i class="bx bx-search"></i></span>
                        <input type="text" id="search" name="search" class="form-control" placeholder="Search ..." value="{{ request()->get('search') }}" autofocus>

                    </div>

                    <div class="col-lg-2 input-group input-group-merge">
                        <select name="user_type" class="form-select user_type" value="{{ request()->get('user_type') }}">
                            <option value=""> Select type</option>
                            <option value="1" {{ request()->get('user_type') == '1' ? 'selected' : '' }}>Store
                            </option>
                            <option value="2" {{ request()->get('user_type') == '2' ? 'selected' : '' }}>User
                            </option>
                        </select>
                    </div>

                    <div class="col-lg-2 input-group input-group-merge">
                        <select name="criteria" class="form-select criteria" value="{{ request()->get('criteria') }}">
                            <option value="">Select Criteria</option>
                            {{-- @if(request()->get('user_type') != '1') --}}
                            <option value="most_ordered" {{    request()->get('criteria') == 'most_ordered' ? 'selected' : '' }}>Most User Ordered
                            </option>
                            <option value="highest_order_sum" {{ request()->get('criteria') == 'highest_order_sum' ? 'selected' : '' }}>Highest User
                                Ordered Sum </option>
                            {{-- @endif --}}

                            {{-- @if(request()->get('user_type') != '2') --}}
                            <option value="most_uploading_product" {{ request()->get('criteria') == 'most_uploading_product' ? 'selected' : '' }}>Most
                                Store
                                Uploading Product</option>
                                <option value="most_getting_orders" {{ request()->get('criteria') == 'most_getting_orders' ? 'selected' : '' }}>Most
                                    Store
                                    Getting Orders</option>

                            {{-- @endif --}}
                        </select>
                    </div>

                </div>
                <div class="d-flex gap-3 my-4 " style="margin-left:200%">

                    <div class="">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                    <div class="">
                        <a href="{{ route('admin.report.listtop10users') }}" class="btn btn-danger">Cancel</a>
                    </div>

                    <div class="">
                        {{-- <a href="{{ route('usersexport', $_GET) }}" class="btn btn-success">Excel</a> --}}
                    </div>

                </div>
            </form>
        </div>
        <div class="m-3 d-flex gap-2 my-4">
            <ul class="pagination    ">
                <li class="">
                    <div class="btn-group">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">{{ currentLimit() }}</button>
                        <ul class="dropdown-menu" style="min-width: auto;">
                            @foreach (limits() as $limit)
                            <li><a class="dropdown-item {{ $limit['active'] ? 'active' : '' }}" href="{{ $limit['url'] }}">{{ $limit['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="table-responsive text-nowrap">
        <nav class="nav-pagination" aria-label="Page navigation">
            <div class="row ">
                <div class="label col-lg-10 col-md-6 mx-3">
                    {{-- <span>Showing {{ $listtop10users->firstItem() }} to {{ $listtop10users->lastItem() }}
                    of total {{ $listtop10users->total() }} entries</span> --}}
                </div>
            </div>
        </nav>

        <table class="table" id="table_id" style="width: 100%">
            <thead>
                <tr>
                    <th><a class="text-dark" href="{{ route('admin.report.listtop10users', [
                                    'sort' => 'created_at',
                                    'direction' => $sortColumn == 'created_at' && $sortDirection == 'asc' ? 'desc' : 'asc',
                                    'search' => request()->get('search'),
                                    'user_type' => request()->get('user_type'),
                                    'status' => request()->get('status'),
                                    'user_active' => request()->get('user_active'),
                                ]) }}">Created
                            At @if ($sortColumn == 'created_at')
                            @if ($sortDirection == 'asc')
                            <i class="fas fa-arrow-up"></i>
                            @else
                            <i class="fas fa-arrow-down"></i>
                            @endif
                            @endif
                        </a></th>

                    <th>F_Name</th>
                    <th>L_Name</th>
                    <th><a class="text-dark" href="{{ route('admin.report.listtop10users', [
                                    'sort' => 'email',
                                    'direction' => $sortColumn == 'email' && $sortDirection == 'asc' ? 'desc' : 'asc',
                                    'search' => request()->get('search'),
                                    'user_type' => request()->get('user_type'),
                                    'status' => request()->get('status'),
                                    'user_active' => request()->get('user_active'),
                                ]) }}">Email
                            @if ($sortColumn == 'email')
                            @if ($sortDirection == 'asc')
                            <i class="fas fa-arrow-up"></i>
                            @else
                            <i class="fas fa-arrow-down"></i>
                            @endif
                            @endif
                        </a>
                    </th>
                    <th><a class="text-dark" href="{{ route('admin.report.listtop10users', [
                                    'sort' => 'phone',
                                    'direction' => $sortColumn == 'phone' && $sortDirection == 'asc' ? 'desc' : 'asc',
                                    'search' => request()->get('search'),
                                    'user_type' => request()->get('user_type'),
                                    'status' => request()->get('status'),
                                    'user_active' => request()->get('user_active'),
                                ]) }}">Mobile
                            @if ($sortColumn == 'phone')
                            @if ($sortDirection == 'asc')
                            <i class="fas fa-arrow-up"></i>
                            @else
                            <i class="fas fa-arrow-down"></i>
                            @endif
                            @endif
                        </a>
                    </th>
                    <th>type</th>
                    <th>Is Active</th>
                    @if (request()->get('criteria') == 'most_ordered')
                    <th>Order count</th>
                    @endif

                    @if (request()->get('criteria') == 'highest_order_sum')
                    <th>orders sum amount</th>
                    @endif

                    @if (request()->get('criteria') == 'most_uploading_product')
                    <th>Product Count</th>
                    @endif



                    <th>status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($listtop10users as $key => $user)
                <tr>
                    {{-- <td>{{ $user->store_name }} </td> --}}
                    <td>{{ $user->created_at }}</td>
                    <td>{{ $user->first_name }}</td>
                    <td>{{ $user->last_name }} </td>
                    <td>{{ $user->email }} </td>
                    <td>{{ $user->phone }} </td>

                    @if ($user->type == 1)
                    <td><span class="btn btn-sm btn-warning">Store</span></td>
                    @else
                    <td><span class="btn btn-sm btn-info">User</span></td>
                    @endif
                    @if ($user->status == 'active')
                    <td><span class="btn btn-sm btn-dark">Active</span></td>
                    @elseif($user->status == 'inactive')
                    <td><span class="btn btn-sm btn-dark">Inactive</span></td>
                    @endif

                    @if (request()->get('criteria') == 'most_ordered')
                    <td>{{ $user->orders_count }}</td>
                    @endif

                    @if (request()->get('criteria') == 'highest_order_sum')
                    <td>{{ $user->orders_sum_grand_total }}</td>
                    @endif


                    @if (request()->get('criteria') == 'most_uploading_product')
                    <td>{{ $user->product_count }}</td>
                    @endif



                    <td>{{ ucfirst($user->status) }} </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{-- <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $listtop10users->links() }}
    </div>

</div> --}}
</div>
</div>
@endsection


@section('scripts')
<script>
    $(document).ready(function() {

        $('.user_type').on('change', function() {
            var userType = $(this).val();
            console.log(userType);
            if (userType == 2) {
                $('select[name="criteria"] option[value="most_uploading_product"]').hide();
                $('select[name="criteria"] option[value="most_ordered"]').show();
                $('select[name="criteria"] option[value="highest_order_sum"]').show();
            } else if (userType == 1) {
                $('select[name="criteria"] option[value="most_ordered"]').hide();
                $('select[name="criteria"] option[value="highest_order_sum"]').hide();
                $('select[name="criteria"] option[value="most_uploading_product"]').show();
            } else {

            }

        });
    });

</script>
@endsection
