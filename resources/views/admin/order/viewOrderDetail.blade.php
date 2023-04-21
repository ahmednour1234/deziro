@extends('admin.layouts.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Order Detail</h4>
    <div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">

            <div class="card mb-4">
                <div class="d-flex justify-content-between  items-center">
                    <h5 class="card-header"> {{ $order->user->first_name . ' ' . $order->user->last_name }} Detail</h5>
                </div>
                <section style="background-color: #eee;">
                    <div class="container py-5">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card mb-4">
                                    <div class="card-body text-center">
                                        @if ($order->user->image != null)
                                            <img src="{{ asset($order->user->image) }}" alt="avatar"
                                                class="rounded-circle img-fluid" style="width: 150px;">
                                        @else
                                            <img alt class=" h-auto rounded-circle" style="width: 150px;"
                                                src="data:image/jpeg;base64,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" />
                                        @endif
                                        @if ($order->user->store_name != null)
                                            <h5 class="my-3">{{ $order->user->store_name }}</h5>
                                        @endif

                                    </div>
                                </div>
                                <div class="card mb-4 mb-lg-0">
                                    <div class="card-body p-0">

                                        @if ($order->user->certificate != '')
                                            <iframe src="{{ asset($order->user->certificate) }}" width="100%"
                                                height="300">
                                            </iframe>
                                            <a href="{{ $order->user->certificate }}">Click To Open!</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">Created At</p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-muted mb-0">
                                                    {{ $order->user->created_at->format('Y-m-d d:H:i') }}</p>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">ID</p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-muted mb-0">
                                                    {{ $order->user->id }}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">First Name</p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-muted mb-0">
                                                    {{ $order->user->first_name }}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">Last Name</p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-muted mb-0">
                                                    {{ $order->user->last_name }}</p>
                                            </div>
                                        </div>
                                        <hr>



                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">Email</p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-muted mb-0">{{ $order->user->email }}</p>
                                            </div>
                                        </div>
                                        <hr>


                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0">Phone</p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-muted mb-0">{{ $order->user->phone }}</p>
                                            </div>
                                        </div>
                                        <hr>

                                        @if ($order->user->position != null)
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0">Position</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0">{{ $order->user->position }}</p>
                                                </div>
                                            </div>
                                            <hr>
                                        @endif
                                        @if ($order->user->tax_number != null)
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <p class="mb-0">Tax Number</p>
                                                </div>
                                                <div class="col-sm-9">
                                                    <p class="text-muted mb-0">{{ $order->user->tax_number }}</p>
                                                </div>
                                            </div>
                                            <hr>
                                        @endif

                                        @php
                                            // $categories = json_decode($order->user->categories)
                                            $categories = explode(',', $order->user->categories);
                                        @endphp
                                        @if ($order->user->categories != null || $order->user->categories != '')
                                            @if ($categories != '')
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <p class="mb-0">Categories</p>
                                                    </div>
                                                    <div class="col-sm-9">

                                                        @foreach ($categories as $category)
                                                            @foreach ($listCategorys as $categorys)
                                                                @if ($category == $categorys->id)
                                                                    <button
                                                                        class="btn btn-success btn-sm">{{ $categorys->name }}</button>
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <hr>
                                            @endif
                                        @endif

                                        <div class="row">
                                            <div class="col-sm-4">
                                                <p class="mb-0">Address</p>
                                            </div>
                                            <div class="col-sm-8">
                                                <p class="text-muted mb-0">
                                                    {{ $order->address?->city . ' , ' . $order->address?->address . ' , ' }} <a
                                                        href="http://maps.google.com/?q={{ $order->address?->lat }},{{ $order->address?->lng }}"
                                                        target="_blank"> Go To Map</a></p>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>

                                    </div>
                                </div>




                            </div>
                        </div>
                </section>
            </div>


            <div class="card  " style="width:100%">
                <div class="d-flex justify-content-between  items-center">
                    <div class="col-lg-3 col-md-6 mb-0 mt-3  mx-3">
                        <form action="" method="get" id="searchForm">
                            <div class="d-flex gap-3">

                                <h4>List Of Products</h4>

                            </div>

                        </form>
                    </div>

                </div>

                <div class="table-responsive text-nowrap">
                    <table class="table" id="table_id" style="width: 100%">
                        <thead class="text-center">
                            <tr>
                                <th>Created At</th>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Store Id</th>
                                <th>Cat</th>
                                <th>Brand</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>address</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($order->all_items as $item)
                                <tr>
                                    <td>{{ $item->product->created_at->format('Y-m-d ')  }}</td>

                                    @if ($item->product->type == 'sell')
                                        <td><a href="/sellingProductDetail/{{ $item->product->id }}"
                                                class="btn btn-sm btn-info"> {{ $item->product->id }} </a></td>
                                    @endif
                                    <td>{{ $item->name }} </td>
                                    <td><a href="/userDetail/{{ $item->product->user_id }} "
                                        class="btn btn-sm btn-info"> {{ $item->product->user_id }}  </a></td>
                                    <td>{{ $item->product->category->name }}</td>
                                    <td>{{ $item->product->brand->name }}</td>
                                    <td>{{ $item->qty_ordered }}</td>
                                    <td>{{ formatPrice($item->price) }}</td>
                                    {{-- <td> {{ $item->product->address->city . ' , ' . $item->product->address->address . ' , ' }}
                                        <a href="http://maps.google.com/?q={{ $item->product->address->lat }},{{ $item->product->address->lng }}"
                                            target="_blank"> Go To Map</a>
                                    </td> --}}
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="9" style="text-align: right;">
                                    <h4>Total: {{ formatPrice($order->grand_total) }}</h4>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <div class="row my-3">
                        <div class="col-lg-8 mx-2">
                            {{-- {{ $listSellingProduct->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">

                <div class="col-lg-4 mt-4 text-center">
                    <div class="card  mb-4">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <h3 class="text-center">Order Summary</h3>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-0">Sub Total</p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">
                                        {{ formatPrice($order->sub_total) }}</p>
                                </div>
                            </div>
                            <hr>


                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-0">Fees Amount</p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">
                                        {{ formatPrice($order->fees_amount) }}</p>
                                </div>
                            </div>
                            <hr>
                            {{-- <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-0">Total Quantity Ordered</p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">{{ $order->total_qty_ordered }}</p>
                                </div>
                            </div>
                            <hr> --}}
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-0">Grand Total</p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0"> {{ formatPrice($order->grand_total) }}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>








            </div>

        </div>
    </div>
@endsection
@push('scripts')
    <script>
        Fancybox.bind('[data-fancybox]', {
            Toolbar: {
                display: {
                    left: ["infobar"],
                    middle: [
                        "zoomIn",
                        "zoomOut",
                        "toggle1to1",
                        "rotateCCW",
                        "rotateCW",
                        "flipX",
                        "flipY",
                    ],
                    right: ["slideshow", "thumbs", "download", "close"],
                },
            },
        });
    </script>
@endpush
