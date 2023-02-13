@extends('admin.layouts.app')
{{-- Active Modal --}}
@include('admin.moreDetails.activate_modal.activeUserModal')
{{-- Inactive Modal --}}
@include('admin.moreDetails.activate_modal.inactiveUserModal')

@section('content')

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Active Stores
    </h4>

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
            <div class="m-3 d-flex gap-2">
                <a href="/createStore" class="">
                    <button type="button" class="btn btn-primary">
                        <span class="flex-center">Add <i class="bx bx-plus"></i></span>
                    </button>
                </a>
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
                        <span>Showing {{ $listStore->firstItem() }} to {{ $listStore->lastItem() }}
                            of total {{ $listStore->total() }} entries</span>
                    </div>
                </div>
            </nav>

            <table class="table" id="table_id" style="width: 100%">
                <thead class="text-center">
                    <tr>
                        <th><a class="text-dark"
                                href="{{ route('admin.store.listStore', ['sort' => 'created_at', 'direction' => $sortColumn == 'created_at' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">Created
                                At @if ($sortColumn == 'created_at')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>

                            <th><a class="text-dark"
                                href="{{ route('admin.store.listStore', ['sort' => 'first_name', 'direction' => $sortColumn == '=first_name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">first
                                Name @if ($sortColumn == '=first_name')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th><a class="text-dark"
                            href="{{ route('admin.store.listStore', ['sort' => 'last_name', 'direction' => $sortColumn == 'last_name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">last
                            Name @if ($sortColumn == 'last_name')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a>
                    </th>

                        <th><a class="text-dark"
                            href="{{ route('admin.store.listStore', ['sort' => 'email', 'direction' => $sortColumn == 'email' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">email
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
                        href="{{ route('admin.store.listStore', ['sort' => 'store_name', 'direction' => $sortColumn == 'store_name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">Store
                        Name @if ($sortColumn == 'store_name')
                            @if ($sortDirection == 'asc')
                                <i class="fas fa-arrow-up"></i>
                            @else
                                <i class="fas fa-arrow-down"></i>
                            @endif
                        @endif
                    </a>
                </th>
                    <th><a class="text-dark"
                        href="{{ route('admin.store.listStore', ['sort' => 'phone', 'direction' => $sortColumn == 'phone' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">Phone
                        @if ($sortColumn == 'phone')
                            @if ($sortDirection == 'asc')
                                <i class="fas fa-arrow-up"></i>
                            @else
                                <i class="fas fa-arrow-down"></i>
                            @endif
                        @endif
                    </a>
                </th>
                        <th>Details</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($listStore as $key => $store)
                        <tr>
                            <td>{{ $store->created_at->format('Y-m-d ') }} </td>
                            <td>{{ $store->first_name }} </td>
                            <td>{{ $store->last_name }} </td>
                            <td>{{ $store->email }} </td>
                            <td>{{ $store->store_name }} </td>
                            <td>{{ $store->phone }} </td>
                            <td><a href="/userDetail/{{ $store->id }}" class="btn btn-info btn-sm">View More
                                    Details</a></td>
                            <td>
                                @if ($store->is_active == 1)
                                    <button type="button" value="{{ $store->id }}" data-value1="{{ $store->first_name.' '.$store->last_name }}"
                                        class="active_store btn btn-success  btn-sm ">Active</button>
                                @else
                                    <button type="button" value="{{ $store->id }}" data-value1="{{ $store->first_name.' '.$store->last_name }}"
                                        class="inactive_store btn btn-danger  btn-sm ">Inactive</button>
                                @endif

                                <a href='editStore/{{ $store->id }}'
                                    class="edit_store  btn btn-warning editbtn btn-sm ">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $listStore->links() }}
                </div>

            </div>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $('#search').on('keyup', function() {
                const z = $('#search').val();
                console.log(z)
                if (z.length < 1) {
                    location.href = 'store'
                }
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });



            $(document).on('click', '.active_store', function(e) {
                    e.preventDefault();
                    var name = $(this).data('value1');
                    var user_id = $(this).val();
                    $('#active_id').val(user_id)
                    $('#inactive_title').text('Inactivate  '+ name)
                    $('#inactive_msg').text('Are you sure do you want to inactivate  '+ name)
                    $('#activeUserModal').modal('show')
                })

                $(document).on('click', '.inactive_store', function(e) {
                    e.preventDefault();
                    var name = $(this).data('value1');
                    var user_id = $(this).val();
                    $('#inactive_id').val(user_id)
                    $('#active_title').text('Activate  '+ name)
                    $('#active_msg').text('Are you sure do you want to activate  '+ name)
                    $('#inactiveUserModal').modal('show')
                })


                $(document).on('click', '.is_active', function(e) {
                    e.preventDefault();

                    var admin_id = $('#active_id').val();
                    console.log(admin_id)
                    $.ajax({
                        type: 'POST',
                        url: 'inactive/' + admin_id,
                        success: function(response) {
                            console.log(response);
                            $('#success_message').addClass('alert alert-success')
                            $('#success_message').text(response.message)
                            $('#activeUserModal').modal('hide')

                            // location.reload(true)
                              setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                        }
                    })
                })

                $(document).on('click', '.is_inactive', function(e) {
                    e.preventDefault();

                    var admin_id = $('#inactive_id').val();
                    console.log(admin_id)
                    $.ajax({
                        type: 'POST',
                        url: 'active/' + admin_id,
                        success: function(response) {
                            console.log(response);
                            $('#success_message').addClass('alert alert-success')
                            $('#success_message').text(response.message)
                            $('#inactiveUserModal').modal('hide')

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
