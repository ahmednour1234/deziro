@extends('admin.layouts.app')

@section('title', 'Reviews')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Orders</h4>

    <div id="success_message"></div>
    <div class="card">

        <div class="d-flex justify-content-between  items-center">
            <h5 class="card-header">List Order Shipping</h5>
        </div>

        <div class="modal-body">

            <input type="hidden" id="id" name="id">

            <div class="row g-2">


                <div class="table-responsive text-nowrap">
                    <table class="table" id="table_id" style="width: 100%">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Order Date</th>
                                <th>Order Number</th>
                                <th>Order Type</th>
                                <th>Order Payment</th>
                                <th>Total Order Price</th>
                                <th>Action</th>
                                <th>View Details</th>
                            </tr>

                        </thead>
                        <tbody class="table-border-bottom-0 text-center" id="myTable">

                            {{-- @foreach ($listSwapProduct as $key=>$swapProduct )
                                <tr class="text-center">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $swapProduct->name }}</td>
                                    <td>{{ $swapProduct->user->first_name.' '.$swapProduct->user->last_name }} </td>
                                    <td>{{ $swapProduct->category->type }}</td>
                                    <td>{{ $swapProduct->subcategorie->name }}</td>
                                    <td>{{ $swapProduct->condition }}</td>
                                    <td><a href="/productImages/{{$swapProduct->id}}" class="btn btn-info btn-sm">Add Images</a></td>
                                    <td><a href="/viewSwapProduct/{{ $swapProduct->id }}" class="btn btn-success btn-sm">View Swap</a></td>
                                    <td>
                                        <button type="button" value="{{ $swapProduct->id }}" class="  btn btn-primary editbtn btn-sm ">Edit</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody> --}}
                    </table>
                </div>




            </div>
        </div>



    </div>

@endsection


<script>

</script>
