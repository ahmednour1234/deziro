@extends('admin.layouts.app')

{{-- @yield('title', 'Product') --}}

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Products
    </h4>

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
                        <span>Showing {{ $viewSwapProduct->firstItem() }} to {{ $viewSwapProduct->lastItem() }}
                            of total {{ $viewSwapProduct->total() }} entries</span>
                    </div>
                </div>
            </nav>
            <table class="table">
                <thead>
                    <tr class="text-center">
                        <th>Created At</th>
                        <th>Product Id</th>
                        <th>User Id</th>
                        <th> Swap Product Id</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0" id="myTable">
                    @foreach ($viewSwapProduct as $key => $viewSwap)
                        <tr class="text-center">
                            <td>{{ $viewSwap->created_at->format('Y-m-d d:H:i') }}</td>
                            <td>
                                @if ($viewSwap->product->type == 'sell')
                                    <a href="/sellingProductDetail/{{ $viewSwap->product->id }}"
                                        class="btn btn-info btn-sm">{{ $viewSwap->product->id }}</a>
                                @elseif($viewSwap->product->type == 'bid')
                                    <a href="/bidProductDetail/{{ $viewSwap->product->id }}"
                                        class="btn btn-info btn-sm">{{ $viewSwap->product->id }}</a>
                                @else
                                    <a href="/swapProductDetail/{{ $viewSwap->product->id }}"
                                        class="btn btn-info btn-sm">{{ $viewSwap->product->id }}</a>
                                @endif
                            </td>
                            <td>
                              @if($viewSwap->user->type == 1 )
                              <a href="/individualDetail/{{ $viewSwap->user_id }}" class="btn btn-primary btn-sm">{{ $viewSwap->user_id }}</a>
                              @else
                              <a href="/sotreDetail/{{ $viewSwap->user_id }}" class="btn btn-primary btn-sm">{{ $viewSwap->user_id }}</a>
                              @endif
                            </td>
                            <td>
                                @if ($viewSwap->swapproduct->type == 'sell')
                                    <a href="/sellingProductDetail/{{ $viewSwap->swapproduct->id }}"
                                        class="btn btn-info btn-sm">{{ $viewSwap->swapproduct->id }}</a>
                                @elseif($viewSwap->swapproduct->type == 'bid')
                                    <a href="/bidProductDetail/{{ $viewSwap->swapproduct->id }}"
                                        class="btn btn-info btn-sm">{{ $viewSwap->swapproduct->id }}</a>
                                @else
                                    <a href="/swapProductDetail/{{ $viewSwap->swapproduct->id }}"
                                        class="btn btn-info btn-sm">{{ $viewSwap->swapproduct->id }}</a>
                                @endif
                            </td>
                            <td><button class="btn btn-dark btn-sm">{{ $viewSwap->request_status }}</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $viewSwapProduct->links() }}
                </div>
            </div>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->
@endsection
