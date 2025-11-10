<div class="modal fade" id="cancelModel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        @csrf
        <div class="modal-content">
            <form>
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel1">Cancel Order</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="order_id" name="order_id">

                    <div class="col-12 mb-0 mx-auto">
                        <label for="reason"> Enter Reason</label>
                        <textarea name="reason" id="reason" class="reason form-control" cols="67.5" rows="5"></textarea>
                        <span class="text-danger" id="error_reason"></span>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary be_cancel" id="saveBtn">Yes
                        cancel</button>
                </div>
            </form>

        </div>
    </div>
</div>
