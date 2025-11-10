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

                {{-- First Name --}}
                <div class="col-6 mb-0">
                    <label for="first_name" class="form-label">First Name <span class="text-error"></span></label>
                    <input type="text" id="first_name" name="first_name" class="form-control first_name">
                    <span class="text-danger" id="error_first_name"></span>
                </div>

                {{-- Last Name --}}
                <div class="col-6 mb-0">
                    <label for="last_name" class="form-label">Last Name <span class="text-error"></span></label>
                    <input type="text" id="last_name" name="last_name" class="form-control last_name">
                    <span class="text-danger" id="error_last_name"></span>
                </div>

                {{-- Phone --}}
                <div class="col-6 mb-0">
                    <label for="phone" class="form-label">Phone <span class="text-error"></span></label>
                    <input type="number" id="phone" name="phone" class="form-control phone">
                    <span class="text-danger" id="error_phone"></span>
                </div>

                {{-- Email --}}
                <div class="col-6 mb-0">
                    <label for="email" class="form-label">Email <span class="text-error"></span></label>
                    <input type="text" id="email" name="email" class="form-control email">
                    <span class="text-danger" id="error_email"></span>
                </div>

                {{-- Password --}}
                <div class="col-6 mb-0">
                    <label for="password" class="form-label">Password <span class="text-error"></span></label>
                    <div class="input-group input-group-merge">
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control password"
                               autocomplete="new-password"
                               required>
                        <span class="input-group-text cursor-pointer" id="toggle-password">
                            <i class="bx bx-hide"></i>
                        </span>
                    </div>
                    <span class="text-danger" id="error_password"></span>
                </div>

                {{-- Confirm Password --}}
                <div class="col-6 mb-0">
                    <label for="confirm_password" class="form-label">Confirm Password <span class="text-error"></span></label>
                    <div class="input-group input-group-merge">
                        <input type="password"
                               id="confirm_password"
                               name="confirm_password"
                               class="form-control confirm_password"
                               autocomplete="new-password"
                               required>
                        <span class="input-group-text cursor-pointer" id="confirm-toggle-password">
                            <i class="bx bx-hide"></i>
                        </span>
                    </div>
                    <span class="text-danger" id="error_confirm_password"></span>
                </div>

                {{-- Categories (Multi Select) --}}
                <div class="col-12 mb-0">
                    <label for="categorys_type" class="form-label">Category Name <span class="text-error"></span></label>
                    <select id="categorys_type" name="category_type[]" multiple>
                        @foreach ($listCategorys as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="error_category_type"></span>
                </div>

                {{-- Store Name --}}
                <div class="col-6 mb-0">
                    <label for="store_name" class="form-label">Store Name <span class="text-error"></span></label>
                    <input type="text" id="store_name" name="store_name" class="form-control store_name">
                    <span class="text-danger" id="error_store_name"></span>
                </div>

                {{-- Position --}}
                <div class="col-6 mb-0">
                    <label for="position" class="form-label">Position <span class="text-error"></span></label>
                    <input type="text" id="position" name="position" class="form-control position">
                    <span class="text-danger" id="error_position"></span>
                </div>

                {{-- Tax Number --}}
                <div class="col-6 mb-0">
                    <label for="tax_number" class="form-label">Tax Number <span class="text-error"></span></label>
                    <input type="text" id="tax_number" name="tax_number" class="form-control tax_number">
                    <span class="text-danger" id="error_tax_number"></span>
                </div>

                {{-- VAT On/Off --}}
                <div class="col-6 mb-0">
                    <label for="vat" class="form-label">VAT <span class="text-error"></span></label>
                    <select id="vat" name="vat" class="form-select">
                        <option value="1">On</option>
                        <option value="0" selected>Off</option>
                    </select>
                    <span class="text-danger" id="error_vat"></span>
                </div>

                {{-- Certificate --}}
                <div class="col-6 mb-0">
                    <label for="certificate" class="form-label">Certificate <span class="text-error"></span></label>
                    <input type="file" id="certificate" name="certificate" class="form-control certificate">
                    <span class="text-danger" id="error_certificate"></span>
                </div>

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
    $(document).ready(function() {

        // Init selectize for categories
        var selectize = $('#categorys_type').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: false,
            onDropdownOpen: function($dropdown) {
                $dropdown.find('.selectize-input input[type="text"]').first().focus();
            }
        })[0].selectize;

        // CSRF
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Toggle password visibility
        $('#toggle-password').on('click', function () {
            const input = $('#password');
            const icon  = $(this).find('i');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('bx-hide').addClass('bx-show');
            } else {
                input.attr('type', 'password');
                icon.removeClass('bx-show').addClass('bx-hide');
            }
        });

        $('#confirm-toggle-password').on('click', function () {
            const input = $('#confirm_password');
            const icon  = $(this).find('i');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('bx-hide').addClass('bx-show');
            } else {
                input.attr('type', 'password');
                icon.removeClass('bx-show').addClass('bx-hide');
            }
        });

        // Add Store - AJAX
        $(document).on('click', '.add_store', function(e) {
            e.preventDefault();

            let formData = new FormData($('#AddStoreForm')[0]);

            // Clear old errors
            $('#AddStoreForm').find('.text-danger').text('');

            $.ajax({
                type: "POST",
                url: "/addNewStore",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {

                    if (response.status === 400) {
                        // Validation errors mapping
                        const errs = response.errors || {};

                        $('#error_first_name').text(errs.first_name ?? '');
                        $('#error_last_name').text(errs.last_name ?? '');
                        $('#error_email').text(errs.email ?? '');
                        $('#error_password').text(errs.password ?? '');
                        $('#error_confirm_password').text(errs.confirm_password ?? '');
                        $('#error_store_name').text(errs.store_name ?? '');
                        $('#error_phone').text(errs.phone ?? '');
                        $('#error_position').text(errs.position ?? '');
                        $('#error_tax_number').text(errs.tax_number ?? '');
                        $('#error_category_type').text(errs.category_type ?? '');
                        $('#error_certificate').text(errs.certificate ?? '');
                        $('#error_vat').text(errs.vat ?? '');

                    } else if (response.status === 404) {

                        $('#error_confirm_password').text(response.message ?? '');

                    } else {
                        // Success
                        $('#success_message')
                            .removeClass()
                            .addClass('alert alert-success')
                            .text(response.message || 'Store created successfully');

                        $('#AddStoreForm')[0].reset();
                        if (selectize) {
                            selectize.clear();
                        }

                        // Redirect to store list
                        window.location.href = '/store';
                    }
                },
                error: function(xhr) {
                    // fallback simple error
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
@endsection
