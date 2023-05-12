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
                            <input type="text" id="search" name="search" class="form-control"
                                placeholder="Search ..." value="{{ request()->get('search') }}" autofocus>

                        </div>

                        <div class="col-lg-2 input-group input-group-merge">
                            <select name="user_type" class="form-select" value="{{ request()->get('user_type') }}">
                                <option value=""> Select type</option>
                                <option value="1" {{ request()->get('user_type') == '1' ? 'selected' : '' }}>Individual
                                </option>
                                <option value="2" {{ request()->get('user_type') == '2' ? 'selected' : '' }}>Store
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-2 input-group input-group-merge">
                            <select name="status" class="form-select" value="{{ request()->get('status') }}">
                                <option value="">Select Status</option>
                                <option value="pending" {{ request()->get('status') == 'pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="accept" {{ request()->get('status') == 'accept' ? 'selected' : '' }}>Accept
                                </option>
                                <option value="rejected" {{ request()->get('status') == 'rejected' ? 'selected' : '' }}>
                                    Rejected</option>

                            </select>
                        </div>

                        <div class="col-lg-2 input-group input-group-merge">
                            <select name="user_active" class="form-select" value="{{ request()->get('user_active') }}">
                                <option value=""> Select Activate</option>
                                <option value="1" {{ request()->get('user_active') == '1' ? 'selected' : '' }}>Active
                                </option>
                                <option value="0" {{ request()->get('user_active') == '0' ? 'selected' : '' }}>
                                    Inactive</option>
                            </select>
                        </div>

                        {{-- <div class="col-lg-2 input-group input-group-merge">
                            <select name="user_ban" class="form-select" value="{{ request()->get('user_ban') }}">
                                <option value=""> Select banned</option>
                                <option value="1" {{ request()->get('user_ban') == '1' ? 'selected' : '' }}>Banned
                                </option>
                                <option value="0" {{ request()->get('user_ban') == '0' ? 'selected' : '' }}>
                                    Not Banned</option>
                            </select>
                        </div> --}}


                    </div>
                    <div class="d-flex gap-3 my-4 " style="margin-left:200%">

                        <div class="">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                        <div class="">
                            <a href="{{ route('admin.report.listuserreport') }}" class="btn btn-danger">Cancel</a>
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
                <div class="row ">
                    <div class="label col-lg-10 col-md-6 mx-3">
                        <span>Showing {{ $listUser->firstItem() }} to {{ $listUser->lastItem() }}
                            of total {{ $listUser->total() }} entries</span>
                    </div>
                </div>
            </nav>

            <table class="table" id="table_id" style="width: 100%">
                <thead>
                    <tr>

                        <th><a class="text-dark"
                                href="{{ route('admin.report.listuserreport', [
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


                        {{-- <th><a class="text-dark"
                                href="{{ route('admin.report.listuserreport', ['sort' => 'store_name', 'direction' => $sortColumn == 'store_name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">Store
                                Name @if ($sortColumn == 'store_name')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a>
                        </th> --}}

                        <th>F_Name</th>
                        <th>L_Name</th>
                        <th><a class="text-dark"
                                href="{{ route('admin.report.listuserreport', [
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
                        <th><a class="text-dark"
                                href="{{ route('admin.report.listuserreport', [
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
                        <th>status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listUser as $key => $user)
                        <tr>
                            {{-- <td>{{ $user->store_name }} </td> --}}
                            <td>{{ $user->created_at }}</td>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }} </td>
                            <td>{{ $user->email }} </td>
                            <td>{{ $user->phone }} </td>

                            @if ($user->type == 1)
                                <td><span class="btn btn-sm btn-warning">Individual</span></td>
                            @else
                                <td><span class="btn btn-sm btn-info">Store</span></td>
                            @endif
                            @if ($user->is_active == 1)
                            <td><span class="btn btn-sm btn-dark">Active</span></td>
                        @elseif($user->is_active == 0)
                            <td><span class="btn btn-sm btn-dark">Inactive</span></td>
                        @endif

                            <td>{{ ucfirst($user->status) }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $listUser->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection
