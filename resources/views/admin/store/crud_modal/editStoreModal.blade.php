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

                {{-- First Name --}}
                <div class="col-6 mb-0">
                    <label for="first_name" class="form-label">First Name <span class="text-error"></span></label>
                    <input type="text"
                           id="first_name"
                           name="first_name"
                           class="form-control first_name"
                           value="{{ $store->first_name }}"
                           required>
                    <span class="text-danger" id="error_first_name"></span>
                </div>

                {{-- Last Name --}}
                <div class="col-6 mb-0">
                    <label for="last_name" class="form-label">Last Name <span class="text-error"></span></label>
                    <input type="text"
                           id="last_name"
                           name="last_name"
                           class="form-control last_name"
                           value="{{ $store->last_name }}"
                           required>
                    <span class="text-danger" id="error_last_name"></span>
                </div>

                {{-- Phone --}}
                <div class="col-6 mb-0">
                    <label for="phone" class="form-label">Phone <span class="text-error"></span></label>
                    <input type="number"
                           id="phone"
                           name="phone"
                           class="form-control phone"
                           value="{{ $store->phone }}"
                           required>
                    <span class="text-danger" id="error_phone"></span>
                </div>

                {{-- Email --}}
                <div class="col-6 mb-0">
                    <label for="email" class="form-label">Email <span class="text-error"></span></label>
                    <input type="text"
                           id="email"
                           name="email"
                           class="form-control email"
                           value="{{ $store->email }}"
                           required>
                    <span class="text-danger" id="error_email"></span>
                </div>

                {{-- Categories (Multi Select) --}}
                <div class="col-12 mb-0">
                    <label for="categorys_type" class="form-label">Category Name <span class="text-error"></span></label>
                    <select id="categorys_type" name="category_type[]" multiple="multiple" required>
                        @foreach ($allCategories as $category)
                            <option value="{{ $category->id }}"
                                {{ in_array($category->id, $categoryIds) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-danger" id="error_category_type"></span>
                </div>

                {{-- Store Name --}}
                <div class="col-6 mb-0">
                    <label for="store_name" class="form-label">Store Name <span class="text-error"></span></label>
                    <input type="text"
                           id="store_name"
                           name="store_name"
                           class="form-control store_name"
                           value="{{ $store->store_name }}">
                    <span class="text-danger" id="error_store_name"></span>
                </div>

                {{-- Position --}}
                <div class="col-6 mb-0">
                    <label for="position" class="form-label">Position <span class="text-error"></span></label>
                    <input type="text"
                           id="position"
                           name="position"
                           class="form-control position"
                           value="{{ $store->position }}">
                    <span class="text-danger" id="error_position"></span>
                </div>

                {{-- Tax Number --}}
                <div class="col-6 mb-0">
                    <label for="tax_number" class="form-label">Tax Number <span class="text-error"></span></label>
                    <input type="text"
                           id="tax_number"
                           name="tax_number"
                           class="form-control tax_number"
                           value="{{ $store->tax_number }}">
                    <span class="text-danger" id="error_tax_number"></span>
                </div>

                {{-- VAT On/Off --}}
                <div class="col-6 mb-0">
                    <label for="vat" class="form-label">VAT <span class="text-error"></span></label>
                    <select id="vat" name="vat" class="form-select">
                        <option value="1" {{ (int)($store->vat ?? 0) === 1 ? 'selected' : '' }}>On</option>
                        <option value="0" {{ (int)($store->vat ?? 0) === 0 ? 'selected' : '' }}>Off</option>
                    </select>
                    <span class="text-danger" id="error_vat"></span>
                </div>

                {{-- Certificate --}}
                <div class="col-6 mb-0">
                    <label for="certificate" class="form-label">Certificate <span class="text-error"></span></label>
                    <input type="file"
                           id="certificate"
                           name="certificate"
                           class="form-control certificate"
                           onchange="displayAddImage(event)">
                    <span class="text-danger" id="error_certificate"></span>
                </div>

                {{-- Existing Certificate Preview --}}
                <div class="col-12 mb-0" id="display_image">
                    @if ($store->certificate)
                        <iframe src="{{ asset($store->certificate) }}"
                                id="showImg"
                                width="100%"
                                height="300"></iframe>
                        <a href="{{ asset($store->certificate) }}" target="_blank">Click To Open!</a>
                    @else
                        <iframe id="showImg" width="100%" height="300" style="display:none;"></iframe>
                    @endif
                </div>

            </div>
        </div>

        <div class="modal-footer mx-auto">
            <button type="button" class="btn btn-primary mx-auto update_store" id="saveBtn">
                Save
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function displayAddImage(event) {
        const frame = document.getElementById('showImg');
        if (frame) {
            frame.style.display = 'block';
            frame.src = URL.createObjectURL(event.target.files[0]);
        }
    }

    $(document).ready(function () {

        // Init selectize for categories
        var selectize = $('#categorys_type').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: false,
            onDropdownOpen: function($dropdown) {
                $dropdown.find('.selectize-input input[type="text"]').first().focus();
            }
        })[0].selectize;

        // CSRF header
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Update Store
        $(document).on('click', '.update_store', function (e) {
            e.preventDefault();

            const $btn = $(this);
            $btn.prop('disabled', true).text('Updating...');

            const store_id = $('#id').val();
            let formData = new FormData($('#UpdateStoreForm')[0]);

            // clear previous errors
            $('#UpdateStoreForm').find('.text-danger').text('');

            $.ajax({
                type: "POST",
                url: "/updateStore/" + store_id,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log(response);

                    if (response.status === 400) {
                        const errs = response.errors || {};

                        $('#error_first_name').text(errs.first_name ?? '');
                        $('#error_last_name').text(errs.last_name ?? '');
                        $('#error_email').text(errs.email ?? '');
                        $('#error_phone').text(errs.phone ?? '');
                        $('#error_store_name').text(errs.store_name ?? '');
                        $('#error_position').text(errs.position ?? '');
                        $('#error_tax_number').text(errs.tax_number ?? '');
                        $('#error_category_type').text(errs.category_type ?? '');
                        $('#error_certificate').text(errs.certificate ?? '');
                        $('#error_vat').text(errs.vat ?? '');

                    } else if (response.status === 404) {

                        $('#update_error_message')
                            .removeClass()
                            .addClass('alert alert-danger')
                            .text(response.message || 'Store not found.');

                    } else {
                        $('#success_message')
                            .removeClass()
                            .addClass('alert alert-success')
                            .text(response.message || 'Store updated successfully');

                        $('#editStoreModal').modal('hide');
                        window.location.href = '/store';
                    }
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                },
                complete: function () {
                    $btn.prop('disabled', false).text('Save');
                }
            });
        });
    });
</script>
@endsection
