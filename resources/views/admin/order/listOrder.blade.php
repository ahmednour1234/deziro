@extends('admin.layouts.app')


@section('content')
    @include('admin.order.action.shipOrderModal')
    @include('admin.order.action.cancelOrderModal')
    @include('admin.order.action.deliverOrderModal')
    @include('admin.order.action.showReasonModal')


    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> List Orders</h4>

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
                        <div class="col-lg-3 input-group input-group-merge">
                            <select name="status" class="form-select" value="{{ request()->get('status') }}">
                                <option value="">Select All Status</option>
                                <option value={{ App\Models\Order::STATUS_PENDING }}
                                    {{ request()->get('status') == App\Models\Order::STATUS_PENDING ? 'selected' : '' }}>
                                    {{ App\Models\Order::STATUS_PENDING }}
                                </option>
                                <option value={{ App\Models\Order::STATUS_SHIPPED }}
                                    {{ request()->get('status') == App\Models\Order::STATUS_SHIPPED ? 'selected' : '' }}>
                                    {{ App\Models\Order::STATUS_SHIPPED }}</option>
                                <option value={{ App\Models\Order::STATUS_DELIVERED }}
                                    {{ request()->get('status') == App\Models\Order::STATUS_DELIVERED ? 'selected' : '' }}>
                                    {{ App\Models\Order::STATUS_DELIVERED }}</option>
                                <option value={{ App\Models\Order::STATUS_CANCELED }}
                                    {{ request()->get('status') == App\Models\Order::STATUS_CANCELED ? 'selected' : '' }}>
                                    {{ App\Models\Order::STATUS_CANCELED }}</option>
                            </select>
                        </div>
                        <div class="">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="m-3 d-flex gap-2">
                <div>

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
                        <span>Showing {{ $listOrder->firstItem() }} to {{ $listOrder->lastItem() }}
                            of total {{ $listOrder->total() }} entries</span>
                    </div>

                </div>
            </nav>
            <table class="table  " id="table_id" style="width: 100% ">
                <thead>
                    <tr style="text-align:center">
                        <th><a class="text-dark"
                                href="{{ route('admin.order.listOrder', ['sort' => 'created_at', 'direction' => $sortColumn == 'created_at' && $sortDirection == 'asc' ? 'desc' : 'asc', 'status' => request()->get('status'), 'search' => request()->get('search')]) }}">Order
                                Date
                                @if ($sortColumn == 'created_at')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th><a class="text-dark"
                                href="{{ route('admin.order.listOrder', ['sort' => 'id', 'direction' => $sortColumn == 'id' && $sortDirection == 'asc' ? 'desc' : 'asc', 'status' => request()->get('status'), 'search' => request()->get('search')]) }}">
                                Order Number
                                @if ($sortColumn == 'id')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th>payment type</th>
                        <th><a class="text-dark"
                                href="{{ route('admin.order.listOrder', ['sort' => 'total_item_count', 'direction' => $sortColumn == 'total_item_count' && $sortDirection == 'asc' ? 'desc' : 'asc', 'status' => request()->get('status'), 'search' => request()->get('search')]) }}">
                                total items ordered
                                @if ($sortColumn == 'total_item_count')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th><a class="text-dark"
                                href="{{ route('admin.order.listOrder', ['sort' => 'grand_total', 'direction' => $sortColumn == 'grand_total' && $sortDirection == 'asc' ? 'desc' : 'asc', 'status' => request()->get('status'), 'search' => request()->get('search')]) }}">
                                Order Number
                                @if ($sortColumn == 'grand_total')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th><a class="text-dark"
                                href="{{ route('admin.order.listOrder', ['sort' => 'status', 'direction' => $sortColumn == 'status' && $sortDirection == 'asc' ? 'desc' : 'asc', 'status' => request()->get('status'), 'search' => request()->get('search')]) }}">
                                Status
                                @if ($sortColumn == 'status')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th>View Order</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($listOrder as $key => $order)
                        <tr style="text-align: center">
                            <td>{{ $order->created_at->format('Y-m-d ') }}</td>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->payment->method_title }}</td>
                            <td>{{ $order->total_item_count }}</td>
                            <td>{{ formatPrice($order->grand_total) }} </td>
                            <td>{{ $order->status }}</td>
                            <td><a href="/orderDetail/{{ $order->id }}" class="btn btn-sm btn-info"> View More
                                    Details</a></td>
                            <td>
                                @if ($order->status == App\Models\Order::STATUS_PENDING)
                                    <button type="button" class="shipped btn btn-primary btn-sm"
                                        value="{{ $order->id }}">Be Ship</button>
                                    <button type="button" class="cancel btn btn-danger btn-sm"
                                        value="{{ $order->id }}">Cancel</button>
                                @elseif($order->status == App\Models\Order::STATUS_SHIPPED)
                                    <button type="button" class="delivered btn btn-success btn-sm"
                                        value="{{ $order->id }}">Delivered</button>
                                    <button type="button" class="cancel btn btn-danger btn-sm"
                                        value="{{ $order->id }}">Cancel</button>
                                @elseif($order->status == App\Models\Order::STATUS_CANCELED)
                                    <button class="btn btn-warning btn-sm showReason"
                                        value="{{ $order->reason }}">Reason</button>
                                @else
                                    DONE
                                @endif

                            </td>
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
        <!--/ Basic Bootstrap Table -->
    @endsection


    @section('scripts')
        <script>
            $(document).ready(function() {


                $('#search').on('keyup', function() {
                    const value = $('#search').val();
                    console.log(value)
                    if (value.length < 1) {
                        location.href = 'order'
                    }
                });


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $(document).on('click', '.shipped', function(e) {
                    e.preventDefault();
                    var order_id = $(this).val();
                    console.log(order_id)
                    $('#order_id').val(order_id)
                    $('#shippedModal').modal('show')
                })

                $(document).on('click', '.be_shipped', function(e) {
                    e.preventDefault();

                    var order_id = $('#order_id').val();
                    console.log(order_id)
                    $.ajax({
                        type: 'POST',
                        url: 'be_shipped/' + order_id,
                        success: function(response) {
                            console.log(response);
                            $('#success_message').addClass('alert alert-success')
                            $('#success_message').text(response.message)
                            $('#shippedModal').modal('hide')
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                    })
                })

                $(document).on('click', '.cancel', function(e) {
                    e.preventDefault();
                    var order_id = $(this).val();
                    console.log(order_id)
                    $('#order_id').val(order_id)
                    $('#cancelModel').modal('show')
                });

                $(document).on('click', '.be_cancel', function(e) {
                    e.preventDefault();

                    var order_id = $('#order_id').val();
                    var data = {
                        'reason': $('.reason').val(),
                    }

                    $.ajax({
                        type: 'POST',
                        url: '/canceled/' + order_id,
                        data: data,
                        dataType: 'json',
                        success: function(response) {
                            console.log(response)

                            if (response.status == 400) {
                                const reason = $('#reason').val();
                                reason == '' ? $('#error_reason').html(response.errors.reason) : $(
                                    '#error_reason').html('')
                            } else if (response.status == 404) {
                                $('#update_error_message').html('');
                                $('#update_error_message').addClass('alert alert-danger');
                                $('#update_error_message').text('response.message');
                                $('.update_user').text('Update')
                            } else {
                                $('#success_message').text(response.message)
                                $('#success_message').addClass('alert alert-success')
                                $('#success_message').text(response.message)
                                $('#cancelModel').modal('hide')
                                $('#cancelModel').find('input').val('')
                                $('.update_user').text('Update')
                                // fetchUser()
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                        }
                    })
                })

                $(document).on('click', '.delivered', function(e) {
                    e.preventDefault();
                    var order_id = $(this).val();
                    console.log(order_id)
                    $('#order_id').val(order_id)
                    $('#deliveredModal').modal('show')
                })

                $(document).on('click', '.be_delivered', function(e) {
                    e.preventDefault();

                    var order_id = $('#order_id').val();
                    console.log(order_id)
                    $.ajax({
                        type: 'POST',
                        url: 'delivered/' + order_id,
                        success: function(response) {
                            console.log(response);
                            $('#success_message').addClass('alert alert-success')
                            $('#success_message').text(response.message)
                            $('#deliveredModal').modal('hide')
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);

                        }
                    })
                })

                $(document).on('click', '.showReason', function(e) {
                    e.preventDefault();
                    $('#showReasonModal').modal('show')
                    var reason = $(this).val();
                    console.log(reason);
                    $('#showReason').html(reason);
                })


            });
        </script>
    @endsection
