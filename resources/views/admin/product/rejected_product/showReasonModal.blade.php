<div class="modal fade" id="showReasonModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        @csrf
        <div class="modal-content">
            <form action="">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel1">Show Reason</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="reason_id" name="reason_id">
                        <p id="reason" name='reason' class="reason">

                        </p>

                </div>
                <div class="modal-footer mx-auto">
                    <button  type="button" class="btn btn-primary mx-auto" data-bs-dismiss="modal"id="saveBtn">Ok</button>
                </div>
            </form>
        </div>
    </div>
</div>
