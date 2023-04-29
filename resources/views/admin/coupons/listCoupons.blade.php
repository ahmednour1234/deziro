@extends('admin.layouts.app')

@section('content')
    @include('admin.coupons.crud_modal.addCouponModal')
    @include('admin.coupons.crud_modal.editCouponModal')
    @include('admin.moreDetails.activate_modal.activeModal')
    @include('admin.moreDetails.activate_modal.inactiveModal')

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Promo Code
    </h4>


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
                        data-bs-target="#addCouponModal">
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
                        <span>Showing {{ $listCoupons->firstItem() }} to {{ $listCoupons->lastItem() }}
                            of total {{ $listCoupons->total() }} entries</span>
                    </div>
                </div>
            </nav>
            <table class="table  " id="table_id" style="width: 100%" class="text-center">
                <thead class="text-center">
                    <tr>
                        <th><a class="text-dark"
                                href="{{ route('admin.coupon.listCoupons', ['sort' => 'created_at', 'direction' => $sortColumn == 'created_at' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                Created At
                                @if ($sortColumn == 'created_at')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th><a class="text-dark"
                                href="{{ route('admin.coupon.listCoupons', ['sort' => 'code', 'direction' => $sortColumn == 'code' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                Code
                                @if ($sortColumn == 'code')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th><a class="text-dark"
                                href="{{ route('admin.coupon.listCoupons', ['sort' => 'description', 'direction' => $sortColumn == 'description' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                Description
                                @if ($sortColumn == 'description')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>

                        <th><a class="text-dark"
                                href="{{ route('admin.coupon.listCoupons', ['sort' => 'is_percentage', 'direction' => $sortColumn == 'is_percentage' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                Discount Type
                                @if ($sortColumn == 'is_percentage')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th><a class="text-dark"
                                href="{{ route('admin.coupon.listCoupons', ['sort' => 'discount_value', 'direction' => $sortColumn == 'discount_value' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                Discount Value
                                @if ($sortColumn == 'discount_value')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th><a class="text-dark"
                                href="{{ route('admin.coupon.listCoupons', ['sort' => 'min_order_amount', 'direction' => $sortColumn == 'min_order_amount' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                Minimum Order Amount
                                @if ($sortColumn == 'min_order_amount')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th><a class="text-dark"
                                href="{{ route('admin.coupon.listCoupons', ['sort' => 'usage_limit_per_coupon', 'direction' => $sortColumn == 'usage_limit_per_coupon' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                Max Orders
                                @if ($sortColumn == 'usage_limit_per_coupon')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th><a class="text-dark"
                                href="{{ route('admin.coupon.listCoupons', ['sort' => 'usage_limit_per_user', 'direction' => $sortColumn == 'usage_limit_per_user' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                Max order same user
                                @if ($sortColumn == 'usage_limit_per_user')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th><a class="text-dark"
                                href="{{ route('admin.coupon.listCoupons', ['sort' => 'expiry_date', 'direction' => $sortColumn == 'expiry_date' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                Expiry Date
                                @if ($sortColumn == 'expiry_date')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>

                        <th><a class="text-dark"
                                href="{{ route('admin.coupon.listCoupons', ['sort' => 'status', 'direction' => $sortColumn == 'status' && $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                                Status
                                @if ($sortColumn == 'status')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="text-center">

                    @foreach ($listCoupons as $key => $coupon)
                        <tr>
                            <td>{{ $coupon->created_at->format('Y-m-d') }}</td>
                            <td>{{ $coupon->code }} </td>
                            <td>{{ $coupon->description }}</td>
                            <td>{{ $coupon->is_percentage == 1 ? '%' : '$' }}</td>
                            <td>{{ $coupon->discount_value }} </td>
                            <td>{{ $coupon->min_order_amount }} $</td>
                            <td>{{ $coupon->usage_limit_per_coupon }}</td>
                            <td>{{ $coupon->usage_limit_per_user }}</td>
                            <td>{{ $coupon->expiry_date }}</td>

                            <td>
                                @if ($coupon->status == 'active')
                                    <button type="button" value="{{ $coupon->id }}" data-value1="{{ $coupon->code }}"
                                        class=" active_coupon btn btn-success  btn-sm ">Active</button>
                                @else
                                    <button type="button" value="{{ $coupon->id }}" data-value1="{{ $coupon->code }}"
                                        class="inactive_coupon  btn btn-danger  btn-sm ">Inactive</button>
                                @endif
                            </td>
                            <td>
                                <button type="button" value="{{ $coupon->id }}"
                                    data-value1="{{ $coupon->first_name }}"
                                    class="edit_coupon  btn btn-warning editbtn btn-sm ">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $listCoupons->links() }}
                </div>
            </div>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->
