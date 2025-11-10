@extends('admin.layouts.app')


@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span>Attributes</h4>

    <!-- Basic Bootstrap Table -->

    <div class="row">
        <div class="col">
            <div class="alert hide" role="alert">

            </div>
        </div>
    </div>

    <div class="card">
        <div class="d-flex justify-content-between  items-center">
            <div class="col-lg-3 col-md-6 mb-0 mt-3  mx-3">
                <form action="" method="get" id="searchForm">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text" id="category-addon-search"><i class="bx bx-search"></i></span>
                        <input type="text" id="search" name="search" class="form-control" placeholder="Search ..."
                            value="{{ request()->get('search') }}" autofocus>

                    </div>
                </form>
            </div>
            <div class="m-3">
                <a href="{{ route('admin.attributes.create') }}" type="button" class="btn btn-primary">
                    <span class="flex-center">Add <i class="bx bx-plus"></i></span>
                </a>
            </div>
        </div>


        <div class="text-nowrap" id="table_container">
            @include('admin.attributes.attributes', $attributes)
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">

            @csrf
            <div class="modal-content">
                <div id="modal-overlay">
                    <div class="w-100 flex-center">
                        <div class="spinner-border spinner-border-lg text-secondary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <form action="">
                    <div class="modal-header">
                        <h3 class="modal-title" id="exampleModalLabel1">Delete</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" id="delete_id" name="delete_id">
                        <h4 class="title"></h4>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="deleteBtn">Yes
                            Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--/ Basic Bootstrap Table -->
@endsection

@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        });
        $(document).on('click', '#delete_attribute', function(e) {
            e.preventDefault();

            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#deleteModal #delete_id').val(id)
            $('#deleteModal .modal-title').text('Delete ' + name)
            $('#deleteModal .title').text('Are you sure you want to delete this attribute?')
            $('#deleteModal').modal('show');
        });

        $('#deleteModal #deleteBtn').on('click', function(e) {
            let id = $('#deleteModal #delete_id').val();
            var url = 'attributes/delete/' + id;
            isLoading(true);

            $.ajax({
                url: url,
                type: "POST",
                data: {
                    'search': @json(request()->get('search')),
                    'limit': @json(request()->get('limit')),
                    'page': @json(request()->get('page')),
                },
                dataType: 'json',
                success: function(data) {
                    isLoading(false);
                    if (data.success) {
                        $('#table_container').html(data.html);
                        $('.alert').removeClass('alert-danger');
                        $('.alert').addClass('alert-success');
                        $('.alert').text(data.message);
                        $('.alert').show();
                        // $(`#attribute_${id}`).slideUp("normal", function() {
                        //     $(this).remove();
                        // });
                    } else {
                        $('.alert').removeClass('alert-success');
                        $('.alert').addClass('alert-danger');
                        $('.alert').text(data.message);
                        $('.alert').show();
                    }
                    $('#deleteModal').modal('hide');
                },
                error: function(result) {
                    isLoading(false);
                    $('#deleteModal .alert').html(result.responseJSON.message);
                    $('#deleteModal .alert').show();
                }
            });
        });
    </script>
@endsection
