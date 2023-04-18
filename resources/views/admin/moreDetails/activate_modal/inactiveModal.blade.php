<div class="modal fade" id="inactiveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        @csrf
        <div class="modal-content">
            <form action="">
                <div class="modal-header">
                    <h3 class="modal-title" id="active_title"></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="inactive_id" name="inactive_id">
                    <h5 id="active_msg"></h5>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success is_inactive" id="saveBtn">Yes
                        Activate</button>
                </div>
            </form>
        </div>
    </div>
</div>
