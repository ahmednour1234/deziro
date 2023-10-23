@extends('admin.layouts.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Gift Payment Detail</h4>
    <div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">

            <div class="col-lg-6 text-center">
                <div class="card mb-4">

                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <h3 class="text-center">Sender</h3>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <p class="mb-0">ID</p>
                            </div>
                            <div class="col-sm-8">
                                    <a href="/userDetail/{{ $giftPayment->sender_id }}"
                                        class="btn btn-sm btn-info"> {{ $giftPayment->sender_id}}
                                    </a>

                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="mb-0">Full Name</p>
                            </div>
                            <div class="col-sm-8">
                                <p class="text-muted mb-0">
                                    {{ $giftPayment->sender->getuserFullNameAttribute() }}
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="mb-0">Email</p>
                            </div>
                            <div class="col-sm-8">
                                <p class="text-muted mb-0">{{ $giftPayment->sender->email }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="mb-0">Phone</p>
                            </div>
                            <div class="col-sm-8">
                                <p class="text-muted mb-0">{{ $giftPayment->sender->phone }}
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="mb-0">Payment Method</p>
                            </div>
                            <div class="col-sm-8">
                                <p class="text-muted mb-0">{{ $giftPayment->payment_method }}
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="mb-0">Amount</p>
                            </div>
                            <div class="col-sm-8">
                                <p class="text-muted mb-0">{{ $giftPayment->amount}}</p>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 text-center">
                <div class="card mb-4">

                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <h3 class="text-center">Receiver</h3>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <p class="mb-0">ID</p>
                            </div>
                            <div class="col-sm-8">
                                    <a href="/userDetail/{{ $giftPayment->receiver_id }}"
                                        class="btn btn-sm btn-info"> {{ $giftPayment->receiver_id}}
                                    </a>

                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="mb-0">Full Name</p>
                            </div>
                            <div class="col-sm-8">
                                <p class="text-muted mb-0">
                                    {{ $giftPayment->receiver->getuserFullNameAttribute() }}
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="mb-0">Email</p>
                            </div>
                            <div class="col-sm-8">
                                <p class="text-muted mb-0">{{ $giftPayment->receiver->email }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="mb-0">Phone</p>
                            </div>
                            <div class="col-sm-8">
                                <p class="text-muted mb-0">{{ $giftPayment->receiver->phone }}
                                </p>
                            </div>
                        </div>
                        <hr>

                    </div>
                </div>
            </div>


            {{-- <div class="row">

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
                                        {{ formatPrice($giftPayment->sub_total) }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-0">Fees Amount</p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">
                                        {{ formatPrice($giftPayment->fees_amount) }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-0">Delivery Fees</p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0">
                                        {{ formatPrice($giftPayment->shipping_amount) }}</p>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="mb-0">Grand Total</p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="text-muted mb-0"> {{ formatPrice($giftPayment->grand_total) }}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>




                <div class="col-lg-4 mt-4 text-center">
                    <div class="card  mb-4">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <h3 class="text-center">Shipment Number (AWB)</h3>
                                    <h4 class="text-center" style="font-weight: bold">{{ $giftPayment->awb }}</h4>
                                </div>
                            </div>
                            <div class="row">
                                <form method="POST" action="{{ route('admin.order.storeOrUpdateAWB', $giftPayment->id) }}">
                                    @csrf
                                    <input type="hidden" value="{{ $giftPayment->id }}">
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control awb" name="awb" id="awb"
                                            value="{{ $giftPayment->awb }}" required />

                                    </div>
                                    <div class="col-sm-12 mt-3 ">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>--}}

                @if ($giftPayment->ltn_number)
                    <div class="col-lg-4 mt-4 text-center">
                        <div class="card  mb-4">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <h3 class="text-center">Ltn Number</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <h4 class="text-muted mb-0">{{ $giftPayment->ltn_number }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($giftPayment->receipt)
                    <div class="col-lg-4 mt-4 text-center">
                        <div class="card  mb-4">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <h3 class="text-center">Receipt</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <a data-download-src="{{ Storage::url($giftPayment->receipt) }}"
                                        href="{{ Storage::url($giftPayment->receipt) }}" data-fancybox
                                        data-caption="Receipt">
                                        <img height="300" id="receipt-img" style="object-fit: contain"
                                            src="{{ Storage::url($giftPayment->receipt) }}" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>
@endsection
@section('scripts')
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
@endsection
