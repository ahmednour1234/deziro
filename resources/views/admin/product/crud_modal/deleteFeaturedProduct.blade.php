<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        @csrf
        <div class="modal-content">
            <form action="">
                <div class="modal-header">
                    <h3 class="modal-title" id="delete_title"></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="delete_id" name="delete_id">
                    <h5 id="delete_msg"></h5 id="msg">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger delete" id="saveBtn">Yes
                        Remove</button>
                </div>
            </form>
        </div>
    </div>
</div>
