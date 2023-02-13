<div class="modal fade" id="deleteSellingProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        @csrf
        <div class="modal-content">
            <form action="">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel1">Delete Product</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="delete_id" name="delete_id">
                        <h4 id="title_product_delete"></h4>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary delete_sellingProduct_btn" id="saveBtn">Yes Delete</button>
                </div>

        </div>
    </div>
</div>
