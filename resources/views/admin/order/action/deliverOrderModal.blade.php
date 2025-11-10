<div class="modal fade" id="deliveredModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        @csrf
        <div class="modal-content">
            <form action="">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel1">Deliver Order</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="order_id" name="order_id">
                    <h5>Are you sure do you want to Delivered this Order?</h5>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary be_delivered" id="saveBtn">Yes
                        Deliver</button>
                </div>
            </form>
        </div>
    </div>
</div>
