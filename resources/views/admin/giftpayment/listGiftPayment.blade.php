@extends('admin.layouts.app')


@section('content')
    @include('admin.giftpayment.action.acceptGiftPaymentModal')
    @include('admin.giftpayment.action.rejectGiftPaymentModal')
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
                                <option value="pending" {{ request()->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="accept" {{ request()->status == 'accept' ? 'selected' : '' }}>Accept</option>
                                <option value="reject" {{ request()->status == 'reject' ? 'selected' : '' }}>Reject</option>

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
                        <span>Showing {{ $listGiftPayments->firstItem() }} to {{ $listGiftPayments->lastItem() }}
                            of total {{ $listGiftPayments->total() }} entries</span>
                    </div>

                </div>
            </nav>
            <table class="table  " id="table_id" style="width: 100% ">
                <thead class="text-center">
                  <tr>
                    <th>date</th>
                    <th>sender </th>
                    <th>receiver</th>
                    <th>amount</th>
                    <th>payment methode</th>
                    <th>status</th>
                    <th>Payment detail</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>

                    @foreach ($listGiftPayments as $key =>$giftpayment)
                        <tr style="text-align: center">
                            <td>{{$giftpayment->updated_at->format('Y-m-d ') }}</td>
                            <td>{{$giftpayment->sender->getuserFullNameAttribute() }}</td>
                            <td>{{$giftpayment->receiver->getuserFullNameAttribute() }}</td>
                            <td>{{$giftpayment->amount }}</td>
                         <td>{{$giftpayment->payment_method }}</td>
                            <td>{{$giftpayment->status }}</td>
                            <td><a href="/orderDetail/{{$giftpayment->id }}" class="btn btn-sm btn-info"> View More
                                    Details</a></td>
                            <td>
                                @if ($giftpayment->status == 'pending')
                                    <button type="button" class="accept btn btn-primary btn-sm"
                                        value="{{$giftpayment->id }}">accept</button>
                                    <button type="button" class="reject btn btn-danger btn-sm"
                                        value="{{$giftpayment->id }}">reject</button>
                                @elseif($giftpayment->status == 'accept')
                                    <button type="button" class="btn btn-success btn-sm"
                                       >Accepted</button>
                                @elseif($giftpayment->status == 'reject')
                                    <button class="btn btn-warning btn-sm showReason"
                                        value="{{$giftpayment->reason }}">Reason</button>
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
                    {{ $listGiftPayments->links() }}
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
                        location.href = 'giftpayments'
                    }
                });


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $(document).on('click', '.accept', function(e) {
                    e.preventDefault();
                    var giftpayment_id = $(this).val();
                    console.log(giftpayment_id)
                    $('#giftpayment_id').val(giftpayment_id)
                    $('#acceptModal').modal('show')
                })

                $(document).on('click', '.be_accept', function(e) {
                    e.preventDefault();

                    var giftpayment_id = $('#giftpayment_id').val();
                    console.log(giftpayment_id)
                    $.ajax({
                        type: 'POST',
                        url: 'be_accept/' + giftpayment_id,
                        success: function(response) {
                            console.log(response);
                            $('#success_message').addClass('alert alert-success')
                            $('#success_message').text(response.message)
                            $('#acceptModal').modal('hide')
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                    })
                })

                $(document).on('click', '.reject', function(e) {
                    e.preventDefault();
                    var giftpayment_id = $(this).val();
                    console.log(giftpayment_id)
                    $('#giftpayment_id').val(giftpayment_id)
                    $('#rejectModel').modal('show')
                });

                $(document).on('click', '.be_reject', function(e) {
                    e.preventDefault();

                    var giftpayment_id = $('#giftpayment_id').val();
                    var data = {
                        'reason': $('.reason').val(),
                    }

                    $.ajax({
                        type: 'POST',
                        url: '/reject/' + giftpayment_id,
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
                                $('#rejectModel').modal('hide')
                                $('#rejectModel').find('input').val('')
                                $('.update_user').text('Update')
                                // fetchUser()
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
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
