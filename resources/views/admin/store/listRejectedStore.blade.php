@extends('admin.layouts.app')

@section('content')

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Rejected Store</h4>

    <!-- Basic Bootstrap Table -->

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
                <div class="row mt-3">
                    <div class="label col-lg-10 col-md-6 mx-3">
                        <span>Showing {{ $listRejectedStore->firstItem() }} to {{ $listRejectedStore->lastItem() }}
                            of total {{ $listRejectedStore->total() }} entries</span>
                    </div>
                </div>
            </nav>

            <table class="table" id="table_id" style="width: 100%">
                <thead class="text-center">
                    <tr>
                        <th><a class="text-dark"
                                href="{{ route('admin.store.listRejectedStore', ['sort' => 'created_at', 'direction' => $sortColumn == 'created_at' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">Created
                                At @if ($sortColumn == 'created_at')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>

                        <th><a class="text-dark"
                                href="{{ route('admin.store.listRejectedStore', ['sort' => 'first_name', 'direction' => $sortColumn == 'first_name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
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
                                href="{{ route('admin.store.listRejectedStore', ['sort' => 'last_name', 'direction' => $sortColumn == 'last_name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
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
                                href="{{ route('admin.store.listRejectedStore', ['sort' => 'email', 'direction' => $sortColumn == 'email' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                email
                                @if ($sortColumn == 'email')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                            <th><a class="text-dark"
                                href="{{ route('admin.store.listRejectedStore', ['sort' => 'store_name', 'direction' => $sortColumn == 'store_name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            Store name
                                @if ($sortColumn == 'store_name')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th><a class="text-dark"
                                href="{{ route('admin.store.listRejectedStore', ['sort' => 'phone', 'direction' => $sortColumn == 'phone' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                phone
                                @if ($sortColumn == 'phone')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($listRejectedStore as $key => $rejectedStore)
                        <tr>
                            <td>{{ $rejectedStore->created_at->format('Y-m-d ') }} </td>
                            <td>{{ $rejectedStore->first_name }} </td>
                            <td>{{ $rejectedStore->last_name }} </td>
                            <td>{{ $rejectedStore->email }} </td>
                            <td>{{ $rejectedStore->store_name }} </td>
                            <td>{{ $rejectedStore->phone }} </td>
                            <td><a href="/userDetail/{{ $rejectedStore->id }}" class="btn btn-info btn-sm">View More Details</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{-- {{ $listRejectedStore->links() }} --}}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                const z = $('#search').val();
                console.log(z)
                if (z.length < 1) {
                    location.href = 'rejectedStore'
                }
            });

            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

            $(document).on('click', '.active_store', function(e) {
                    e.preventDefault();

                    var admin_id = $(this).val();
                    console.log(admin_id)
                    $('#active_id').val(admin_id)
                    $('#title_user_delete').text('Are you sure?')
                    $('#activestoreModal').modal('show')
                })

                $(document).on('click', '.inactive_store', function(e) {
                    e.preventDefault();

                    var admin_id = $(this).val();
                    console.log(admin_id)
                    $('#inactive_id').val(admin_id)
                    $('#inactivestoreModal').modal('show')
                })


                $(document).on('click', '.is_active', function(e) {
                    e.preventDefault();

                    var admin_id = $('#active_id').val();
                    console.log(admin_id)
                    $.ajax({
                        type: 'POST',
                        url: 'store_inactive/' + admin_id,
                        success: function(response) {
                            console.log(response);
                            $('#success_message').addClass('alert alert-success')
                            $('#success_message').text(response.message)
                            $('#activestoreModal').modal('hide')

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
                        url: 'store_active/' + admin_id,
                        success: function(response) {
                            console.log(response);
                            $('#success_message').addClass('alert alert-success')
                            $('#success_message').text(response.message)
                            $('#inactivestoreModal').modal('hide')

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
