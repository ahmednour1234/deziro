<div class="modal fade" id="addAdminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        @csrf
        <div class="modal-content">
            <form id="AddAdminForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Add Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="id" name="id">

                    <div class="row g-2">

                        <div class="col-6 mb-0">
                            <label for="first_name" class="form-label"> First Name <span
                                    class="text-error"></span></label>
                            <input type="text" id="first_name" name="first_name" class="form-control first_name">
                            <span class="text-danger" id="error_first_name"></span>
                        </div>

                        <div class="col-6 mb-0">
                            <label for="last_name" class="form-label"> Last Name <span
                                    class="text-error"></span></label>
                            <input type="text" id="last_name" name="last_name" class="form-control last_name">
                            <span class="text-danger" id="error_last_name"></span>
                        </div>

                        <div class="col-6 mb-0">
                            <label for="phone" class="form-label"> Phone <span class="text-error"></span></label>
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
                            <input type="password" id="password" name="password" class="form-control password">
                            <span class="text-danger" id="error_password"></span>
                        </div>

                        <div class="col-6 mb-0">
                            <label for="confirm_password" class="form-label"> Confirm Password <span class="text-error"></span></label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control confirm_password">
                            <span class="text-danger" id="error_confirm_password"></span>
                        </div>



                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary add_admin" id="saveBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    function displayAddImage(event) {
        document.getElementById('showImg').src = URL.createObjectURL(event.target.files[0]);
    }
</script>
