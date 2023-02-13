@extends('admin.layouts.app')

@section('content')

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Categories</h4>


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
                <div>
                    <button type="button" class="btn btn-primary" id="addModalBtn" data-bs-toggle="modal"
                        data-bs-target="#addAdminModal">
                        <span class="flex-center">Add <i class="bx bx-plus"></i></span>
                    </button>
                </div>
                <ul class="pagination">
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
                        <span>Showing {{ $listAdmin->firstItem() }} to {{ $listAdmin->lastItem() }}
                            of total {{ $listAdmin->total() }} entries</span>
                    </div>
                </div>
            </nav>
            <table class="table  " id="table_id" style="width: 100%" class="text-center">
                <thead class="text-center">
                    <tr>
                       <th>Created at</th>
                       <th>Title</th>
                       <th>Icon</th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach ($listAdmin as $key => $admin)
                        <tr>
                            <td>{{ $admin->created_at->format('Y-m-d ') }}</td>
                            <td>{{ $admin->first_name }} </td>
                            <td>{{ $admin->last_name }}</td>
                            <td>{{ $admin->phone }} </td>

                            <td>
                                @if ($admin->is_active == 1)
                                    <button type="button" value="{{ $admin->id }}"
                                        data-value1="{{ $admin->first_name . ' ' . $admin->last_name }}"
                                        class=" active_admin btn btn-success  btn-sm ">Active</button>
                                @else
                                    <button type="button" value="{{ $admin->id }}"
                                        data-value1="{{ $admin->first_name . ' ' . $admin->last_name }}"
                                        class="inactive_admin  btn btn-danger  btn-sm ">Inactive</button>
                                @endif
                                <button type="button" value="{{ $admin->id }}"
                                    data-value1="{{ $admin->first_name . ' ' . $admin->last_name }}"
                                    class="edit_admin  btn btn-warning editbtn btn-sm ">Edit</button>
                                {{-- <button type="button" value="{{ $admin->id }}"
                                    class="delete_admin  btn btn-danger deletebtn btn-sm ">Delete</button> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $listAdmin->links() }}
                </div>
            </div>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->
@endsection


