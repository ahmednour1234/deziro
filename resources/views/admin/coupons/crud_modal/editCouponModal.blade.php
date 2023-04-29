<div class="modal fade" id="editCouponModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        @csrf
        <div class="modal-content">
            <form id="UpdateCouponForm" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Edit Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="edit_id" name="edit_id">

                    <div class="row">

                        <div class="form-group">
                            <label for="code">Coupon code:</label>
                            <input class="form-control" type="text" name="code" id="edit_code" required>
                            <span class="text-danger" id="error_edit_code"></span>
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                        </div>
                        <span class="text-danger" id="error_edit_description"></span>

                        <div class="form-group">
                            <label for="is_percentage">Type:</label>
                            <select class="form-control" name="is_percentage" id="edit_is_percentage" required>
                                <option value="1">Percentage</option>
                                <option value="0">Fixed amount</option>
                            </select>
                            <span class="text-danger" id="error_edit_is_percentage"></span>
                        </div>


                        <div class="form-group col-lg-6">
                            <label for="discount_value">Discount value:</label>
                            <input class="form-control" type="number" name="discount_value" id="edit_discount_value"
                                step="0.01" required>
                            <span class="text-danger" id="error_edit_discount_value"></span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="min_order_amount">Minimum order amount:</label>
                            <input class="form-control" type="number" name="min_order_amount" id="edit_min_order_amount"
                                step="0.01">
                            <span class="text-danger" id="error_edit_min_order_amount"></span>
                        </div>

                        <div class="form-group">
                            <label for="expiry_date">Expiry date:</label>
                            <input class="form-control" type="datetime-local" name="expiry_date" id="edit_expiry_date"
                                required>
                            <span class="text-danger" id="error_edit_expiry_date"></span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="usage_limit_per_coupon">Max orders:</label>
                            <input class="form-control" type="number" name="usage_limit_per_coupon"
                                id="edit_usage_limit_per_coupon">
                            <span class="text-danger" id="error_edit_usage_limit_per_coupon"></span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="usage_limit_per_user">Max order same user:</label>
                            <input class="form-control" type="number" name="usage_limit_per_user"
                                id="edit_usage_limit_per_user">
                            <span class="text-danger" id="error_edit_usage_limit_per_user"></span>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary update_coupon" id="saveBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
      function displayEditImage(event) {
        document.getElementById('showEditImg').src = URL.createObjectURL(event.target.files[0]);
    }
</script>
