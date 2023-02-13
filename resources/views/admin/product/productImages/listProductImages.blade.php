@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <br />
        <h3 class="text-center">Add Images to {{ $product->name }}</h3>
        <br />

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Select Image</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="/productImages/store/{{ $product->id }}" enctype="multipart/form-data"
                    class="dropzone" id="dropzone">
                    @csrf
                </form>
                <div class="text-center mt-3">
          <button type="button" class="btn btn-info reload" id="submit-all">View All Images</button>
        </div>
            </div>
        </div>
        <br />

        <div class="panel-heading">
            <h3 class="">Uploaded Image</h3>
        </div>
        <div class="row ">
            @foreach ($ProductImages as $images)
            <div class="col-lg-3 col-md-4 col-sm-6 mt-3 " id="uploaded_image">
                <div class="card" style="width: 18rem; height:24.5rem">
                    <img src="{{ asset($images->product_image) }}" width="100%" height="100%" id="image_id"  value={{ $images->product_id }} >
                    <div class="card-body text-center">
                       <a href="/productImages/delete/{{ $images->id }}"> <button class="btn btn-primary delete " value="{{ $images->id }}">Delete</button></a>
                     
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>


    {{-- <div class="container">

        <h3 class="jumbotron">Add Images to {{ $product->product_name }}</h3>
        <form method="post" action="/productImages/store/{{ $product->id }}" enctype="multipart/form-data" class="dropzone"
            id="dropzone">

            @csrf
        </form>
        <div>
@foreach ($imagesProduct as $images)
<img src="{{ asset('admin/assets/img/product_image/' . $images->product_images) }}" width="150" id="image_id"  value={{ $images->product_id }} >
@endforeach


        </div>
    </div> --}}
@endsection

@section('scripts')
    <script type="text/javascript">
    $('#submit-all').click(function() {
    location.reload();
});


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        Dropzone.options.dropzone = {
            maxFilesize: 12,
            renameFile: function(file) {
                var dt = new Date();
                var time = dt.getTime();
                return time + file.name;
            },
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            addRemoveLinks: false,
            timeout: 50000,
            removedfile: function(file) {
                var name = file.upload.filename;
                $.ajax({

                    type: 'POST',
                    url: '/productImages/delete',
                    data: {
                        filename: name
                    },
                    success: function(data) {
                        console.log("File has been successfully removed!!");
                    },
                    error: function(e) {
                        console.log(e);
                    }
                });
                var fileRef;
                return (fileRef = file.previewElement) != null ?
                    fileRef.parentNode.removeChild(file.previewElement) : void 0;
            },

            success: function(file, response) {
                console.log(response.success);

            },
            error: function(file, response) {
                return false;
            }
        };
    </script>
@endsection
