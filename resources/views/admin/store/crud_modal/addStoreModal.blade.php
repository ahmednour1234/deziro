@extends('admin.layouts.app')


@section('content')
    <div class="card">
        <form id="AddStoreForm" method="POST" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Add Store</h5>
            </div>
            <div class="modal-body">

                <input type="hidden" id="id" name="id">

                <div class="row g-2">



                    <div class="col-6 mb-0">
                        <label for="first_name" class="form-label"> First Name <span class="text-error"></span></label>
                        <input type="text" id="first_name" name="first_name" class="form-control first_name">
                        <span class="text-danger" id="error_first_name"></span>
                    </div>

                    <div class="col-6 mb-0">
                        <label for="last_name" class="form-label"> Last Name <span class="text-error"></span></label>
                        <input type="text" id="last_name" name="last_name" class="form-control last_name">
                        <span class="text-danger" id="error_last_name"></span>
                    </div>


                    <div class="col-6 mb-0">
                        <label for="phone" class="form-label"> phone <span class="text-error"></span></label>
                        <input type="number" id="phone" name="phone" class="form-control phone">
                        <span class="text-danger" id="error_phone"></span>
                    </div>


                    <div class="col-6 mb-0">
                        <label for="email" class="form-label"> Email <span class="text-error"></span></label>
                        <input type="text" id="email" name="email" class="form-control email">
                        <span class="text-danger" id="error_email"></span>
                    </div>

                    <div class="col-6 mb-0">
                        <label for="password" class="form-label"> Password <span class="text-error"></span></label>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password" class="form-control  password " name="password" value="{{ old('password') }}"  autocomplete="current-password" required/>
                            <span class="input-group-text cursor-pointer" id="toggle-password"><i class="bx bx-hide"></i></span>
                        </div>

                        <span class="text-danger" id="error_password"></span>
                    </div>

                    <div class="col-6 mb-0">
                        <label for="confirm_password" class="form-label"> confirm_password <span
                                class="text-error"></span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="confirm_password" class="form-control   confirm_password" name="confirm_password" value="{{ old('confirm_password') }}"  autocomplete="current-password" required/>
                                    <span class="input-group-text cursor-pointer" id="confirm-toggle-password"><i class="bx bx-hide"></i></span>
                                </div>
                       
                        <span class="text-danger" id="error_confirm_password"></span>
                    </div>

                    <div class="col-12 mb-0">
                        <label for="category_type" class="form-label">Category Name <span class="text-error"></span></label>
                        <select id="categorys_type" name="category_type[]" multiple>
                            <!--<option value=""> Select Category ...</option>-->
                            @foreach ($listCategorys as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach

                        </select>
                        <span class="text-danger" id="error_category_type"></span>
                    </div>




                    <div class="col-6 mb-0">
                        <label for="store_name" class="form-label"> Store Name <span class="text-error"></span></label>
                        <input type="text" id="store_name" name="store_name" class="form-control store_name">
                        <span class="text-danger" id="error_store_name"></span>
                    </div>

                    <div class="col-6 mb-0">
                        <label for="position" class="form-label"> Position <span class="text-error"></span></label>
                        <input type="text" id="position" name="position" class="form-control position">
                        <span class="text-danger" id="error_position"></span>
                    </div>

                    <div class="col-6 mb-0">
                        <label for="tax_number" class="form-label"> Tax Number <span class="text-error"></span></label>
                        <input type="text" id="tax_number" name="tax_number" class="form-control tax_number">
                        <span class="text-danger" id="error_tax_number"></span>
                    </div>

                    <div class="col-6 mb-0">
                        <label for="certificate" class="form-label"> Certificate <span class="text-error"></span></label>
                        <input type="file" id="certificate" name="certificate" class="form-control certificate"
                            onchange="displayAddImage(event)">
                        <span class="text-danger" id="error_certificate"></span>
                    </div>


                    {{-- <div class="col-12 mb-0 " id="display_image">
                        <img id="showImg" width="100%" height="250">
                    </div> --}}

                    {{-- <iframe id="showImg" width="100%" height="250">
                    </iframe>
                    <a href="{{ $userDetail->certificate }}">Click To Open!</a> --}}



                    {{-- <div class="col-6 mb-0">
                        <label for="category" class="form-label">Category Name <span class="text-error"></span></label>
                        <select class="form-control addselect2  category" id="categorys_name" name="category[]" multiple
                             value=''>
                            <!--<option value=""> Select Category ...</option>-->
                            @foreach ($listCategorys as $category)
                                <option value="{{ $category->id }}" >{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="error_category"></span>
                    </div> --}}


                </div>
            </div>
            <div class="modal-footer mx-auto">
                <button type="submit" class="btn btn-primary mx-auto add_store" id="saveBtn">Save</button>
            </div>
        </form>
    </div>
@endsection


@section('scripts')
    <script>
        // document.getElementById('showImg').style.display = 'none'

        // function displayAddImage(event) {
        //     document.getElementById('showImg').style.display = 'block'
        //     document.getElementById('showImg').src = URL.createObjectURL(event.target.files[0]);
        // }


        $(document).ready(function() {

            var selectize = $('#categorys_type').selectize({
                plugins: ['remove_button'],
                delimiter: ',',
                persist: false,

                onDropdownOpen: function($dropdown) {
                    // Set focus to the search input when the dropdown is opened
                    $dropdown.find('.selectize-input input[type="text"]').first().focus();
                }
            })[0].selectize;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            //Add store
            $(document).on('click', '.add_store', function(e) {
                e.preventDefault();
                let formData = new FormData($('#AddStoreForm')[0]);
                $.ajax({
                    type: "POST",
                    url: "/addNewStore",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response)

                        if (response.status == 400) {
                            response.errors.first_name != undefined ? $('#error_first_name')
                                .html(response.errors.first_name) : $('#error_first_name').html(
                                    '')
                            response.errors.last_name != undefined ? $('#error_last_name').html(
                                response.errors.last_name) : $('#error_last_name').html('')
                            response.errors.email != undefined ? $('#error_email').html(response
                                .errors.email) : $('#error_email').html('')
                            response.errors.password != undefined ? $('#error_password').html(
                                response.errors.password) : $('#error_password').html('')
                            response.errors.username != undefined ? $('#error_username').html(
                                response.errors.username) : $('#error_username').html('')
                            response.errors.store_name != undefined ? $('#error_store_name')
                                .html(response.errors.store_name) : $('#error_store_name').html(
                                    '')
                            response.errors.phone != undefined ? $('#error_phone').html(response
                                .errors.phone) : $('#error_phone').html('')

                            response.errors.position != undefined ? $('#error_position').html(
                                response
                                .errors.position) : $('#error_position').html('')

                            response.errors.tax_number != undefined ? $('#error_tax_number')
                                .html(response
                                    .errors.tax_number) : $('#error_tax_number').html('')

                            // response.errors.category != undefined ? $('#error_category').html(
                            //     response.errors.category) : $('#error_category').html('')

                            response.errors.certificate != undefined ? $('#error_certificate')
                                .html(response.errors.certificate) : $('#error_certificate')
                                .html('')
                            response.errors.confirm_password != undefined ? $(
                                '#error_confirm_password').html(
                                response.errors.confirm_password) : $(
                                '#error_confirm_password').html('')

                        } else if (response.status == 404) {
                            response.message != undefined ? $('#error_confirm_password').html(
                                response.message) : $('#error_confirm_password').html('')

                        } else {
                            $('#success_message').text(response.message)
                            $('#success_message').addClass('alert alert-success')
                            $('#success_message').text(response.message)
                            $('#addModal').find('input').val('')
                            $('#addModal').find('select').val('')
                            location.href = '/store';

                        }
                    }
                })
            })
        });
    </script>
@endsection
