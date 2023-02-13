@extends('admin.layouts.app')

@section('title', 'Notifications')

@section('content')
@include('admin.notification.addNotificationModal')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Notifications</h4>

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
                        data-bs-target="#addNotificationModal">
                        <span class="flex-center">Add <i class="bx bx-plus"></i></span>
                    </button>
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


        <div class="table-responsive text-nowrap">
            <nav class="nav-pagination" aria-label="Page navigation">
                <div class="row mb-0">
                    <div class="label col-lg-10 col-md-6 mx-3">
                        <span>Showing {{ $listNotifications->firstItem() }} to {{ $listNotifications->lastItem() }}
                            of total {{ $listNotifications->total() }} entries</span>
                    </div>
                </div>
            </nav>
            <table class="table  " id="table_id" style="width: 100%" class="text-center">
                <thead class="text-center">
                    <tr>
                        <th>Created At</th>
                        <th>User Name</th>
                        <th>Title</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($listNotifications as $notification)
                        <tr>
                            <td>{{ $notification->created_at->format('d-m-Y') }}</td>
                            <td>{{ $notification->user !='' ? $notification->user->first_name.' '.$notification->user->last_name : $notification->user_id}}</td>
                            <td>{{ $notification->title }}</td>
                            <td>{{ $notification->description }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $listNotifications->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script>

        $(document).ready(function() {

            $('#search').on('keyup', function() {
                const search = $('#search').val();
                console.log(search)
                if (search.length < 1) {
                    location.href = 'notification'
                }
            });

            $('.select2').select2({
                width: '100%',
                dropdownParent: $('#addNotificationModal'),
                theme: 'classic'
            })

               //Add Notification
               $('.add_notification').click(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "/addNewNotification",
                    data: $('#AddNotificationForm').serialize(),
                    dataType: 'json',
                    success: function(response) {
                        console.log(response)

                        if (response.status == 400) {
                            response.errors.title != undefined ? $('#error_title')
                                .html(response.errors.title) : $('#error_title').html(
                                    '')
                            response.errors.description != undefined ? $('#error_description').html(
                                response.errors.description) : $('#error_description').html('')
                            response.errors.user_id != undefined ? $('#error_user_id').html(response
                                .errors.user_id) : $('#error_user_id').html('')

                        }
                        else if(response.status == 404){
                            response.message != undefined ? $('#error_user_id').html(response
                                .message) : $('#error_user_id').html('')
                            // setTimeout(function() {
                            //     window.location.reload();
                            // }, 10000);
                        }
                         else {
                            $('#success_message').text(response.message)
                            $('#success_message').addClass('alert alert-success')
                            $('#addNotificationModal').modal('hide')
                            $('#AddNotificationForm')[0].reset();
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                    }
                })
            })


        })
    </script>
@endsection

