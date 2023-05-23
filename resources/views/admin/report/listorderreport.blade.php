@extends('admin.layouts.app')


@section('content')




<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
            /</a></span> Order Report
</h4>

<!-- Basic Bootstrap Table -->

@if (Session::has('success'))
<div class="alert alert-success">
    <ul>
        <li>{{ Session::get('success') }}</li>
    </ul>
</div>
@endif

<div class="card">
    <div class="d-flex justify-content-between  items-center">
        <div class="col-lg-2 col-md-6 mb-0 mt-4  mx-3">
            <form action="" method="get" id="searchForm">
                <div class="d-flex gap-3">
                    <div class=" col-lg-2 input-group input-group-merge">
                        <span class="input-group-text" id="addon-search"><i class="bx bx-search"></i></span>
                        <input type="text" id="search" name="search" class="form-control" placeholder="Search ..." value="{{ request()->get('search') }}" autofocus>

                    </div>

                    <div class="col-lg-2 input-group input-group-merge">
                        <select name="status" class="form-select" value="{{ request()->get('status') }}">
                            <option value="">Select Status</option>
                            <option value="Pending" {{ request()->get('status') == 'Pending' ? 'selected' : '' }}>
                                Pending</option>
                            <option value="pending_payment" {{ request()->get('status') == 'pending_payment' ? 'selected' : '' }}>
                                Pending Payment</option>
                            <option value="shipped" {{ request()->get('status') == 'shipped' ? 'selected' : '' }}>
                                Shipped</option>
                            <option value="delivered" {{ request()->get('status') == 'delivered' ? 'selected' : '' }}>
                                Delivered</option>
                            <option value="canceled" {{ request()->get('status') == 'canceled' ? 'selected' : '' }}>Canceled
                            </option>


                        </select>
                    </div>

                </div>
                <div class="d-flex gap-3 my-4 " style="margin-left:200%">
                    <div class="">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                    <div class="">
                        <a href="{{ route('admin.report.listorderreport') }}" class="btn btn-danger">Cancel</a>
                    </div>
                    <div class="">
                    </div>
                </div>
            </form>
        </div>
        <div class="m-3 d-flex gap-2 my-4">
            <ul class="pagination    ">
                <li class="">
                    <div class="btn-group">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">{{ currentLimit() }}</button>
                        <ul class="dropdown-menu" style="min-width: auto;">
                            @foreach (limits() as $limit)
                            <li><a class="dropdown-item {{ $limit['active'] ? 'active' : '' }}" href="{{ $limit['url'] }}">{{ $limit['label'] }}</a></li>
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
                    <span>Showing {{ $listOrder->firstItem() }} to {{ $listOrder->lastItem() }}
                        of total {{ $listOrder->total() }} entries</span>
                </div>
            </div>
        </nav>

        <table class="table" id="table_id" style="width: 100%">
            <thead>
                <tr>
                    <th>Created at</th>
                    <th>F_Name</th>
                    <th>L_Name</th>
                    <th>Total Item Count</th>
                    <th>Order Currency Code</th>
                    <th>Grand Total</th>
                    <th>Base Grand Total</th>
                    <th>Sub Total</th>
                    <th>Base Sub Total</th>
                    <th>Fees Amount</th>
                    <th>Base fees amount</th>
                    <th>Discount Percent</th>
                    <th>Discount Amount</th>
                    <th>Base Discount Amount</th>
                    <th>Shipping Amount</th>
                    <th>Base Shipping Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($listOrder as $key => $order)
                <tr>
                    {{-- <td>{{ $order->store_name }} </td> --}}
                    <td>{{ $order->created_at }}</td>
                    <td>{{ $order->user_first_name }}</td>
                    <td>{{ $order->user_last_name }}</td>
                    <td>{{ $order->total_item_count}} </td>
                    <td>{{ $order->order_currency_code}} </td>
                    <td>{{ $order->grand_total}} </td>
                    <td>{{ $order->base_grand_total}} </td>
                    <td>{{ $order->sub_total}} </td>
                    <td>{{ $order->base_sub_total}} </td>
                    <td>{{ $order->fees_amount}} </td>
                    <td>{{ $order->base_fees_amount}} </td>
                    <td>{{ $order->discount_percent}} </td>
                    <td>{{ $order->discount_amount}} </td>
                    <td>{{ $order->base_discount_amount}} </td>
                    <td>{{ $order->shipping_amount}} </td>
                    <td>{{ $order->base_shipping_amount	}} </td>
                    <td>{{ ucfirst($order->status) }} </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row my-3">
            <div class="col-lg-8 mx-2">
                {{ $listOrder->links() }}
            </div>

        </div>
    </div>
</div>
@endsection
