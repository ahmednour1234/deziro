@extends('admin.layouts.app')

{{-- @yield('title', 'Product') --}}

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span>Individual Rejected Products</h4>

    <!-- Basic Bootstrap Table -->
        @include('admin.product.rejected_product.showReasonModal')

    <div id="success_message"></div>


    <div class="card">
        <div class="d-flex justify-content-between  items-center">
            <div class="col-lg-3 col-md-6 mb-0 mt-3  mx-3">
                <form action="" method="get" id="searchForm">
                    <div class="d-flex gap-3">
                    <div class=" col-lg-3 input-group input-group-merge">
                        <span class="input-group-text" id="addon-search"><i class="bx bx-search"></i></span>
                        <input type="text" id="search" name="search" class="form-control" placeholder="Search ..."
                            value="{{ request()->get('search') }}" autofocus>
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
                <div class="row mt-3 ">
                    <div class="label col-lg-10 col-md-6 mx-3">
                        <span>Showing {{ $listRejectedProduct->firstItem() }} to {{ $listRejectedProduct->lastItem() }}
                            of total {{ $listRejectedProduct->total() }} entries</span>
                    </div>
                </div>
            </nav>
            <table class="table" id="table_id" style="width: 100%">
                <thead class="text-center">
                    <tr>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listRejectedProduct', ['sort' => 'created_at', 'direction' => $sortColumn == 'created_at' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            Created  At
                             @if ($sortColumn == 'created_at')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listRejectedProduct', ['sort' => 'id', 'direction' => $sortColumn == 'id' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                       product id
                             @if ($sortColumn == 'id')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listRejectedProduct', ['sort' => 'name', 'direction' => $sortColumn == 'name' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                           product name
                             @if ($sortColumn == 'name')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listRejectedProduct', ['sort' => 'user_id', 'direction' => $sortColumn == 'user_id' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            individual id
                             @if ($sortColumn == 'user_id')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listRejectedProduct', ['sort' => 'type', 'direction' => $sortColumn == 'type' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                         product type
                             @if ($sortColumn == 'type')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listRejectedProduct', ['sort' => 'subcategory_id', 'direction' => $sortColumn == 'subcategory_id' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            Sub
                             @if ($sortColumn == 'subcategory_id')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listRejectedProduct', ['sort' => 'condition', 'direction' => $sortColumn == 'condition' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            Condition
                             @if ($sortColumn == 'condition')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th><a class="text-dark"
                            href="{{ route('admin.product.listRejectedProduct', ['sort' => 'money_collection', 'direction' => $sortColumn == 'money_collection' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            money collection
                             @if ($sortColumn == 'money_collection')
                                @if ($sortDirection == 'asc')
                                    <i class="fas fa-arrow-up"></i>
                                @else
                                    <i class="fas fa-arrow-down"></i>
                                @endif
                            @endif
                        </a></th>
                        <th>reason</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($listRejectedProduct as $key => $rejectedProduct)
                        <tr>
                            <td>{{ $rejectedProduct->created_at->format('Y-m-d ') }} </td>
                            <td>{{ $rejectedProduct->id }}</td>
                            <td>{{ $rejectedProduct->name }} </td>
                            <td><a href="individualDetail/{{ $rejectedProduct->user_id }}" class="btn btn-dark btn-sm">
                                    {{ $rejectedProduct->user_id }}
                                </a></td>
                            <td>{{ $rejectedProduct->type }} </td>
                            <td>{{ $rejectedProduct->subcategorie->name }} </td>
                            <td>{{ $rejectedProduct->condition }}</td>
                            {{-- <td>{{ $rejectedProduct->price }}</td> --}}
                            <td>{{ $rejectedProduct->money_collection }}</td>
                            <td>  <button class="btn btn-warning btn-sm reason" value="{{ $rejectedProduct->reason }}">reason</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $listRejectedProduct->links() }}
                </div>
            </div>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            $('#search').on('keyup',function(){
                    const z = $('#search').val();
                    console.log(z)
                    if(z.length<1){
                        location.href = 'rejectedProduct'
                    }
                 });
            // $.fn.dataTable.ext.errMode = 'throw';
            // var table = $('#table_id').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     ajax: "rejectedProduct",
            //     columns: [{
            //             "title": "#",
            //             render: function(data, type, row, meta) {
            //                 console.log(row)
            //                 return meta.row + meta.settings._iDisplayStart + 1;
            //             }
            //         },

            //         {
            //             data: 'name'
            //         },

            //           {
            //             data: null,
            //             render: function(data, row, type) {

            //                     return `${data.user.first_name  + " " + data.user.last_name}`

            //             }
            //         },
            //         {
            //             data: 'category.type'
            //         },
            //         {
            //             data: 'subcategorie.name'
            //         },
            //         {
            //             data: 'condition'
            //         },

            //         {
            //             data: 'price'
            //         },

            //         {
            //             data: null,
            //             render: function(data, row, type) {
            //                 return `
            //                 <button class="btn btn-warning btn-sm reason" value="${data.reason}">reason</button>
            //                   `;
            //             }
            //         },

            //         {
            //             data: null,
            //             render: function(data, row, type) {
            //                 return `
            //                 <button class="btn btn-dark btn-sm">${data.status}</button>
            //                   `;
            //             }
            //         }

            //     ]

            // });


            $(document).on('click' , '.reason', function(e){
                e.preventDefault();
                $('#showReasonModal').modal('show')
                var reason = $(this).val();
                $('#reason').html(reason);
            })

        })
    </script>
@endsection
