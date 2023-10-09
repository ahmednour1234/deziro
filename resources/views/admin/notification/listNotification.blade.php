@extends('admin.layouts.app')

@section('title', 'Notifications')

@section('content')
    {{-- @include('admin.notification.addNotificationModal') --}}
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Notifications</h4>

    <div id="success_message"></div>
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
                        <input type="hidden" name="limit" value="{{ request()->get('limit') }}">
                    </div>

                </form>
            </div>
            <div class="m-3 d-flex gap-2 my-4">

                <div>
                    <a href="{{ route('admin.notification.addNotification') }}">

                        <button type="button" class="btn btn-primary" id="addModalBtn" data-bs-toggle="modal"
                            data-bs-target="#addBannerModal">
                            <span class="flex-center">Add <i class="bx bx-plus"></i></span>
                        </button>
                    </a>
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
        <nav class="nav-pagination" aria-label="Page navigation">
            <div class="row ">
                <div class="label col-lg-10 col-md-6 mx-3">
                    <span>Showing {{ $listnotification->firstItem() }} to {{ $listnotification->lastItem() }}
                        of total {{ $listnotification->total() }} entries</span>
                </div>
            </div>
        </nav>
        <div class="d-flex justify-content-between  items-center">


            <div class="modal-body">

                <input type="hidden" id="id" name="id">

                <div class="row g-2">


                    <div class="table-responsive text-nowrap">
                        <table class="table" id="table_id" style="width: 100%">
                            <thead>
                                <tr class="text-center">
                                    {{-- <th>#</th> --}}
                                    <th><a class="text-dark"
                                        href="{{ route('admin.notification.listNotification', ['sort' => 'created_at', 'direction' => $sortColumn == 'created_at' && $sortDirection == 'asc' ? 'desc' : 'asc', 'limit' => request()->get('limit'), 'search' => request()->get('search')]) }}">
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
                                        href="{{ route('admin.notification.listNotification', ['sort' => 'id', 'direction' => $sortColumn == 'id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'limit' => request()->get('limit'), 'search' => request()->get('search')]) }}">
                                       Id
                                        @if ($sortColumn == 'id')
                                            @if ($sortDirection == 'asc')
                                                <i class="fas fa-arrow-up"></i>
                                            @else
                                                <i class="fas fa-arrow-down"></i>
                                            @endif
                                        @endif
                                    </a></th>

                                    <th>Type</th>
                                    <th>User Id</th>


                                    <th><a class="text-dark"
                                        href="{{ route('admin.notification.listNotification', ['sort' => 'title', 'direction' => $sortColumn == 'title' && $sortDirection == 'asc' ? 'desc' : 'asc', 'limit' => request()->get('limit'), 'search' => request()->get('search')]) }}">
                                       Title
                                        @if ($sortColumn == 'title')
                                            @if ($sortDirection == 'asc')
                                                <i class="fas fa-arrow-up"></i>
                                            @else
                                                <i class="fas fa-arrow-down"></i>
                                            @endif
                                        @endif
                                    </a></th>
                                    <th><a class="text-dark"
                                        href="{{ route('admin.notification.listNotification', ['sort' => '=description', 'direction' => $sortColumn == '=description' && $sortDirection == 'asc' ? 'desc' : 'asc', 'limit' => request()->get('limit'), 'search' => request()->get('search')]) }}">
                                        Description
                                        @if ($sortColumn == '=description')
                                            @if ($sortDirection == 'asc')
                                                <i class="fas fa-arrow-up"></i>
                                            @else
                                                <i class="fas fa-arrow-down"></i>
                                            @endif
                                        @endif
                                    </a></th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0 text-center" id="myTable">
                                @foreach ($listnotification as $key => $notification)
                                    <tr class="text-center">
                                        {{-- <td>{{ $key + 1 }}</td> --}}
                                        <td>{{ $notification->created_at->format('Y-m-d ') }}</td>
                                        <td>{{ $notification->id }}</td>

                                        <td>
                                            @if (!is_null($notification->topic))
                                                {{ $notification->topic }}
                                            @else
                                            @php
                                            // Assuming $user_id contains the user_id you want to query for
                                            $user = App\Models\User::find($notification->user_id);
                                            $userType = $user ? $user->type : null;

                                             @endphp
                                                @if ($userType == 1)
                                                    store
                                                @else
                                                    individual
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if ($notification->user_id == null)
                                                Null
                                            @else
                                                @php
                                                    $user = App\Models\User::find($notification->user_id);
                                                    $userType = $user ? $user->type : null;
                                                @endphp


                                                    <a href="/userDetail/{{ $notification->user_id }}">{{ $notification->user_id }}</a>

                                            @endif
                                        </td>
                                        <td>{{ $notification->title }}</td>
                                        <td>{{ $notification->description }}</td>
                                        {{-- <td><a href="{{ url('editNotification', $notification->id) }}"
                                                class="btn btn-sm btn-primary" value="{{ $notification->id }}">Edit</a>
                                        </td> --}}

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row my-3">
                            <div class="col-lg-8 mx-2">
                                {{ $listnotification->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>

@endsection


<script></script>
