@extends('admin.layouts.app')

@section('title', 'Info')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
                /</a></span> Rate</h4>

    <div id="success_message"></div>
    <div class="card">

        <div class="d-flex justify-content-between  items-center">
            <h5 class="card-header">Update Rate</h5>

        </div>


        <div class="modal-body">

            <input type="hidden" id="id" name="id">

            <div class="row g-2">


                <div class="col-12 mb-0">
                    <label for="vendor_exchange_rate" class="form-label"> App Exchange Rate <span
                            class="text-error"></span></label>
                    <input type="number" id="vendor_exchange_rate" name="vendor_exchange_rate"
                        class="form-control vendor_exchange_rate">
                    <span class="text-danger" id="error_vendor_exchange_rate"></span>
                </div>

            </div>
        </div>
        <div class="text-center my-3">

            <button type="submit" class="btn btn-primary add_subCategory text-center" id="saveBtn">Save</button>
        </div>


    </div>
@endsection
