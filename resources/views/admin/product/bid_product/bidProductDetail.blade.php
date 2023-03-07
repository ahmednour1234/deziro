@extends('admin.layouts.app')

{{-- @yield('title', 'Product') --}}

@section('content')
    @include('admin.product.request_product.status_modal.approveProductModal')
    @include('admin.product.request_product.status_modal.rejectProductModal')
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap");


        body {

            background-color: #eee;
            font-family: "Poppins", sans-serif
        }

        .card {
            background-color: #fff;
            padding: 14px;
            border: none
        }

        .demoo {
            width: 100%
        }

        ul {
            list-style: none outside none;
            padding-left: 0;
            margin-bottom: 0
        }

        li {
            display: block;
            float: left;
            margin-right: 6px;
            cursor: pointer
        }

        img {
            display: block;
            height: auto;
            width: 100%
        }

        .stars i {
            color: #f6d151
        }

        .stars span {
            font-size: 13px
        }

        hr {
            color: #d4d4d4
        }

        .badge {
            padding: 5px !important;
            padding-bottom: 6px !important
        }

        .badge i {
            font-size: 10px
        }

        .profile-image {
            width: 35px
        }

        .comment-ratings i {
            font-size: 13px
        }

        .username {
            font-size: 12px
        }

        .comment-profile {
            line-height: 17px
        }

        .date span {
            font-size: 12px
        }

        .p-ratings i {
            color: #f6d151;
            font-size: 12px
        }

        .btn-long {
            padding-left: 35px;
            padding-right: 35px
        }

        .buttons {
            margin-top: 15px
        }

        .buttons .btn {
            height: 46px
        }

        .buttons .cart {
            border-color: #ff7676;
            color: #ff7676
        }

        .buttons .cart:hover {
            background-color: #e86464 !important;
            color: #fff
        }

        .buttons .buy {
            color: #fff;
            background-color: #ff7676;
            border-color: #ff7676
        }

        .buttons .buy:focus,
        .buy:active {
            color: #fff;
            background-color: #ff7676;
            border-color: #ff7676;
            box-shadow: none
        }

        .buttons .buy:hover {
            color: #fff;
            background-color: #e86464;
            border-color: #e86464
        }

        .buttons .wishlist {
            background-color: #fff;
            border-color: #ff7676
        }

        .buttons .wishlist:hover {
            background-color: #e86464;
            border-color: #e86464;
            color: #fff
        }

        .buttons .wishlist:hover i {
            color: #fff
        }

        .buttons .wishlist i {
            color: #ff7676
        }

        .comment-ratings i {
            color: #f6d151
        }

        .followers {
            font-size: 9px;
            color: #d6d4d4
        }

        .store-image {
            width: 42px
        }

        .dot {
            height: 10px;
            width: 10px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px
        }

        .bullet-text {
            font-size: 12px
        }

        .my-color {
            margin-top: 10px;
            margin-bottom: 10px
        }

        label.radio {
            cursor: pointer
        }

        label.radio input {
            position: absolute;
            top: 0;
            left: 0;
            visibility: hidden;
            pointer-events: none
        }

        label.radio span {
            border: 2px solid #8f37aa;
            display: inline-block;
            color: #8f37aa;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            text-transform: uppercase;
            transition: 0.5s all
        }

        label.radio .red {
            background-color: red;
            border-color: red
        }

        label.radio .blue {
            background-color: blue;
            border-color: blue
        }

        label.radio .green {
            background-color: green;
            border-color: green
        }

        label.radio .orange {
            background-color: orange;
            border-color: orange
        }

        label.radio input:checked+span {
            color: #fff;
            position: relative
        }

        label.radio input:checked+span::before {
            opacity: 1;
            content: '\2713';
            position: absolute;
            font-size: 13px;
            font-weight: bold;
            left: 4px
        }

        .card-body {
            padding: 0.3rem 0.3rem 0.2rem
        }
    </style>
    <link rel='stylesheet' href='https://sachinchoolur.github.io/lightslider/dist/css/lightslider.css'>
    <div class="container-fluid mt-2 mb-3">
        <div class="row no-gutters">
            <div class="col-md-5 pr-2">
                <div class="card">
                    <div class="demoo">

                        <ul id="lightSlider">
                            @foreach ($imageProduct as $image)
                                <!--<li data-thumb="{{ asset('admin/assets/img/product_image/' . $image->product_image) }}"> <img-->
                                <!--        src="{{ asset('admin/assets/img/product_image/' . $image->product_image) }}"-->
                                <!--        width="100%" height="300" />-->
                                <!--</li>-->
                                <li data-thumb="{{ asset($image->product_image) }}" width="100%" height="300"> <img
                                        src="{{ asset($image->product_image) }}" width="100%" height="300" />



                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
            <div class="col-md-7">
                <div class="card mb-12">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-0">User Id</p>
                            </div>
                            <div class="col-sm-6">
                                <div class="col-sm-6">
                                    <a href="/individualDetail/{{ $productDetail->user_id }}" class="btn btn-dark btn-sm">
                                        {{ $productDetail->user_id }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-0">User Name</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-muted mb-0">
                                    {{ $productDetail->user->first_name . ' ' . $productDetail->user->last_name }}</p>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-0">User Email</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-muted mb-0">
                                    {{ $productDetail->user->email }}</p>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-0">User Phone Number</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-muted mb-0">
                                    {{ $productDetail->user->phone_number }}</p>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-0">Product Id</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-muted mb-0">
                                    {{ $productDetail->id }}</p>
                            </div>
                        </div>
                        <hr>


                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-0">Product Name</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-muted mb-0">
                                    {{ $productDetail->name }}</p>
                            </div>
                        </div>
                        <hr>
                        {{-- <input type="text" id="store_id" value="{{ $productDetail->id }}"> --}}
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-0">Sub Category </p>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-muted mb-0">{{ $productDetail->category->name }}</p>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-0">Starting Price</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-muted mb-0">{{ $productDetail->bid_starting_price }}</p>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-0">last bid Price</p>
                            </div>
                            <div class="col-sm-6">
                                @if($bidProduct != '')
                                <p class="text-muted mb-0">{{ $bidProduct->amount }}</p>
                                @else
                                0
                                @endif

                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-0">Number of Bids</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-muted mb-0">{{ $countBids }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-0">All Bids </p>
                            </div>
                            <div class="col-sm-6">
                                <a href="/viewBidProduct/{{ $productDetail->id }}" class="btn btn-success btn-sm">View Bids</a>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-0">Condition</p>
                            </div>
                            <div class="col-sm-6">
                                {{ $productDetail->condition }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-0">Count Down</p>
                            </div>
                            <div class="col-sm-6">
                                <p data-countdown="{{ $productDetail->countdown }}"></p>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-0">Money Collection</p>
                            </div>
                            <div class="col-sm-6">
                                {{ $productDetail->money_collection }}
                            </div>
                        </div>
                        <hr>


                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-0">Description</p>
                            </div>
                            <div class="col-sm-6">
                                {{ $productDetail->description }}
                            </div>
                        </div>
                        <hr>

                        @if ($productDetail->address_id != null)
                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="mb-0">Address</p>
                                </div>
                                <div class="col-sm-6">

                                    {{ $productDetail->address->city . ' , ' . $productDetail->address->address . ' , ' }}
                                    <a href="http://maps.google.com/?q={{ $productDetail->address->lat }},{{ $productDetail->address->lng }}"
                                        target="_blank"> Go To Map</a></p>

                                </div>
                            </div>
                        @endif
                    </div>

                </div>
                <div class="text-center my-3">
                    @if ($productDetail->status == 'pending')
                        <button type="button" value="{{ $productDetail->id }}"
                            class="approve_product  btn btn-primary editbtn  ">Approve</button>
                        <button type="button" value="{{ $productDetail->id }}"
                            class="reject_product btn btn-danger deletebtn  ">Reject</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'></script>
    <script src='https://sachinchoolur.github.io/lightslider/dist/js/lightslider.js'></script>
    <script>
        $('#lightSlider').lightSlider({
            gallery: true,
            item: 1,
            loop: true,
            slideMargin: 0,
            thumbItem: 9
        });
    </script>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {


            $('[data-countdown]').each(function() {
                var $this = $(this),
                    finalDate = $(this).data('countdown');
                $this.countdown(finalDate, function(event) {
                    $this.html(event.strftime('%D:%H:%M:%S'));
                });
            });


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.approve_product', function(e) {
                e.preventDefault();
                var product_id = $(this).val();
                console.log(product_id)
                $('#approve_id').val(product_id)
                $('#approveProductModal').modal('show')
            });

            $(document).on('click', '.reject_product', function(e) {
                e.preventDefault();
                var product_id = $(this).val();
                console.log(product_id)
                $('#reject_id').val(product_id)
                $('#rejectProductModal').modal('show')
            });

            $(document).on('click', '.reject_btn', function(e) {
                e.preventDefault();

                var product_id = $('#reject_id').val();
                var data = {
                    'reason': $('.reason').val(),
                }

                $.ajax({
                    type: 'POST',
                    url: '/reject_product/' + product_id,
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
                            $('#rejectProductModal').modal('hide')
                            $('#editStoreModal').find('input').val('')
                            $('.update_user').text('Update')
                            // fetchUser()
                            location.reload();

                        }
                    }
                })

            })

            $(document).on('click', '.approve_btn', function(e) {
                e.preventDefault();

                var product_id = $('#approve_id').val();
                let formData = new FormData($('#approveProductForm')[0]);
                console.log(product_id)

                $.ajax({
                    type: 'POST',
                    url: '/approve_product/' + product_id,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response)

                        if (response.status == 404) {
                            $('#update_error_message').html('');
                            $('#update_error_message').addClass('alert alert-danger');
                            $('#update_error_message').text('response.message');
                            $('.update_user').text('Update')
                        } else {
                            $('#success_message').text(response.message)
                            $('#success_message').addClass('alert alert-success')
                            $('#success_message').text(response.message)
                            $('#approveProductModal').modal('hide')
                            $('#approveProductModal').find('input').val('')
                            $('.update_user').text('Update')
                            // fetchUser()
                            location.reload();

                        }
                    }
                })

            })
        });
    </script>
@endsection
