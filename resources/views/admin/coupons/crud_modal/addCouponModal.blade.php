<div class="modal fade" id="addCouponModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        @csrf
        <div class="modal-content">
            <form id="AddCouponForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Add Coupon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="row">

                        <div class="form-group">
                            <label for="code">Coupon code:</label>
                            <input class="form-control" type="text" name="code" id="code" required>
                            <span class="text-danger" id="error_code"></span>
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                        </div>
                        <span class="text-danger" id="error_description"></span>

                        <div class="form-group">
                            <label for="is_percentage">Type:</label>
                            <select class="form-control" name="is_percentage" id="is_percentage" required>
                                <option value="1">Percentage</option>
                                <option value="0">Fixed amount</option>
                            </select>
                            <span class="text-danger" id="error_is_percantage"></span>
                        </div>


                        <div class="form-group col-lg-6">
                            <label for="discount_value">Discount value:</label>
                            <input class="form-control" type="number" name="discount_value" id="discount_value"
                                step="0.01" required>
                            <span class="text-danger" id="error_discount_value"></span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="min_order_amount">Minimum order amount:</label>
                            <input class="form-control" type="number" name="min_order_amount" id="min_order_amount"
                                step="0.01">
                            <span class="text-danger" id="error_min_order_amount"></span>
                        </div>

                        <div class="form-group">
                            <label for="expiry_date">Expiry date:</label>
                            <input class="form-control" type="datetime-local" name="expiry_date" id="expiry_date"
                                required>
                            <span class="text-danger" id="error_expiry_date"></span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="usage_limit_per_coupon">Max orders:</label>
                            <input class="form-control" type="number" name="usage_limit_per_coupon"
                                id="usage_limit_per_coupon">
                            <span class="text-danger" id="error_usage_limit_per_coupon"></span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="usage_limit_per_user">Max order same user:</label>
                            <input class="form-control" type="number" name="usage_limit_per_user"
                                id="usage_limit_per_user">
                            <span class="text-danger" id="error_usage_limit_per_user"></span>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary add_coupon" id="saveBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    function displayAddImage(event) {
        document.getElementById('showImg').src = URL.createObjectURL(event.target.files[0]);
    }
</script>
