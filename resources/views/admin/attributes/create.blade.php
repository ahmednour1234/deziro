@extends('admin.layouts.app')

@section('title', 'Add Attribute')

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">
            <a href="{{ route('admin.home.listHome') }}"> Home </a>/</span> <a href="{{ route('admin.attributes.index') }}">
            Attributes </a> / Create
    </h4>

    <div class="row">
        <div class="col">
            @if ($message = session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ $message }}
                </div>
            @endif
        </div>
    </div>
    <div class="card">

        <div class="d-flex justify-content-between  items-center">
            <h5 class="card-header">Add Attribute</h5>
        </div>


        <div class="modal-body">

            <form method="POST" action="{{ route('admin.attributes.store') }}" @submit.prevent="onSubmit"
                enctype="multipart/form-data">

                <div class="page-content">
                    <div class="form-container">
                        @csrf()

                        <section title="General">
                            <h5 class="section-header">General</h5>
                            <div class="section-body">
                                <div class="row">
                                    <div class="col-6 mb-2">
                                        <div class="control-group">
                                            <label for="code" class="form-label">Code <span
                                                    class="text-error"></span></label>
                                            <input type="text" id="code" name="code" class="form-control"
                                                value="{{ old('code') }}">
                                            @if ($errors->has('code'))
                                                <span class="text-danger text-left">{{ $errors->first('code') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-6 mb-2">
                                        <div class="control-group">
                                            <label for="name" class="form-label">Name <span
                                                    class="text-error"></span></label>
                                            <input type="text" id="name" name="name" class="form-control"
                                                value="{{ old('name') }}">
                                            @if ($errors->has('name'))
                                                <span
                                                    class="text-danger text-left">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>

                                    </div>

                                    <div class="col-12 mb-2">
                                        <div class="control-group">
                                            <label for="type" class="form-label">{{ __('Type') }}</label>
                                            <select class="form-control select2 category_type" id="type" name="type"
                                                required>
                                                {{-- <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>
                                                    {{ __('Text') }}</option>
                                                <option value="textarea" {{ old('type') == 'textarea' ? 'selected' : '' }}>
                                                    {{ __('Textarea') }}</option>
                                                <option value="price" {{ old('type') == 'price' ? 'selected' : '' }}>
                                                    {{ __('Price') }}</option>
                                                <option value="boolean" {{ old('type') == 'boolean' ? 'selected' : '' }}>
                                                    {{ __('Boolean') }}</option> --}}
                                                <option value="select" {{ old('type') == 'select' ? 'selected' : '' }}>
                                                    {{ __('Select') }}</option>
                                                {{-- <option value="multiselect"
                                                    {{ old('type') == 'multiselect' ? 'selected' : '' }}>
                                                    {{ __('Multiselect') }}</option>
                                                <option value="datetime" {{ old('type') == 'datetime' ? 'selected' : '' }}>
                                                    {{ __('Datetime') }}</option>
                                                <option value="date" {{ old('type') == 'date' ? 'selected' : '' }}>
                                                    {{ __('Date') }}</option>
                                                <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>
                                                    {{ __('Image') }}</option>
                                                <option value="file" {{ old('type') == 'file' ? 'selected' : '' }}>
                                                    {{ __('File') }}</option>
                                                <option value="checkbox" {{ old('type') == 'checkbox' ? 'selected' : '' }}>
                                                    {{ __('Checkbox') }}</option> --}}
                                            </select>
                                            @if ($errors->has('type'))
                                                <span class="text-danger text-left">{{ $errors->first('type') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <div class="{{ in_array(old('type'), ['select', 'multiselect', 'checkbox']) || true ? '' : 'hide' }}">
                            <section id="options">
                                <h5 class="section-header">Options</h5>
                                <div class="section-body">
                                    <div class="row">
                                        <div class="col-12 mb-2 {{ old('type') == 'select' ? '' : 'hide' }}">
                                            <div class="control-group">
                                                <label for="swatch_type" class="form-label">{{ __('Swatch Type') }}</label>
                                                <select class="form-control select2" id="swatch_type" name="swatch_type">
                                                    <option value="dropdown"
                                                        {{ old('swatch_type') == 'dropdown' ? 'selected' : '' }}>
                                                        {{ __('Dropdown') }}
                                                    </option>

                                                    {{-- <option value="color"
                                                        {{ old('swatch_type') == 'color' ? 'selected' : '' }}>
                                                        {{ __('Color Swatch') }}
                                                    </option>

                                                    <option value="image"
                                                        {{ old('swatch_type') == 'image' ? 'selected' : '' }}>
                                                        {{ __('Image Swatch') }}
                                                    </option>

                                                    <option value="text"
                                                        {{ old('swatch_type') == 'text' ? 'selected' : '' }}>
                                                        {{ __('Text Swatch') }}
                                                    </option> --}}
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 mb-2 hide">
                                            <div class="form-check control-group">
                                                <input class="form-check-input" type="checkbox" id="default-null-option" name="default-null-option" value="1"
                                                    {{ old('default-null-option') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="default-null-option">
                                                    {{ __('Default Null Option') }}
                                                </label>
                                            </div>
                                        </div>

                                        <div class="table options_table">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th id="swatch-header"
                                                            class="{{ old('swatch_type') == 'color' || old('swatch_type') == 'image' ? '' : 'hide' }}">
                                                            {{ __('Swatch') }}</th>

                                                        <th>{{ __('Name') }}</th>

                                                        <th>{{ __('Position') }}</th>

                                                        <th></th>
                                                    </tr>
                                                </thead>

                                                <tbody id="options_list">
                                                    @if ($options = old('options'))
                                                        @foreach ($options as $id => $row)
                                                            <tr id="{{ $id }}">
                                                                <td
                                                                    class="swatch-picker @if (old('type') != 'select' || old('swatch_type') != 'color') hide @endif">
                                                                    <div class="control-group">
                                                                        <input type="color"
                                                                            name="options[{{ $id }}][color_swatch_value]" />
                                                                    </div>
                                                                </td>

                                                                <td
                                                                    class="file @if (old('type') != 'select' || old('swatch_type') != 'image') hide @endif">
                                                                    <div class="control-group">
                                                                        <input type="file" accept="image/*"
                                                                            name="options[{{ $id }}][image_swatch_value]" />
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <div class="control-group">
                                                                        <input type="text"
                                                                            name="{{ 'options[' . $id . '][name]' }}"
                                                                            @if ($errors->has('options.' . $id . '.name')) placeholder="{{ $errors->first('options.' . $id . '.name') }}" @endif
                                                                            class="form-control @if ($errors->has('options.' . $id . '.name')) has-error @endif"
                                                                            value="{{ $row['name'] }}" />
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <div class="control-group">
                                                                        <input type="number"
                                                                            name="{{ 'options[' . $id . '][sort_order]' }}"
                                                                            class="form-control"
                                                                            value="{{ $row['sort_order'] }}" />
                                                                    </div>
                                                                </td>

                                                                <td class="actions">
                                                                    <i class="tf-icons bx bx-trash"
                                                                        onclick="removeRow('{{ $id }}')"></i>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3">
                                                            <button type="button" class="btn btn-primary mt-20"
                                                                id="add-option-btn" onclick="addOptionRow(false)">
                                                                {{ __('Add Option') }}
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>


                                    </div>
                                </div>
                            </section>

                        </div>

                        <section title="{{ __('Validations') }}">
                            <h5 class="section-header">Validations</h5>
                            <div class="section-body">
                                <div class="row">
                                    <div class="col-6 mb-2">

                                        <div class="form-check control-group">
                                            <input class="form-check-input" type="checkbox" id="is_required" name="is_required" value="1"
                                                {{ old('is_required') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_required">
                                                {{ __('Is Required ?') }}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-6 mb-2">

                                        <div class="form-check control-group">
                                            <input class="form-check-input" type="checkbox" id="is_unique" name="is_unique" value="1"
                                                {{ old('is_unique') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_unique">
                                                {{ __('Is Unique ?') }}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-2">
                                        <div class="control-group">
                                            <label for="validation"
                                                class="form-label">{{ __('Input Validation') }}</label>
                                            <select class="form-control select2 category_type" id="validation"
                                                name="validation">
                                                <option value=""></option>
                                                <option value="numeric"
                                                    {{ old('validation') == 'numeric' ? 'selected' : '' }}>
                                                    {{ __('Number') }}
                                                </option>
                                                <option value="email"
                                                    {{ old('validation') == 'email' ? 'selected' : '' }}>
                                                    {{ __('Email') }}</option>
                                                <option value="decimal"
                                                    {{ old('validation') == 'decimal' ? 'selected' : '' }}>
                                                    {{ __('Decimal') }}
                                                </option>
                                                <option value="url" {{ old('validation') == 'url' ? 'selected' : '' }}>
                                                    {{ __('Url') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section>
                            <h5 class="section-header">Configuration</h5>
                            <div class="section-body">
                                <div class="row">
                                    <div class="col-6 mb-2">
                                        <div class="form-check control-group">
                                            <input class="form-check-input" type="checkbox" id="value_per_user" name="value_per_user" value="1"
                                                {{ old('value_per_user') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="value_per_user">
                                                {{ __('Value Per User ?') }}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-6 mb-2">
                                        <div class="form-check control-group">
                                            <input class="form-check-input" type="checkbox" id="is_filterable" name="is_filterable" value="1"
                                                {{ old('is_filterable') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_filterable">
                                                {{ __('Is Filterable ?') }}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-6 mb-2">
                                        <div class="form-check control-group">
                                            <input class="form-check-input" type="checkbox" id="is_configurable" name="is_configurable" value="1"
                                                {{ old('is_configurable') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_configurable">
                                                {{ __('Use To Create Configurable Product ?') }}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-6 mb-2">
                                        <div class="form-check control-group">
                                            <input class="form-check-input" type="checkbox" id="is_visible_on_front" name="is_visible_on_front" value="1"
                                                {{ old('is_visible_on_front') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_visible_on_front">
                                                {{ __('Visible on Product View Page on Front-end ?') }}
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </section>


                    </div>
                </div>
                <div class="text-center my-3">
                    <button type="submit" class="btn btn-primary text-center">Save Attribute</button>
                </div>
            </form>
        </div>



    </div>
@endsection

@push('scripts')
    <script>
        var optionRowCount = 1;
        var optionRows = [];
        var show_swatch = @json(old('type') == 'select');
        var type = @json(old('type'));
        var swatch_type = @json(old('swatch_type'));
        var isNullOptionChecked = false;
        var idNullOption = null;

        $(document).ready(function() {
            $('#type').on('change', function(e) {
                show_swatch = false;
                type = $(e.target).val();
                if (['select'].indexOf($(e.target).val()) === -1) {

                    $('#swatch_type').parent().parent().addClass('hide');
                } else {
                    show_swatch = true;
                    $('#swatch_type').parent().parent().removeClass('hide');
                }
            });

            $('#swatch_type').on('change', function(e) {
                swatch_type = $(e.target).val();
                if (swatch_type == 'color') {
                    $('.swatch-picker').removeClass('hide');
                    $('.file').addClass('hide');
                    $('#swatch-header').removeClass('hide');
                } else if (swatch_type == 'image') {
                    $('.file').removeClass('hide');
                    $('.swatch-picker').addClass('hide');
                    $('#swatch-header').removeClass('hide');
                } else {
                    $('.file').addClass('hide');
                    $('.swatch-picker').addClass('hide');
                    $('#swatch-header').addClass('hide');
                }
            });

            $('#default-null-option').on('change', function(e) {
                if ($(e.target).is(':checked')) {
                    if (!idNullOption) {
                        addOptionRow(true);
                    }
                } else if (idNullOption !== null && typeof idNullOption !== 'undefined') {
                    const row = optionRows.find(optionRow => optionRow.id === idNullOption);
                    removeRow(row.id);
                }
            });
        });


        function addOptionRow(isNullOptionRow) {
            const rowCount = this.optionRowCount++;
            const id = 'option_' + rowCount;
            let row = {
                'id': id,
                'locales': {}
            };

            row['notRequired'] = '';

            if (isNullOptionRow) {
                this.idNullOption = id;
                row['notRequired'] = true;
            }

            this.optionRows.push(row);

            var option_html = '';
            option_html += `<tr id="${row.id}">`;
            option_html +=
                `<td class="swatch-picker ${show_swatch && swatch_type == 'color' ? '' : 'hide'}">
                    <div class="control-group">
                        <input type="color" name="options[${row.id}][color_swatch_value]" value="#ffffff" />
                    </div>
                </td>`;
            option_html += `<td class="file ${show_swatch && swatch_type == 'image'? '' : 'hide'}">
                            <div class="control-group">
                                <input type="file" accept="image/*" name="options[${row.id}][image_swatch_value]"/>
                            </div>
                        </td>`;

            option_html += `<td>
                                <div class="control-group">
                                    <input type="text" name="${adminName(row)}" class="form-control" />
                                </div>
                            </td>`;

            option_html += `<td>
                                <div class="control-group">
                                    <input type="number" name="` + sortOrderName(row) + `" class="form-control"/>
                                </div>
                            </td>`;

            option_html += `<td class="actions">
                                <i class="tf-icons bx bx-trash" onclick="removeRow('${row.id}')"></i>
                            </td>`;
            option_html += '</tr>';



            $('#options_list').append(option_html)
        }

        function removeRow(id) {
            if (id === this.idNullOption) {
                this.idNullOption = null;
                this.isNullOptionChecked = false;
            }

            this.optionRows = this.optionRows.filter(function(row) {
                return row.id == id;
            });

            $('#' + id).remove();
        }

        function adminName(row) {
            return 'options[' + row.id + '][name]';
        }

        function sortOrderName(row) {
            return 'options[' + row.id + '][sort_order]';
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#type').on('change', function(e) {
                if (['select', 'multiselect', 'checkbox'].indexOf($(e.target).val()) === -1) {
                    $('#options').parent().addClass('hide');
                } else {
                    $('#options').parent().removeClass('hide');
                }

                if (['select', 'multiselect', 'checkbox', 'checkbox', 'price'].indexOf($(e.target)
                        .val()) === -1) {
                    $('#options').parent().addClass('hide');
                    $('#is_filterable').attr('disabled', 'disabled');
                } else {
                    $('#options').parent().removeClass('hide');
                    $('#is_filterable').removeAttr('disabled');
                }

                if (['text'].indexOf($(e.target).val()) > -1) {
                    $('#validation').parents('.control-group').removeClass('hide');
                } else {
                    $('#validation').parents('.control-group').addClass('hide');
                }
            })
        });
    </script>
@endpush

<script>
    function displayAddImage(event) {
        document.getElementById('showImg').src = URL.createObjectURL(event.target.files[0]);
    }
</script>
