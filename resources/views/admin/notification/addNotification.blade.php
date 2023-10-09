@extends('admin.layouts.app')

@section('content')
    <div class="card">

        <form action="{{ route('admin.notification.addNewNotification') }}" id="AddCategoryForm" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Add Notification</h5>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="id" name="id">

                    <div class="row g-2">

                        <div class="col-12 mb-0">
                            <label for="notification_title" class="form-label" >Notification Title <span
                                    class="text-error"></span></label>
                            <input type="text" id="notification_title" name="title"
                                class="form-control notification_title" required>
                            <span class="text-danger" id="error_notification_title"></span>
                        </div>

                        <div class="col-12 mb-0">
                            <label for="notification_description" class="form-label">Notification description <span
                                    class="text-error"></span></label>
                            <textarea class="form-control" name="description" id="" cols="30" rows="5" required></textarea>
                            <span class="text-danger" id="error_notification_description"></span>
                        </div>
                    </div>

                    <div class="col-12 mb-0 my-2">
                        <label for="users" class="form-label"> Select User <span class="text-error"></span></label>
                        <select class="form-control addSelect users" id="users" aria-label="Default select example"
                            name="user_id" required>
                            <option value="all"> Select All</option>
                            <option value="stores">All Store Users</option>
                            <option value="individual">All Individual Users</option>
                            @foreach ($listUsers as $user)
                                <option value={{ $user->id }}>{{'Full name: ' .  $user->getuserFullNameAttribute(). '/ id: ' . $user->id }}</option>
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
