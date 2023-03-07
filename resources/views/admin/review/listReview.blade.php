@extends('admin.layouts.app')

@section('title', 'Reviews')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Reviews</h4>

    <div id="success_message"></div>
    <div class="card">

        <div class="d-flex justify-content-between  items-center">
            <h5 class="card-header">All Reviews</h5>
        </div>

        <div class="modal-body">

            <input type="hidden" id="id" name="id">

            <div class="row g-2">


                <div class="table-responsive text-nowrap">
                    <table class="table" id="table_id" style="width: 100%">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>User Name</th>
                                <th>Rate</th>


                            </tr>

                        </thead>
                        <tbody class="table-border-bottom-0 text-center" id="myTable">
                            <tr>
                                <td>1</td>
                                <td>Ali Malla</td>
                                <td><div class="ratings">
                                    <i class="fa fa-star rating-color"></i>
                                    <i class="fa fa-star rating-color"></i>
                                    <i class="fa fa-star rating-color"></i>
                                    <i class="fa fa-star rating-color"></i>
                                    <i class="fa fa-star"></i>
                                </div></td>
                            </tr>

                            <tr>
                                <td>2</td>
                                <td>Ali Kazan</td>
                                <td><div class="ratings">
                                    <i class="fa fa-star rating-color"></i>
                                    <i class="fa fa-star rating-color"></i>
                                    <i class="fa fa-star rating-color"></i>
                                    <i class="fa fa-star rating-color"></i>
                                    <i class="fa fa-star"></i>
                                </div></td>
                            </tr>


                            <tr>
                                <td>3</td>
                                <td> Mohamad Hassan</td>
                                <td><div class="ratings">
                                    <i class="fa fa-star rating-color"></i>
                                    <i class="fa fa-star rating-color"></i>
                                    <i class="fa fa-star rating-color"></i>
                                    <i class="fa fa-star rating-color"></i>
                                    <i class="fa fa-star"></i>
                                </div></td>
                            </tr>
                            {{-- @foreach ($listSwapProduct as $key=>$swapProduct )
                                <tr class="text-center">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $swapProduct->name }}</td>
                                    <td>{{ $swapProduct->user->first_name.' '.$swapProduct->user->last_name }} </td>
                                    <td>{{ $swapProduct->category->type }}</td>
                                    <td>{{ $swapProduct->category->name }}</td>
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
