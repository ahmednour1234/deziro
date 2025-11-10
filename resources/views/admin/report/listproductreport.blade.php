@extends('admin.layouts.app')


@section('content')




<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
            /</a></span> Product Report
</h4>

<!-- Basic Bootstrap Table -->

@if (Session::has('success'))
<div class="alert alert-success">
    <ul>
        <li>{{ Session::get('success') }}</li>
    </ul>
</div>
@endif

<div class="card">
    <div class="d-flex justify-content-between  items-center">
        <form action="" method="get" id="searchForm">
            <div class="col-lg-4 col-md-6 mb-0 mt-4  mx-3">

                <div class="d-flex gap-3">
                    <div class="col-lg-3 input-group input-group-merge">
                        <span class="input-group-text" id="addon-search"><i class="bx bx-search"></i></span>
                        <input type="text" id="search" name="search" class="form-control" placeholder="Search ..." value="{{ request()->get('search') }}" autofocus>
                    </div>

                    <div class="col-lg-3 input-group input-group-merge">
                        <select id="category" name="category_name" class="form-select select2" value="{{ request()->get('category_name') }}">
                            <option value="">Select Category</option>
                            @foreach ($listCategory as $category)
                            <option value="{{ $category->id }}" {{ request()->get('category_name') == $category->id ? 'selected' : '' }}>
                                {{ $category->name}}</option>
                            @endforeach
                        </select>
                    </div>



                    <div class="col-lg-3 input-group input-group-merge">
                        <select name="status" class="form-select" value="{{ request()->get('status') }}">
                            <option value="">Select Status</option>
                            <option value="pending" {{ request()->get('status') == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="rejected" {{ request()->get('status') == 'rejected' ? 'selected' : '' }}>Rejected
                            </option>
                            <option value="active" {{ request()->get('status') == 'active' ? 'selected' : '' }}>Active
                            </option>
                            <option value="inactive" {{ request()->get('status') == 'inactive' ? 'selected' : '' }}>
                                Inactive</option>
                            <option value="sold" {{ request()->get('status') == 'sold' ? 'selected' : '' }}>
                                Sold</option>
                        </select>
                    </div>


                    <div class="col-lg-3 input-group input-group-merge">
                        <select name="product_type" class="form-select" value="{{ request()->get('product_type') }}">
                            <option value="">Select All Products</option>
                            <option value="products" {{ request()->get('product_type') == 'products' ? 'selected' : '' }}>Products
                            </option>
                            <option value="featuredproducts" {{ request()->get('product_type') == 'featuredproducts' ? 'selected' : '' }}>Featured Products
                            </option>
                        </select>
                    </div>




                </div>

            </div>


            <div class="d-flex gap-3 my-4 " style="margin-left:50%">
                <div class="">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
                <div class="">
                    <a href="{{ route('admin.report.listproductreport') }}" class="btn btn-danger">Cancel</a>
                </div>

                <div class="">
                    {{-- <a href="{{ route('productexport', $_GET) }}" class="btn btn-success">Excel</a> --}}
                </div>
            </div>
        </form>
        <div class="m-3 d-flex gap-2 my-4 ">
            <ul class="pagination">
                <li class="">
                    <div class="btn-group">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">{{ request()->get('limit') != '' ? request()->get('limit') : currentLimit() }}</button>
                        <ul class="dropdown-menu" style="min-width: auto;">
                            @foreach (limits() as $limit)
                            <li><a class="dropdown-item {{ $limit['active'] ? 'active' : '' }}" href="{{ $limit['url'] }}">{{ $limit['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>



    <div class="table-responsive text-nowrap">
        <nav class="nav-pagination" aria-label="Page navigation">
            <div class="row ">
                <div class="label col-lg-10 col-md-6 mx-3">
                    <span>Showing {{ $listProduct->firstItem() }} to {{ $listProduct->lastItem() }}
                        of total {{ $listProduct->total() }} entries</span>
                </div>
            </div>
        </nav>

        <table class="table" id="table_id" style="width: 100%">
            <thead>
                <tr>
                    <th>Created At</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Special Price</th>
                    <th>Views</th>
                    <th>Product Type</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($listProduct as $key => $product)
                <tr>
                    <td>{{ $product->created_at }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->special_price }}</td>
                    <td>{{ $product->views }}</td>
                    <td>{{ $product->product_type }}</td>
                    <td>{{ $product->type }}</td>
                    <td>{{ $product->status }} </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="row my-3">
            <div class="col-lg-8 mx-2">
                {{ $listProduct->links() }}
            </div>

        </div>
    </div>
</div>
@endsection

