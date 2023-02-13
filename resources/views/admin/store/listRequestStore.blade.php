@extends('admin.layouts.app')


@section('content')

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span>Request Store</h4>

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
                        <span>Showing {{ $listRequestStore->firstItem() }} to {{ $listRequestStore->lastItem() }}
                            of total {{ $listRequestStore->total() }} entries</span>
                    </div>
                </div>
            </nav>

            <table class="table" id="table_id" style="width: 100%">
                <thead class="text-center">
                    <tr>
                        <th><a class="text-dark"
                                href="{{ route('admin.store.listRequestStore', ['sort' => 'created_at', 'direction' => $sortColumn == 'created_at' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">Created
                                At @if ($sortColumn == 'created_at')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>

                        <th><a class="text-dark"
                                href="{{ route('admin.store.listRequestStore', ['sort' => 'first_name', 'direction' => $sortColumn == 'first_name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
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
                                href="{{ route('admin.store.listRequestStore', ['sort' => 'last_name', 'direction' => $sortColumn == 'last_name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
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
                                href="{{ route('admin.store.listRequestStore', ['sort' => '=email', 'direction' => $sortColumn == '=email' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                email
                                @if ($sortColumn == '=email')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                            <th><a class="text-dark"
                                href="{{ route('admin.store.listRequestStore', ['sort' => 'store_name', 'direction' => $sortColumn == 'store_name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                store name
                                @if ($sortColumn == 'store_name')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th><a class="text-dark"
                                href="{{ route('admin.store.listRequestStore', ['sort' => 'phone', 'direction' => $sortColumn == 'phone' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
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
                        {{-- <th>Status</th> --}}
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($listRequestStore as $key => $requeststore)
                        <tr>
                            <td>{{ $requeststore->created_at->format('Y-m-d ') }} </td>
                            <td>{{ $requeststore->first_name }} </td>
                            <td>{{ $requeststore->last_name }} </td>
                            <td>{{ $requeststore->email }} </td>
                            <td>{{ $requeststore->store_name }} </td>
                            <td>{{ $requeststore->phone }} </td>
                            <td><a href="/userDetail/{{ $requeststore->id }}" class="btn btn-info btn-sm">View More
                                    Details</a></td>
                            {{-- <td><span class="btn btn-dark btn-sm">{{ $requeststore->status }}</span></td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $listRequestStore->links() }}
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
                    location.href = 'requestStore'
                }
            });

        })
    </script>
@endsection
