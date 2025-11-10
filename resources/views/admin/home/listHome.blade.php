@extends('admin.layouts.app')



@section('content')
    <div class="content-wrapper">
        <!-- Content -->
        <div class="row">

            <div class="col-lg-4 col-md-4 col-sm-12 mb-4 ">
                <div class="card mx-auto ">
                    <div class="card-body mx-auto">
                        <div class="d-flex justify-content-between flex-sm-row flex-column gap-3 mx-auto">
                            <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between mx-auto">
                                <div class="card-title text-center mx-auto">
                                    <h4 class="text-nowrap mb-2">Users Numbers</h4>

                                </div>
                                <div class="mt-sm-auto mx-auto">

                                    <h5 class="mb-0 "> <span
                                            class="badge bg-label-warning rounded-pill px-5 mx-5">{{ $users }}</span>
                                    </h5>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>




            <div class="col-lg-4 col-md-4 col-sm-12 mb-4 mx-auto">
                <div class="card mx-auto">
                    <div class="card-body mx-auto">
                        <div class="d-flex justify-content-between flex-sm-row flex-column gap-3 mx-auto">
                            <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between mx-auto">
                                <div class="card-title text-center mx-auto">
                                    <h4 class="text-nowrap mb-2">Products Numbers</h4>

                                </div>
                                <div class="mt-sm-auto mx-auto">

                                    <h5 class="mb-0 "> <span
                                            class="badge bg-label-warning rounded-pill px-5 mx-5">{{ $products }}</span>
                                    </h5>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-4 col-md-4 col-sm-12 mb-4 ">
                <div class="card mx-auto">
                    <div class="card-body mx-auto">
                        <div class="d-flex justify-content-between flex-sm-row flex-column gap-3 mx-auto">
                            <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between mx-auto">
                                <div class="card-title text-center mx-auto">
                                    <h4 class="text-nowrap mb-2">Orders Numbers</h4>

                                </div>
                                <div class="mt-sm-auto mx-auto">

                                    <h5 class="mb-0 "><span class="badge bg-label-warning rounded-pill px-5 mx-5">
                                            {{ $orders }}</span>
                                    </h5>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>




        </div>
        {{-- <div class="card col-lg-12  mb-4">
            <canvas id="myChart" class="mx-auto" style="width:100%;max-width:100%"></canvas>

        </div> --}}
        <div class="row ">


            <div class="card col-lg-12  mb-4 ">
                <div class="d-flex justify-content-between  items-center">
                    <h5 class="card-header">Most Uploading Stores</h5>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table text-center" id="table_id" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Store Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Number Of Products</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mostuploadingstores as $key => $mostuploadingstore)
                                <tr>
                                    <td><a href="/userDetail/{{ $mostuploadingstore->id }}"
                                            class="btn btn-info btn-sm">{{ $mostuploadingstore->id }}</a></td>
                                    <td>{{ $mostuploadingstore->store_name }}</td>
                                    <td>{{ $mostuploadingstore->email }}</td>
                                    <td>{{ $mostuploadingstore->phone }}</td>
                                    <td>{{ $mostuploadingstore->product_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card col-lg-12  mb-4 ">
                <div class="d-flex justify-content-between  items-center">
                    <h5 class="card-header">Most Ordering Users</h5>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table text-center" id="table_id" style="width: 100%">
                        <thead>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Number Of Order</th>
                        </thead>
                        <tbody>

                            @foreach ($mostorderingusers as $key => $mostorderinguser)
                                <tr>
                                    <td><a href="/userDetail/{{ $mostorderinguser->id }}" class="btn btn-info btn-sm">
                                            {{ $mostorderinguser->id }}</a></td>
                                    <td>{{ $mostorderinguser->first_name }}</td>
                                    <td>{{ $mostorderinguser->last_name }}</td>
                                    <td>{{ $mostorderinguser->email }}</td>
                                    <td>{{ $mostorderinguser->phone }}</td>
                                    <td>{{ $mostorderinguser->orders_count }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card col-lg-12  mb-4 ">
                <div class="d-flex justify-content-between  items-center">
                    <h5 class="card-header">Most Stores getting Orders</h5>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table text-center" id="table_id" style="width: 100%">
                        <thead>
                            <th>ID</th>
                            <th>Store Name</th>

                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Number Of Order</th>
                        </thead>
                        <tbody>

                            @foreach ($mostgetingorderstores as $key => $mostorderinguser)
                                <tr>
                                    <td><a href="/userDetail/{{ $mostorderinguser->id }}" class="btn btn-info btn-sm">
                                            {{ $mostorderinguser->id }}</a></td>
                                    <td>{{ $mostorderinguser->store_name }}</td>
                                    <td>{{ $mostorderinguser->email }}</td>
                                    <td>{{ $mostorderinguser->phone }}</td>
                                    <td>{{ $mostorderinguser->orders_count }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>


            {{--     <div class="card col-lg-12  mb-4 ">
                <div class="d-flex justify-content-between  items-center">
                    <h5 class="card-header">Most Uploading Individual</h5>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table text-center" id="table_id" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Number Of Products</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mostuploadingindividuals as $key => $mostuploadingindividual)
                                <tr>
                                    <td><a href="/individualDetail/{{ $mostuploadingindividual->user->id }}"
                                            class="btn btn-info btn-sm">
                                            {{ $mostuploadingindividual->user->id }}</a></td>
                                    <td>{{ $mostuploadingindividual->user->first_name }}</td>
                                    <td>{{ $mostuploadingindividual->user->last_name }}</td>
                                    <td>{{ $mostuploadingindividual->user->email }}</td>
                                    <td>{{ $mostuploadingindividual->user->phone_number }}</td>
                                    <td>{{ $mostuploadingindividual->count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card col-lg-12  mb-4 ">
                <div class="d-flex justify-content-between  items-center">
                    <h5 class="card-header">Most Active Individual On Bids</h5>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table text-center" id="table_id" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Number Of Bids</th>
                            </tr>
                        </thead>



                        <tbody>
                            @foreach ($mostactiveindividualonbids as $key => $mostactiveindividualonbid)
                                <tr>
                                    <td><a href="/individualDetail/{{ $mostactiveindividualonbid->user_id }}"
                                            class="btn btn-info btn-sm">
                                            {{ $mostactiveindividualonbid->user_id }}</a></td>
                                    <td>{{ $mostactiveindividualonbid->first_name }}</td>
                                    <td>{{ $mostactiveindividualonbid->last_name }}</td>
                                    <td>{{ $mostactiveindividualonbid->email }}</td>
                                    <td>{{ $mostactiveindividualonbid->phone_number }}</td>
                                    <td>{{ $mostactiveindividualonbid->count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card col-lg-12  mb-4 ">
                <div class="d-flex justify-content-between  items-center">
                    <h5 class="card-header">Most Active Individual On swap</h5>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table text-center" id="table_id" style="width: 100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Number Of swaps</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mostactiveindividualonswaps as $key => $mostactiveindividualonswap)
                                <tr>
                                    <td><a href="/individualDetail/{{ $mostactiveindividualonswap->user_id }}"
                                            class="btn btn-info btn-sm">
                                            {{ $mostactiveindividualonswap->user_id }}</a></td>
                                    <td>{{ $mostactiveindividualonswap->first_name }}</td>
                                    <td>{{ $mostactiveindividualonswap->last_name }}</td>
                                    <td>{{ $mostactiveindividualonswap->email }}</td>
                                    <td>{{ $mostactiveindividualonswap->phone_number }}</td>
                                    <td>{{ $mostactiveindividualonswap->count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card col-lg-12  mb-4 ">
                <div class="d-flex justify-content-between  items-center">
                    <h5 class="card-header">Most Stores getting Orders</h5>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table text-center" id="table_id" style="width: 100%">
                        <thead>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Number Of Order</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>



            <div class="card col-lg-12  mb-4 ">
                <div class="d-flex justify-content-between  items-center">
                    <h5 class="card-header">Most Boosting Individual</h5>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table text-center" id="table_id" style="width: 100%">
                        <thead>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Number Of Boost</th>
                        </thead>
                        <tbody>
                            @foreach ($mostboostingindividuals as $key => $mostboostingindividual)
                            @if ($mostboostingindividual->boosts_count > 0)
                                <tr>
                                    <td><a href="/individualDetail/{{ $mostboostingindividual->user_id }}"
                                            class="btn btn-info btn-sm">
                                            {{ $mostboostingindividual->id }}</a></td>
                                    <td>{{ $mostboostingindividual->first_name }}</td>
                                    <td>{{ $mostboostingindividual->last_name }}</td>
                                    <td>{{ $mostboostingindividual->email }}</td>
                                    <td>{{ $mostboostingindividual->phone_number }}</td>
                                    <td>{{ $mostboostingindividual->boosts_count }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card col-lg-12  mb-4 ">
                <div class="d-flex justify-content-between  items-center">
                    <h5 class="card-header">Most Boosting Stores</h5>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table text-center" id="table_id" style="width: 100%">
                        <thead>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Number Of Boost</th>
                        </thead>
                        <tbody>
                            @foreach ($mostboostingstores as $key => $mostboostingstore)
                                <tr>
                                    <td><a href="/sotreDetail/{{ $mostboostingstore->user_id }}"
                                            class="btn btn-info btn-sm">
                                            {{ $mostboostingstore->user_id }}</a></td>
                                    <td>{{ $mostboostingstore->user->first_name }}</td>
                                    <td>{{ $mostboostingstore->user->last_name }}</td>
                                    <td>{{ $mostboostingstore->user->email }}</td>
                                    <td>{{ $mostboostingstore->user->phone_number }}</td>
                                    <td>{{ $mostboostingstore->count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div> --}}

        </div>



        <!-- / Content -->
    @endsection

    {{-- @section('scripts')
    <script>
        var xValues = [100, 200, 300, 400, 500, 600, 700, 800, 900, 1000];

        new Chart("myChart", {
            type: "line",
            data: {
                labels: xValues,
                datasets: [{
                    data: [860, 1140, 1060, 1060, 1070, 1110, 1330, 2210, 7830, 2478],
                    borderColor: "red",
                    fill: false
                }, {
                    data: [1600, 1700, 1700, 1900, 2000, 2700, 4000, 5000, 6000, 7000],
                    borderColor: "green",
                    fill: false
                }, {
                    data: [300, 700, 2000, 5000, 6000, 4000, 2000, 1000, 200, 100],
                    borderColor: "blue",
                    fill: false
                }]
            },
            options: {
                legend: {
                    display: false
                }
            }
        });
    </script>
@endsection --}}
