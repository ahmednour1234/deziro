@extends('admin.layouts.app')

{{-- @yield('title', 'Product') --}}

@section('content')
@include('admin.moreDetails.activate_modal.approveModal')
@include('admin.moreDetails.activate_modal.rejectModal')
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
                            @foreach ($productImages as $image)
                                <!--<li data-thumb="{{ Storage::url($image->product_image) }}"> <img-->
                                <!--        src="{{ Storage::url($image->product_image) }}"-->
                                <!--        width="100%" height="300" />-->
                                <!--</li>-->
                                <li data-thumb="{{ Storage::url($image->product_image) }}" width="100%" height="300">
                                    <img src="{{ Storage::url($image->product_image) }}" width="100%" height="300" />

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
                                <a href="/userDetail/{{ $productDetail->user_id }}" class="btn btn-dark btn-sm">
                                    {{ $productDetail->user_id }}
                                </a>
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
                                    {{ $productDetail->user->phone }}</p>
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

                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-0"> Category </p>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-muted mb-0">{{ $productDetail->category->name }}</p>
                            </div>
                        </div>
                        <hr>



                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-0">Price</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-muted mb-0">{{ $productDetail->price }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-0">Special Price</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-muted mb-0">{{ $productDetail->special_price }}</p>
                            </div>
                        </div>
                        <hr>


                        <div class="row">
                            <div class="col-sm-6">
                                <p class="mb-0">Quantity</p>
                            </div>
                            <div class="col-sm-6">
                                {{ $productDetail->quantity }}
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
                    </div>

                </div>
                <div class="row mx-3 my-3">
                    <div class="d-flex ">

                        @if ($productDetail->status == 'pending')
                            <div class="text-center mx-auto">
                                <button type="button" value="{{ $productDetail->id }}"
                                    data-value1="{{ $productDetail->name }}"
                                    class="approve  btn btn-primary   ">Approve</button>
                                <button type="button" value="{{ $productDetail->id }}"
                                    data-value1="{{ $productDetail->name }}"
                                    class="reject btn btn-danger   ">Reject</button>
                            </div>
                        @else
                            <div></div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    @endsection
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
@section('scripts')
<script>
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });



        $(document).on('click', '.approve', function(e) {
            e.preventDefault();
            var name = $(this).data('value1');
            var product_id = $(this).val();
            console.log(product_id)
            $('#approve_id').val(product_id)
            $('#approve_title').text('Approve  ' + name)
            $('#approve_msg').text('Are you sure do you want to Approve  ' + name)
            $('#approveModal').modal('show')
        })


        $(document).on('click', '.approve_btn', function(e) {
            e.preventDefault();

            var product_id = $('#approve_id').val();
            console.log(product_id)
            $.ajax({
                type: 'POST',
                url: '/approve_product/' + product_id,
                success: function(response) {
                    console.log(response);
                    $('#success_message').addClass('alert alert-success')
                    $('#success_message').text(response.message)
                    $('#approveModal').modal('hide')

                    location.href = '/storeProduct'

                }
            })
        })

        $(document).on('click', '.reject', function(e) {
            e.preventDefault();
            var name = $(this).data('value1');
            var product_id = $(this).val();
            console.log(product_id)
            $('#reject_id').val(product_id)
            $('#reject_title').text('Reject  ' + name)
            $('#reject_msg').text('Are you sure do you want to Reject  ' + name)
            $('#rejectModal').modal('show')
        });

        $(document).on('click', '.reject_btn', function(e) {
            e.preventDefault();
            var product_id = $('#reject_id').val();
            console.log(product_id)
            $.ajax({
                type: 'POST',
                url: '/reject_product/' + product_id,

                success: function(response) {
                    console.log(response)
                    $('#success_message').text(response.message)
                    $('#success_message').addClass('alert alert-success')
                    $('#success_message').text(response.message)
                    $('#rejectModal').modal('hide')
                    $('#rejectModal').find('input').val('')
                    location.href = '/storeProduct'
                }
            })
        })

    })
</script>
@endsection
