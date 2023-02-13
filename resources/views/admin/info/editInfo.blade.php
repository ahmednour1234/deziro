@extends('admin.layouts.app')

@section('title', 'Info')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Info</h4>

    <div id="success_message"></div>
    <div class="card">

        <div class="d-flex justify-content-between  items-center">
            <h5 class="card-header">Update Info</h5>

        </div>


            <div class="modal-body">

                <input type="hidden" id="id" name="id">

                <div class="row g-2">

                    <div class="col-12 mb-0">
                        <label for="setting_name" class="form-label">Name <span
                                class="text-error"></span></label>
                        <input type="text" id="setting_name" name="setting_name"
                            class="form-control setting_name">
                        <span class="text-danger" id="error_type"></span>
                    </div>

                    <div class="col-12 mb-0">
                        <label for="setting_email" class="form-label">Email <span
                                class="text-error"></span></label>
                        <input type="text" id="setting_email" name="setting_email"
                            class="form-control setting_email">
                        <span class="text-danger" id="error_type"></span>
                    </div>


                    <div class="col-12 mb-0">
                        <label for="setting_phone" class="form-label">Phone <span
                                class="text-error"></span></label>
                        <input type="text" id="setting_phone" name="setting_phone"
                            class="form-control setting_phone">
                        <span class="text-danger" id="error_type"></span>
                    </div>

                    <div class="col-6 mb-0">
                        <label for="image" class="form-label"> Add Logo <span
                                class="text-error"></span></label>

                        <input type="file" class="form-control image" name="image" id="image"
                            onchange="displayAddImage(event)" />
                        <span class="text-danger" id="error_image"></span>
                    </div>

                    <div class="col-6 mb-0 " id="display_image">
                        <img id="showImg" width="575" height="200">
                    </div>



                </div>
            </div>
            <div class="text-center my-3">

                <button type="submit" class="btn btn-primary add_subCategory text-center" id="saveBtn">Save</button>
            </div>


        </div>
    @endsection


    <script>
        function displayAddImage(event) {
            document.getElementById('showImg').src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
