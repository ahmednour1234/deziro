@extends('admin.layouts.app')

@section('content')


<div class="card">

    <form action="{{route('admin.notification.updateNotification',$notification->id)}}" id="AddCategoryForm" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Edit Notification</h5>
            </div>

            <div class="modal-body">
                <input type="hidden" id="id" name="id">

                <div class="row g-2">

                    <div class="col-12 mb-0">
                        <label for="notification_title" class="form-label">Notification Title <span
                                class="text-error"></span></label>
                        <input type="text" id="notification_title" name="title"
                            class="form-control notification_title" value="{{$notification->title}}">
                        <span class="text-danger" id="error_notification_title"></span>
                    </div>

                    <div class="col-12 mb-0">
                        <label for="notification_description" class="form-label">Notification description <span
                                class="text-error"></span></label>
                        <textarea class="form-control"  name="description" id="" cols="30" rows="5">{{$notification->description}}</textarea>
                        <span class="text-danger" id="error_notification_description" ></span>
                    </div>
                </div>

                <div class="col-12 mb-0 my-2">
                    <label for="users" class="form-label"> Select Store <span class="text-error"></span></label>
                    <select class="form-control addSelect users" id="users" aria-label="Default select example"
                        name="user_id" required>
                        <option value=""> Select All</option>
                        <option value=""> Select All Store</option>
                        <option value=""> Select All Individual</option>
                        @foreach ($listUsers as $user)
                        <option value={{$user->id }} {{$user->id == $notification->user_id ? 'selected' : ''}}>{{$user->first_name}}</option>
                        @endforeach



                    </select>
                    <span class="text-danger" id="error_users"></span>
                </div>
            </div>



            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button> --}}
                <button type="submit" class="btn btn-primary " id="saveBtn">Save</button>
            </div>

        </div>
    </form>
</div>

@endsection
@section('scripts')
<script>
    $(document).ready(function() {

        $('.addSelect').select2({
                width: '100%',
                theme: 'classic'
            })

        })
</script>
@endsection
