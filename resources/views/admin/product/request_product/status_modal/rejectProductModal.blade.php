<div class="modal fade" id="rejectProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        @csrf
        <div class="modal-content">
            {{-- <form id="rejectProductForm" enctype="multipart/form-data" method="POST"> --}}
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel1">Reject product</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="reject_id" name="reject_id">

                    <div class="col-12 mb-0 mx-auto">
                        <label for="reason"> Enter Reason</label>
                     <textarea name="reason" id="reason" class="reason form-control" cols="67.5" rows="5"></textarea>
                        <span class="text-danger" id="error_reason"></span>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary reject_btn" id="saveBtn">Yes
                        Reject</button>
                </div>
            {{-- </form> --}}

        </div>
    </div>
</div>
