@extends('admin.layouts.app')


@section('content')
    <div class="card">
        <form id="UpdateStoreForm" method="POST" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Update Store</h5>
            </div>
            <div class="modal-body">

                <input type="hidden" id="id" name="id" value="{{ $store->id }}">

                <div class="row g-2">

                    <div class="col-6 mb-0">
                        <label for="first_name" class="form-label"> First Name <span class="text-error"></span></label>
                        <input type="text" id="first_name" name="first_name" class="form-control first_name"
                            value="{{ $store->first_name }}" required>
                        <span class="text-danger" id="error_first_name"></span>
                    </div>

                    <div class="col-6 mb-0">
                        <label for="last_name" class="form-label"> Last Name <span class="text-error"></span></label>
                        <input type="text" id="last_name" name="last_name" class="form-control last_name"
                            value="{{ $store->last_name }}" required>
                        <span class="text-danger" id="error_last_name"></span>
                    </div>

                    <div class="col-6 mb-0">
                        <label for="phone" class="form-label"> Phone <span class="text-error"></span></label>
                        <input type="number" id="phone" name="phone" class="form-control phone"
                            value="{{ $store->phone }}" required>
                        <span class="text-danger" id="error_phone"></span>
                    </div>

                    <div class="col-6 mb-0">
                        <label for="email" class="form-label"> Email <span class="text-error"></span></label>
                        <input type="text" id="email" name="email" class="form-control email"
                            value="{{ $store->email }}" required>
                        <span class="text-danger" id="error_email"></span>
                    </div>

                    <div class="col-6 mb-0">
                        <label for="password" class="form-label"> Password <span class="text-error"></span></label>
                        <input type="password" id="password" name="password" class="form-control password" value=""
                            required>
                        <span class="text-danger" id="error_password"></span>
                    </div>


                    <div class="col-6 mb-0">
                        <label for="confirm_password" class="form-label"> confirm_password <span
                                class="text-error"></span></label>
                        <input type="password" id="confirm_password" name="confirm_password"
                            class="form-control confirm_password" required>
                        <span class="text-danger" id="error_confirm_password"></span>
                    </div>

                    @php
                    $categories = explode(",", $store->categories);


               @endphp
                    <div class="col-12 mb-0">
                        <label for="category_type" class="form-label">Category Name <span
                                class="text-error"></span></label>
                        <select class="form-control editselect2  category_type" id="categorys_type" name="category_type[]" multiple="multiple"
                            multiple required >

                            <!--<option value=""> Select Category ...</option>-->

                            @foreach ($listCategorys as $category)
                            @foreach ($categories as $key => $categorie)
                                <option value="{{ $category->id }}" {{$category->id == $categorie ?  "selected"  : ""}}>{{ $category->name }}</option>
                                {{-- {{ in_array($option->id, explode(',', $record->field_name)) ? 'selected' : '' }} --}}
                            @endforeach
                            @endforeach
                        </select>
                        <span class="text-danger" id="error_category_type"></span>
                    </div>



                    <div class="col-6 mb-0">
                        <label for="store_name" class="form-label"> Store Name <span class="text-error"></span></label>
                        <input type="text" id="store_name" name="store_name" class="form-control store_name"
                            value="{{ $store->store_name }}">
                        <span class="text-danger" id="error_store_name"></span>
                    </div>

                    <div class="col-6 mb-0">
                        <label for="position" class="form-label"> Position <span class="text-error"></span></label>
                        <input type="text" id="position" name="position" class="form-control position"
                            value="{{ $store->position }}">
                        <span class="text-danger" id="error_position"></span>
                    </div>

                    <div class="col-6 mb-0">
                        <label for="tax_number" class="form-label"> Tax Number <span class="text-error"></span></label>
                        <input type="text" id="tax_number" name="tax_number" class="form-control tax_number"
                            value="{{ $store->tax_number }}">
                        <span class="text-danger" id="error_tax_number"></span>
                    </div>

                    <div class="col-6 mb-0">
                        <label for="certificate" class="form-label"> Certificate <span class="text-error"></span></label>
                        <input type="file" id="certificate" name="certificate" class="form-control certificate"
                            value="{{ $store->certificate }}" onchange="displayAddImage(event)">
                        <span class="text-danger" id="error_certificate"></span>
                    </div>

                    <div class="col-12 mb-0 " id="display_image">
                    @if ($store->certificate != '')
                    <iframe src="{{ asset($store->certificate) }}" id="showImg" width="100%" height="300">
                    </iframe>
                    <a href="{{ $store->certificate }}">Click To Open!</a>
                @endif
            </div>




                </div>
            </div>
            <div class="modal-footer mx-auto">
                <button type="button" class="btn btn-primary mx-auto update_store" id="saveBtn">Save</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>


function displayAddImage(event) {
    document.getElementById('showImg').style.display = 'block'
    document.getElementById('showImg').src = URL.createObjectURL(event.target.files[0]);
}


        $(document).ready(function() {
            $('.editselect2').select2({
                width: '100%',
                allowClear: true,
                theme: 'classic',
                // containerCssClass: ':all:'
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //   Update User
        $(document).on('click', '.update_store', function(e) {
            e.preventDefault();
            $(this).text('Updating')
            var store_id = $('#id').val();
            let formData = new FormData($('#UpdateStoreForm')[0]);

            $.ajax({
                type: "POST",
                url: "/updateStore/" + store_id,
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

                        response.errors.position != undefined ? $('#error_position').html(response
                            .errors.position) : $('#error_position').html('')

                        response.errors.tax_number != undefined ? $('#error_tax_number').html(response
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
                        response.message != undefined ? $('#error_confirm_password').html(response
                            .message) : $('#error_confirm_password').html('')
                        $('#update_error_message').html('');
                        $('#update_error_message').addClass('alert alert-danger');
                        $('#update_error_message').text('response.message');

                    } else {
                        $('#success_message').text(response.message)
                        $('#success_message').addClass('alert alert-success')
                        $('#success_message').text(response.message)
                        $('#editStoreModal').modal('hide')
                        $('#editStoreModal').find('input').val('')
                        location.href = '/store'

                    }
                }
            })
        })

        function displayEditImage(event) {
            document.getElementById('showEditImg').src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
@endsection
