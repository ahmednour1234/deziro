@extends('admin.layouts.app')

@section('title', 'Category')


@include('admin.category.activate_modal.approveRequestModal')
@include('admin.category.activate_modal.rejectRequestModal')

@section('content')


    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Category</h4>

    <div id="success_message"></div>
    <div class="card">

        <div class="d-flex justify-content-between  items-center">
            <div class="col-lg-3 col-md-6 mb-0 mt-3  mx-3">
                <form action="" method="get" id="searchForm">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text" id="category-addon-search"><i class="bx bx-search"></i></span>
                        <input type="text" id="search" name="search" class="form-control" placeholder="Search ..."
                            value="{{ request()->get('search') }}" autofocus>

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
                        <span>Showing {{ $listRequestCategorie->firstItem() }} to {{ $listRequestCategorie->lastItem() }}
                            of total {{ $listRequestCategorie->total() }} entries</span>
                    </div>

                </div>
            </nav>
            <table class="table" id="table_id" style="width: 100%">
                <thead>
                    <tr class="text-center">
                        <th>Requested At</th>
                        <th>Store Name</th>
                        <th>old Categories</th>
                        <th>Request Categories</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">

                    @foreach ($listRequestCategorie as $key => $requestCategorie)
                        <tr class="text-center">
                            <td>{{ $requestCategorie->created_at->format('Y-m-d ') }}</td>
                            <td>{{ $requestCategorie->user->store_name }}</td>
                            @php
                                $oldcategories = App\Models\User::where('id', $requestCategorie->user_id)->first();

                                if ($oldcategories != '') {
                                    $old = explode(',', $oldcategories->categories);
                                }
                            @endphp
                            <td>
                                @foreach ($old as $category)
                                    @foreach ($listCategorys as $categorys)
                                        @if ($category == $categorys->id)
                                            <button class="btn btn-success btn-sm">{{ $categorys->name }}</button>
                                        @endif
                                    @endforeach
                                @endforeach
                            </td>
                            @php
                                $categories = explode(',', $requestCategorie->new_categories);
                            @endphp

                            <td>
                                @foreach ($categories as $category)
                                    @foreach ($listCategorys as $categorys)
                                        @if ($category == $categorys->id)
                                            <button class="btn btn-success btn-sm">{{ $categorys->name }}</button>
                                        @endif
                                    @endforeach
                                @endforeach
                            </td>

                            <td>
                                <button class="btn btn-primary btn-sm approve"
                                    value="{{ $requestCategorie != '' ? $requestCategorie->id : '' }}">Approve</button>
                                <button class="btn btn-danger btn-sm reject"
                                    value="{{ $requestCategorie != '' ? $requestCategorie->id : '' }}">Reject</button>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $listRequestCategorie->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection



@section('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $(document).on('click', '.reject', function(e) {
                e.preventDefault();

                var request_id = $(this).val();
                console.log(request_id)
                $('#reject_id').val(request_id)
                $('#title_user_delete').text('Are you sure?')
                $('#rejectRequestModal').modal('show')
            })

            $(document).on('click', '.reject_btn', function(e) {
                e.preventDefault();

                var request_id = $('#reject_id').val();
                console.log(request_id)
                $.ajax({
                    type: 'POST',
                    url: 'rejectRequest/' + request_id,
                    success: function(response) {
                        console.log(response);
                        $('#success_message').addClass('alert alert-success')
                        $('#success_message').text(response.message)
                        $('#rejectRequestModal').modal('hide')

                        // location.reload(true)
                        location.reload();
                    }
                })
            })

            $(document).on('click', '.approve', function(e) {
                e.preventDefault();

                var request_id = $(this).val();
                console.log(request_id)
                $('#approve_id').val(request_id)
                $('#approveRequestModal').modal('show')
            })

            $(document).on('click', '.approve_btn', function(e) {
                e.preventDefault();

                var request_id = $('#approve_id').val();
                console.log(request_id)
                $.ajax({
                    type: 'POST',
                    url: 'approveRequest/' + request_id,
                    success: function(response) {
                        console.log(response);
                        $('#success_message').addClass('alert alert-success')
                        $('#success_message').text(response.message)
                        $('#approveRequestModal').modal('hide')

                        // location.reload(true)
                        location.reload();
                    }
                })
            })



        })
    </script>

@endsection
