<div class="modal fade" id="acceptModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        @csrf
        <div class="modal-content">
            <form action="">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel1">Accept Gift Payment</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="giftpayment_id" name="giftpayment_id">
                    <h5>Are you sure do you want to Accept this Gift Payment?</h5>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary be_accept" id="saveBtn">Yes
                        Accept</button>
                </div>
            </form>
        </div>
    </div>
</div>
