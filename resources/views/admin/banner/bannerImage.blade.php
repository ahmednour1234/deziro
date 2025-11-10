@extends('admin.layouts.app')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Banners</h4>


    <div id="success_message"></div>

    <div class="container-fluid">


        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Select Image</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="/bannerImage/store/{{ $banner->id }}" enctype="multipart/form-data"
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
            @foreach ($bannerImage as $images)
                {{-- {{ dd($images) }} --}}
                <div class="col-lg-3 col-md-4 col-sm-6 mt-3 mx-4 " id="uploaded_image">
                    <div class="card " style="width: 18rem; height:24.5rem">
                        <img src={{ url('storage/' . $images->image) }} width="100%" height="100%" id="image_id"
                            value={{ $images->banner_id }}>
                        <div class="card-body text-center">
                            <a href="/bannerImage/delete/{{ $images->id }}"> <button class="btn btn-primary delete"
                                    value="{{ $images->id }}">Delete</button></a>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!--/ Basic Bootstrap Table -->
@endsection




@section('scripts')
    <script type="text/javascript">
        $('#submit-all').click(function() {
            location.reload();
        });

    </script>
@endsection
