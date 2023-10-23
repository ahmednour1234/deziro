@extends('admin.layouts.app')

@section('title', 'Payment Info')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"> <a href="{{ route('admin.home.listHome') }}"> Home
            /</a></span> Info</h4>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card">

    <div class="d-flex justify-content-between  items-center">
        <h5 class="card-header">Update Payment Info</h5>

    </div>
    <form action="{{ route('admin.payment_info.updatePaymentInfo') }}" method="POST">
        @csrf
        <div class="modal-body">

            <div class="row g-2">

                <div class="col-6 mb-0">
                    <label for="wish_number" class="form-label">Wish Number <span class="text-error"></span></label>
                    <input type="text" id="wish_number" name="wish_number" class="form-control wish_number" value="{{ $payment_info->wish_number }}" required>

                </div>

                <div class="col-6 mb-0">
                    <label for="wish_name" class="form-label">Wish Name <span class="text-error"></span></label>
                    <input type="text" id="wish_name" name="wish_name" class="form-control wish_name" value="{{ $payment_info->wish_name }}" required>

                </div>


                <div class="col-6 mb-0">
                    <label for="omt_number" class="form-label">Omt Number <span class="text-error"></span></label>
                    <input type="text" id="omt_number" name="omt_number" class="form-control omt_number" value="{{ $payment_info->omt_number }}" required>

                </div>

                <div class="col-6 mb-0">
                    <label for="omt_name" class="form-label">Omt Name <span class="text-error"></span></label>
                    <input type="text" id="omt_name" name="omt_name" class="form-control omt_name" value="{{ $payment_info->omt_name }}" required>

                </div>

            </div>

        </div>
        <div class="text-center my-3">

            <button type="submit" class="btn btn-primary  text-center" id="saveBtn">Save</button>
        </div>
    </form>


</div>
@endsection
