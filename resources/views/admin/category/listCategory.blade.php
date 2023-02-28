@extends('admin.layouts.app')

@section('content')

@include('admin.category.crud_category.addCategorieModal')

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Categories</h4>


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
                        data-bs-target="#addCategorieModal">
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
                        <span>Showing {{ $listCategorie->firstItem() }} to {{ $listCategorie->lastItem() }}
                            of total {{ $listCategorie->total() }} entries</span>
                    </div>
                </div>
            </nav>
            <table class="table  " id="table_id" style="width: 100%" class="text-center">
                <thead class="text-center">
                    <tr>
                        <th>Created At</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($listCategorie as $categorie)
                        <tr>
                            <td>{{ $categorie->created_at->format('d-m-Y') }}</td>
                            <td>{{ $categorie->name }}</td>
                            <td><img src="storage/{{ $categorie->image }}" alt="" width="200" ></td>
                            <td>
                                <button class="btn btn-primary btn-sm edit_categorie" value="{{ $categorie->id }}">Edit</button>
                                @if($categorie->is_active == 1)
                                <button class="btn btn-success btn-sm inactive" value="{{ $categorie->id }}">Active</button>
                                @else
                                <button class="btn btn-warning btn-sm active" value="{{ $categorie->id }}">Inactive</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            <div class="row my-3">
                <div class="col-lg-8 mx-2">
                    {{ $listCategorie->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            $(document).on('click', '.add_category', function(e) {
                e.preventDefault();

                let formData = new FormData($('#AddCategoryForm')[0]);
                // console.log(formData)
                $.ajax({
                    type: 'POST',
                    url: '/addNewCategory',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);

                        if (response.status == 400) {
                            response.errors.name != undefined ? $('#error_name').html(response
                                .errors.name) : $('#error_name').html('')
                            response.errors.image != undefined ? $('#error_image').html(response
                                .errors.image) : $('#error_image').html('')

                        } else {
                            $('#success_message').text(response.message)
                            $('#success_message').addClass('alert alert-success')
                            $('#success_message').text(response.message)
                            $('#addCategoryModal').modal('hide')
                            $('#addCategoryModal').find('input').val('')
                            location.reload()
                        }
                    }
                })
            })



        })
    </script>
@endsection
