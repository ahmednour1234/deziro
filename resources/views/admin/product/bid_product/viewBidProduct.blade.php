@extends('admin.layouts.app')

{{-- @yield('title', 'Product') --}}

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Products</h4>

    <!-- Basic Bootstrap Table -->


    <div id="success_message"></div>


    <div class="card">
        <div class="d-flex justify-content-between  items-center">
            <div class="col-lg-3 col-md-6 mb-0 mt-3  mx-3">
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

        </div>

        <div class="table-responsive text-nowrap">
            <nav class="nav-pagination" aria-label="Page navigation">

                <div class="row mt-3 ">
                    <div class="label col-lg-10 col-md-6 mx-3">
                        <span>Showing {{ $viewBidProduct->firstItem() }} to {{ $viewBidProduct->lastItem() }}
                            of total {{ $viewBidProduct->total() }} entries</span>
                    </div>
                </div>
            </nav>
            <table class="table">
                <thead>
                    <tr class="text-center">
                        <th>Date of Bid</th>
                        <th>Product Id</th>
                        <th>User Id</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0" id="myTable">
                    @foreach ($viewBidProduct as $key=>$viewBid )
                        <tr class="text-center">
                            <td>{{ $viewBid->created_at->format('Y-m-d d:H:i')  }}</td>
                            <td>{{ $viewBid->product_id }}</td>
                            @if($viewBid->user->type == 1)
                            <td><a href="/individualDetail/{{$viewBid->user_id}}" class="btn btn-info btn-sm">{{ $viewBid->user_id }}</td>
                            @else
                            <td><a href="/sotreDetail/{{ $viewBid->user_id }}" class="btn btn-info btn-sm">{{ $viewBid->user_id }}</a></td>
                            @endif
                            <td>{{ $viewBid->amount }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $viewBidProduct->links() }}
                </div>
                <ul class="pagination col-lg-3  mb-0  ">
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
    </div>
    <!--/ Basic Bootstrap Table -->
@endsection


