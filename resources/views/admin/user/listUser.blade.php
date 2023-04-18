@extends('admin.layouts.app')

{{-- Active Modal --}}
@include('admin.moreDetails.activate_modal.activeModal')
{{-- Inactive Modal --}}
@include('admin.moreDetails.activate_modal.inactiveModal')
@section('content')


    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Users</h4>

    <!-- Basic Bootstrap Table -->

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
                        <span>Showing {{ $listUser->firstItem() }} to {{ $listUser->lastItem() }}
                            of total {{ $listUser->total() }} entries</span>
                    </div>
                </div>
            </nav>
            <table class="table  " id="table_id" style="width: 100%" class="text-center">
                <thead class="text-center">
                    <tr>
                        <th><a class="text-dark"
                                href="{{ route('admin.user.listUser', ['sort' => 'created_at', 'direction' => $sortColumn == 'created_at' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
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
                                href="{{ route('admin.user.listUser', ['sort' => 'first_name', 'direction' => $sortColumn == 'first_name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                first name
                                @if ($sortColumn == 'first_name')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th><a class="text-dark"
                                href="{{ route('admin.user.listUser', ['sort' => 'last_name', 'direction' => $sortColumn == 'last_name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                last name
                                @if ($sortColumn == 'last_name')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th><a class="text-dark"
                                href="{{ route('admin.user.listUser', ['sort' => 'phone', 'direction' => $sortColumn == 'phone' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                mobile
                                @if ($sortColumn == 'phone')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                            <th>Details</th>

                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach ($listUser as $key => $user)
                        <tr>
                            <td>{{ $user->created_at->format('Y-m-d ') }}</td>
                            <td>{{ $user->first_name }} </td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->phone }} </td>
                            <td><a href="/userDetail/{{ $user->id }}" class="btn btn-info btn-sm">View More
                                Details</a></td>

                            <td>
                                @if ($user->is_active == 1)
                                    <button type="button" value="{{ $user->id }}"
                                        data-value1="{{ $user->first_name . ' ' . $user->last_name }}"
                                        class=" active_user btn btn-success  btn-sm ">Active</button>
                                @else
                                    <button type="button" value="{{ $user->id }}"
                                        data-value1="{{ $user->first_name . ' ' . $user->last_name }}"
                                        class="inactive_user  btn btn-danger  btn-sm ">Inactive</button>
                                @endif
                            </td>
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
    <!--/ Basic Bootstrap Table -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $('#search').on('keyup', function() {
                const z = $('#search').val();
                console.log(z)
                if (z.length < 1) {
                    location.href = 'user'
                }
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $(document).on('click', '.active_user', function(e) {
                    e.preventDefault();
                    var name = $(this).data('value1');
                    var user_id = $(this).val();
                    $('#active_id').val(user_id)
                    $('#inactive_title').text('Inactivate  '+ name)
                    $('#inactive_msg').text('Are you sure do you want to inactivate  '+ name)
                    $('#activeModal').modal('show')
                })

                $(document).on('click', '.inactive_user', function(e) {
                    e.preventDefault();
                    var name = $(this).data('value1');
                    var user_id = $(this).val();
                    $('#inactive_id').val(user_id)
                    $('#active_title').text('Activate  '+ name)
                    $('#active_msg').text('Are you sure do you want to activate  '+ name)
                    $('#inactiveModal').modal('show')
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

                    var admin_id = $('#inactive_id').val();
                    console.log(admin_id)
                    $.ajax({
                        type: 'POST',
                        url: 'active/' + admin_id,
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




            // $(document).on('click', '.delete_user', function(e) {
            //     e.preventDefault();

            //     var user_id = $(this).val();
            //     console.log(user_id)
            //     $('#delete_id').val(user_id)
            //     $('#title_user_delete').text('Are you sure?')
            //     $('#deleteuserModal').modal('show')
            // })



        })
    </script>
@endsection