@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#search').on('keyup', function() {
                const z = $('#search').val();
                console.log(z)
                if (z.length < 1) {
                    location.href = '/adminn'
                }
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //Edit Admin
            $(document).on('click', '.edit_admin', function(e) {
                e.preventDefault();
                var admin_id = $(this).val();
                console.log(admin_id)
                $('#editAdminModal').modal('show')
                $.ajax({
                    type: 'GET',
                    url: 'editAdmin/' + admin_id,
                    success: function(response) {
                        console.log(response);
                        if (response.status == 404) {
                            $('#success_message').html("")
                            $('#success_message').addClass('alert alert-danger')
                            $('#success_message').text(response.message)
                        } else {
                            $('#edit_id').val(response.admin.id)
                            $('#edit_first_name').val(response.admin.first_name)
                            $('#edit_last_name').val(response.admin.last_name)
                            $('#edit_email').val(response.admin.email)
                            // $('#edit_password').val(response.admin.password)
                            $('#edit_phone').val(response.admin.phone)

                            $('#error_edit_first_name').html('')
                            $('#error_edit_last_name').html('')
                            $('#error_edit_email').html('')
                            $('#error_edit_password').html('')
                            $('#error_edit_mobile').html('')
                            $('.update_admin').text('Update')
                        }
                    }

                })
            })

            //Update Admin
            $(document).on('click', '.update_admin', function(e) {
                e.preventDefault();
                var admin_id = $('#edit_id').val();
                $.ajax({
                    type: "POST",
                    url: "/updateAdmin/" + admin_id,
                    data: $('#UpdateAdminForm').serialize(),
                    dataType: 'json',
                    success: function(response) {
                        console.log(response)
                        if (response.status == 400) {
                            response.errors.first_name != undefined ? $(
                                    '#error_edit_first_name').html(response.errors.first_name) :
                                $('#error_edit_first_name').html('')
                            response.errors.last_name != undefined ? $('#error_edit_last_name')
                                .html(response.errors.last_name) : $('#error_edit_last_name')
                                .html('')
                            response.errors.phone != undefined ? $('#error_edit_phone').html(
                                response.errors.phone) : $('#error_edit_phone').html('')
                            response.errors.email != undefined ? $('#error_edit_email').html(
                                response.errors.email) : $('#error_edit_email').html('')


                            response.errors.password != undefined ? $('#error_edit_password')
                                .html(response.errors.password) : $('#error_edit_password')
                                .html('')

                            response.errors.confirm_password != undefined ? $(
                                '#error_edit_confirm_password').html(
                                response.errors.confirm_password) : $(
                                '#error_edit_confirm_password').html('')

                        } else if (response.status == 404) {
                            response.message != undefined ? $('#error_edit_confirm_password')
                                .html(response.message) : $('#error_edit_confirm_password')
                                .html('');
                            $('#update_error_message').html('');
                            $('#update_error_message').addClass('alert alert-danger');
                            $('#update_error_message').text('response.message');
                        } else {
                            $('#success_message').text(response.message)
                            $('#success_message').addClass('alert alert-success')
                            $('#editAdminModal').modal('hide')

                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);

                        }
                    }
                })
            })

            //Add Admin
            $('.add_admin').click(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "/addNewAdmin",
                    data: $('#AddAdminForm').serialize(),
                    dataType: 'json',
                    success: function(response) {
                        console.log(response)

                        if (response.status == 400) {
                            response.errors.first_name != undefined ? $('#error_first_name')
                                .html(response.errors.first_name) : $('#error_first_name').html(
                                    '')
                            response.errors.last_name != undefined ? $('#error_last_name').html(
                                response.errors.last_name) : $('#error_last_name').html('')
                            response.errors.phone != undefined ? $('#error_phone').html(response
                                .errors.phone) : $('#error_phone').html('')
                            response.errors.email != undefined ? $('#error_email').html(response
                                .errors.email) : $('#error_email').html('')
                            response.errors.password != undefined ? $('#error_password').html(
                                response.errors.password) : $('#error_password').html('')
                            response.errors.confirm_password != undefined ? $(
                                '#error_confirm_password').html(
                                response.errors.confirm_password) : $(
                                '#error_confirm_password').html('')

                        } else if (response.status == 404) {
                            response.message != undefined ? $('#error_confirm_password').html(
                                response.message) : $('#error_confirm_password').html('')

                        } else {
                            $('#success_message').text(response.message)
                            $('#success_message').addClass('alert alert-success')
                            $('#addAdminModal').modal('hide')
                            $('#AddAdminForm')[0].reset();
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                    }
                })
            })


            $(document).on('click', '.active_admin', function(e) {
                e.preventDefault();
                var name = $(this).data('value1');
                var user_id = $(this).val();
                $('#active_id').val(user_id)
                $('#inactive_title').text('Inactivate  ' + name)
                $('#inactive_msg').text('Are you sure do you want to inactivate  ' + name)
                $('#activeUserModal').modal('show')
            })

            $(document).on('click', '.inactive_admin', function(e) {
                e.preventDefault();
                var name = $(this).data('value1');
                var user_id = $(this).val();
                $('#inactive_id').val(user_id)
                $('#active_title').text('Activate  ' + name)
                $('#active_msg').text('Are you sure do you want to activate  ' + name)
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




            // $(document).on('click', '.delete_admin', function(e) {
            //     e.preventDefault();

            //     var admin_id = $(this).val();
            //     console.log(admin_id)
            //     $('#delete_id').val(admin_id)
            //     $('#title_user_delete').text('Are you sure?')
            //     $('#deleteAdminModal').modal('show')
            // })

            // $(document).on('click', '.delete_admin_btn', function(e) {
            //     e.preventDefault();

            //     var admin_id = $('#delete_id').val();

            //     $.ajax({
            //         type: 'GET',
            //         url: 'deleteAdmin/' + admin_id,
            //         success: function(response) {
            //             // console.log(response);
            //             $('#success_message').addClass('alert alert-success')
            //             $('#success_message').text(response.message)
            //             $('#deleteAdminModal').modal('hide')
            //             // fetchUser()
            //             // location.reload(true)

            //         }
            //     })
            // })



        })
    </script>
@endsection