@endsection


@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#search').on('keyup', function() {
                const z = $('#search').val();
                console.log(z)
                if (z.length < 1) {
                    location.href = '/coupon'
                }
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //Edit coupon
            $(document).on('click', '.edit_coupon', function(e) {
                e.preventDefault();
                var coupon_id = $(this).val();
                console.log(coupon_id)
                $('#editCouponModal').modal('show')
                $.ajax({
                    type: 'GET',
                    url: 'editCoupon/' + coupon_id,
                    success: function(response) {
                        console.log(response);
                        if (response.status == 404) {
                            $('#success_message').html("")
                            $('#success_message').addClass('alert alert-danger')
                            $('#success_message').text(response.message)
                        } else {
                            $('#edit_id').val(response.coupon.id)
                            $('#edit_code').val(response.coupon.code)
                            $('#edit_description').val(response.coupon.description)
                            $('#edit_is_percentage').val(response.coupon.is_percentage)
                            $('#edit_discount_value').val(response.coupon.discount_value)
                            $('#edit_min_order_amount').val(response.coupon.min_order_amount)
                            $('#edit_usage_limit_per_coupon').val(response.coupon.usage_limit_per_coupon)
                            $('#edit_usage_limit_per_user').val(response.coupon.usage_limit_per_user)
                            $('#edit_expiry_date').val(response.coupon.expiry_date)

                            $('#error_edit_code').html('')
                            $('#error_edit_description').html('')
                            $('#error_edit_is_percentage').html('')
                            $('#error_edit_discount_value').html('')
                            $('#error_edit_min_order_amount').html('')
                            $('#error_edit_usage_limit_per_coupon').html('')
                            $('#error_edit_usage_limit_per_user').html('')
                            $('#error_edit_expiry_date').html('')
                            $('.update_coupon').text('Update')
                        }
                    }

                })
            })

            //Update coupon
            $(document).on('click', '.update_coupon', function(e) {
                e.preventDefault();
                var coupon_id = $('#edit_id').val();
                $.ajax({
                    type: "POST",
                    url: "/updateCoupon/" + coupon_id,
                    data: $('#UpdateCouponForm').serialize(),
                    dataType: 'json',
                    success: function(response) {
                        console.log(response)
                        if (response.status == 400) {
                            response.errors.code != undefined ? $('#error_edit_code')
                                .html(response.errors.code) : $('#error_edit_code').html(
                                    '')
                            response.errors.description != undefined ? $('#error_edit_description')
                                .html(
                                    response.errors.description) : $('#error_edit_description').html(
                                    '')
                            response.errors.is_percentage != undefined ? $(
                                '#error_edit_is_percentage').html(response
                                .errors.is_percentage) : $('#error_edit_is_percentage').html('')
                            response.errors.discount_value != undefined ? $(
                                '#error_edit_discount_value').html(
                                response
                                .errors.discount_value) : $('#error_edit_discount_value').html(
                                '')
                            response.errors.min_order_amount != undefined ? $(
                                '#error_edit_min_order_amount').html(
                                response.errors.min_order_amount) : $(
                                '#error_edit_min_order_amount').html('')
                            response.errors.expiry_date != undefined ? $(
                                '#error_edit_expiry_date').html(
                                response.errors.expiry_date) : $(
                                '#error_edit_expiry_date').html('')

                            response.errors.usage_limit_per_coupon != undefined ? $(
                                '#error_edit_usage_limit_per_coupon').html(
                                response.errors.usage_limit_per_coupon) : $(
                                '#error_edit_usage_limit_per_coupon').html('')

                            response.errors.usage_limit_per_user != undefined ? $(
                                '#error_edit_usage_limit_per_user').html(
                                response.errors.usage_limit_per_user) : $(
                                '#error_edit_usage_limit_per_user').html('')

                        } else {
                            $('#success_message').text(response.message)
                            $('#success_message').addClass('alert alert-success')
                            $('#editCouponModal').modal('hide')

                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);

                        }
                    }
                })
            })

            //Add coupon
            $('.add_coupon').click(function(e) {

                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "/addNewCoupon",
                    data: $('#AddCouponForm').serialize(),
                    dataType: 'json',
                    success: function(response) {
                        console.log(response)

                        if (response.status == 400) {
                            response.errors.code != undefined ? $('#error_code')
                                .html(response.errors.code) : $('#error_code').html(
                                    '')
                            response.errors.description != undefined ? $('#error_description')
                                .html(
                                    response.errors.description) : $('#error_description').html(
                                    '')
                            response.errors.is_percentage != undefined ? $(
                                '#error_is_percentage').html(response
                                .errors.is_percentage) : $('#error_is_percentage').html('')
                            response.errors.discount_value != undefined ? $(
                                '#error_discount_value').html(
                                response
                                .errors.discount_value) : $('#error_discount_value').html(
                                '')
                            response.errors.min_order_amount != undefined ? $(
                                '#error_min_order_amount').html(
                                response.errors.min_order_amount) : $(
                                '#error_min_order_amount').html('')
                            response.errors.expiry_date != undefined ? $(
                                '#error_expiry_date').html(
                                response.errors.expiry_date) : $(
                                '#error_expiry_date').html('')

                            response.errors.usage_limit_per_coupon != undefined ? $(
                                '#error_usage_limit_per_coupon').html(
                                response.errors.usage_limit_per_coupon) : $(
                                '#error_usage_limit_per_coupon').html('')

                            response.errors.usage_limit_per_user != undefined ? $(
                                '#error_usage_limit_per_user').html(
                                response.errors.usage_limit_per_user) : $(
                                '#error_usage_limit_per_user').html('')
                        } else {
                            $('#success_message').text(response.message)
                            $('#success_message').addClass('alert alert-success')
                            $('#addCouponModal').modal('hide')
                            $('#AddCouponForm')[0].reset();
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                    }
                })
            })


            $(document).on('click', '.active_coupon', function(e) {
                e.preventDefault();
                var name = $(this).data('value1');
                var user_id = $(this).val();
                $('#active_id').val(user_id)
                $('#inactive_title').text('Inactivate  ' + name)
                $('#inactive_msg').text('Are you sure do you want to inactivate  ' + name)
                $('#activeModal').modal('show')
            })

            $(document).on('click', '.inactive_coupon', function(e) {
                e.preventDefault();
                var name = $(this).data('value1');
                var user_id = $(this).val();
                $('#inactive_id').val(user_id)
                $('#active_title').text('Activate  ' + name)
                $('#active_msg').text('Are you sure do you want to activate  ' + name)
                $('#inactiveModal').modal('show')
            })


            $(document).on('click', '.is_active', function(e) {
                e.preventDefault();

                var coupon_id = $('#active_id').val();
                console.log(coupon_id)
                $.ajax({
                    type: 'POST',
                    url: 'coupon_inactive/' + coupon_id,
                    success: function(response) {
                        console.log(response);
                        $('#success_message').addClass('alert alert-success')
                        $('#success_message').text(response.message)
                        $('#activeModal').modal('hide')

                        // location.reload(true)
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                })
            })

            $(document).on('click', '.is_inactive', function(e) {
                e.preventDefault();

                var coupon_id = $('#inactive_id').val();
                console.log(coupon_id)
                $.ajax({
                    type: 'POST',
                    url: 'coupon_active/' + coupon_id,
                    success: function(response) {
                        console.log(response);
                        $('#success_message').addClass('alert alert-success')
                        $('#success_message').text(response.message)
                        $('#inactiveModal').modal('hide')

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
