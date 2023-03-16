<div class="modal fade" id="rejectRequestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        @csrf
        <div class="modal-content">
            <form action="">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel1">Reject Request</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="reject_id" name="reject_id">
<h5>Are you sure do you want to reject this Request?</h5>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary reject_btn" id="saveBtn">Yes
                        Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
