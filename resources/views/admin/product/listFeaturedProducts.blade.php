@extends('admin.layouts.app')



@section('content')

@include('admin.product.crud_modal.addFeaturedProduct')
@include('admin.product.crud_modal.deleteFeaturedProduct')


    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span>List Featured Products</h4>

    <!-- Basic Bootstrap Table -->


    <div id="success_message"></div>

    <div class="card">
        <div class="d-flex justify-content-between  items-center">
            <div class="col-lg-3 col-md-6 mb-0 mt-3  mx-3">
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
                        data-bs-target="#addFeaturedProduct">
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
                <div class="row mt-3 ">
                    <div class="label col-lg-10 col-md-6 mx-3">
                        <span>Showing {{ $listFeaturedProducts->firstItem() }} to {{ $listFeaturedProducts->lastItem() }}
                            of total {{ $listFeaturedProducts->total() }} entries</span>
                    </div>
                </div>
            </nav>
            <table class="table" id="table_id" style="width: 100%">
                <thead class="text-center">
                    <tr>
                        <th><a class="text-dark"
                                href="{{ route('admin.product.listFeaturedProducts', [
                                    'sort' => 'created_at',
                                    'direction' => $sortColumn == 'created_at' && $sortDirection == 'asc' ? 'desc' : 'asc',

                                ]) }}">Created
                                At @if ($sortColumn == 'created_at')
                                    @if ($sortDirection == 'asc')
                                        <i class="fas fa-arrow-up"></i>
                                    @else
                                        <i class="fas fa-arrow-down"></i>
                                    @endif
                                @endif
                            </a></th>
                        <th> Product id</th>
                        <th> Product name</th>
                        <th> Store id</th>
                        <th> cat</th>
                        <th>Brand</th>
                        <th>quantity</th>
                        <th> price</th>
                        <th>special price</th>
                        <th>View Product</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($listFeaturedProducts as $key => $featuredProduct)
                        <tr>
                            <td>{{ $featuredProduct->created_at->format('Y-m-d ') }} </td>
                            <td>{{ $featuredProduct->product_id }}</td>
                            <td>{{ $featuredProduct->product->name }} </td>
                            <td><a href="/userDetail/{{ $featuredProduct->product->user_id }}" class="btn btn-info btn-sm">
                                    {{ $featuredProduct->product->user_id }}
                                </a></td>
                            <td>{{ $featuredProduct->product->category->name }} </td>
                            <td>{{ $featuredProduct->product->brand->name }} </td>
                            <td>{{ $featuredProduct->product->quantity }}</td>
                            <td>{{ $featuredProduct->product->price }}</td>
                            <td>{{ $featuredProduct->product->special_price }}</td>
                            <td> <a href="/productDetail/{{ $featuredProduct->product->id }}"  class="btn btn-info btn-sm">View More
                                    Details</a></td>
                            <td>
                           <button class="btn btn-sm btn-danger delete_product" value="{{ $featuredProduct->id }}" data-value1="{{  $featuredProduct->product->name }}">Remove</button>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $listFeaturedProducts->links() }}
                </div>
            </div>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $('#search').on('keyup', function() {
                const z = $('#search').val();
                console.log(z)
                if (z.length < 1) {
                    location.href = '/featuredProducts'
                }
            });

            $('.select2').select2({
                width: '100%',
                dropdownParent: $('#addFeaturedProduct'),
                theme: 'classic'
            })

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });



            $('.add_featured_product').click(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "/addFeaturedProduct",
                    data: $('#addFeaturedProductForm').serialize(),
                    dataType: 'json',
                    success: function(response) {
                        console.log(response)
                        if (response.status == 400) {
                            response.errors.featured_product != undefined ? $('#error_featured_product')
                                .html(response.errors.featured_product) : $('#error_featured_product').html(
                                    '')


                        }else{
                            $('#success_message').text(response.message)
                            $('#success_message').addClass('alert alert-success')
                            $('#addFeaturedProduct').modal('hide')
                            $('#addFeaturedProductForm')[0].reset();
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }

                    }
                })
            })


            $(document).on('click', '.delete_product', function(e) {
                e.preventDefault();
                var name = $(this).data('value1');
                var product_id = $(this).val();
                console.log(name);
                $('#delete_id').val(product_id)
                $('#delete_title').text('Delete  ' + name)
                $('#delete_msg').text('Are you sure do you want to Delete  ' + name)
                $('#deleteModal').modal('show')
            })

            $(document).on('click', '.delete', function(e) {
                e.preventDefault();

                var product_id = $('#delete_id').val();
                console.log(product_id)
                $.ajax({
                    type: 'POST',
                    url: 'deleteFeaturedProduct/' + product_id,
                    success: function(response) {
                        console.log(response);
                        $('#success_message').addClass('alert alert-success')
                        $('#success_message').text(response.message)
                        $('#deleteModal').modal('hide')

                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                })
            })





        })
    </script>
@endsection
