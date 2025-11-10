<div class="modal fade" id="editAdminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        @csrf
        <div class="modal-content">
            <form id="UpdateAdminForm" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Edit Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="edit_id" name="edit_id">

                    <div class="row g-2">


                        <div class="col-6 mb-0">
                            <label for="edit_first_name" class="form-label"> First Name <span
                                    class="text-error"></span></label>
                            <input type="text" id="edit_first_name" name="first_name" class="form-control edit_first_name">
                            <span class="text-danger" id="error_edit_first_name"></span>
                        </div>

                        <div class="col-6 mb-0">
                            <label for="edit_last_name" class="form-label"> Last Name <span
                                    class="text-error"></span></label>
                            <input type="text" id="edit_last_name" name="last_name" class="form-control edit_last_name">
                            <span class="text-danger" id="error_edit_last_name"></span>
                        </div>

                        <div class="col-6 mb-0">
                            <label for="edit_phone" class="form-label"> Phone <span class="text-error"></span></label>
                            <input type="number" id="edit_phone" name="phone" class="form-control edit_phone">
                            <span class="text-danger" id="error_edit_phone"></span>
                        </div>


                        <div class="col-6 mb-0">
                            <label for="edit_email" class="form-label"> Email <span class="text-error"></span></label>
                            <input type="text" id="edit_email" name="email" class="form-control edit_email">
                            <span class="text-danger" id="error_edit_email"></span>
                        </div>

                        <div class="col-12 mb-0">
                            <label for="edit_password" class="form-label"> Password <span class="text-error"></span></label>
                            {{-- <input type="password" id="edit_password" name="password" class="form-control edit_password"> --}}
                            <div class="input-group input-group-merge">
                                <input type="password" id="edit_password" class="form-control   edit_password" name="password" value="{{ old('password') }}"  autocomplete="current-password" required/>
                                <span class="input-group-text cursor-pointer" id="edit-toggle-password"><i class="bx bx-hide"></i></span>
                            </div>
                            <span class="text-danger" id="error_edit_password"></span>
                        </div>


                        {{-- <div class="col-6 mb-0">
                            <label for="edit_confirm_password" class="form-label">Confirm Password <span class="text-error"></span></label>
                            <input type="password" id="edit_confirm_password" name="confirm_password" class="form-control edit_confirm_password">
                            <span class="text-danger" id="error_edit_confirm_password"></span>
                        </div> --}}


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary update_admin" id="saveBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
      function displayEditImage(event) {
        document.getElementById('showEditImg').src = URL.createObjectURL(event.target.files[0]);
    }
</script>
