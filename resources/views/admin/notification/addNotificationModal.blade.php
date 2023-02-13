<div class="modal fade" id="addNotificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        <form id="AddNotificationForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Add Notiification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="id" name="id">

                    <div class="row g-2">

                        <div class="col-12 mb-0">
                            <label for="title" class="form-label">Notification Title <span
                                    class="text-error"></span></label>
                            <input type="text" id="title" name="title" class="form-control title">
                            <span class="text-danger" id="error_title"></span>
                        </div>

                        <div class="col-12 mb-0">
                            <label for="description" class="form-label">Notification description <span
                                    class="text-error"></span></label>
                            <textarea class="form-control description" name="description" id="description" cols="30" rows="5"></textarea>
                            <span class="text-danger" id="error_description"></span>
                        </div>
                    </div>

                    <div class="col-12 mb-0 my-2">
                        <label for="user_id" class="form-label"> Select All User <span
                                class="text-error"></span></label>
                        <select class="form-control select2 user_id" id="user_id" name="user_id">
                            <option value="users"> All User ...</option>
                            <option value="stores"> All Store ...</option>
                            <option value="individuals"> All Individual ...</option>
                            @foreach ($listUsers as $user)
                                <option value={{ $user->id }}>{{ $user->first_name.' '.$user->last_name }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger" id="error_user_id"></span>
                    </div>

                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary add_notification" id="saveBtn">Save</button>
                </div>

            </div>
        </form>
    </div>
</div>
