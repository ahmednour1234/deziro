<div class="modal fade" id="approveProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        @csrf
        <div class="modal-content">
            <form id="approveProductForm" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel1">Approve Product</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="approve_id" name="approve_id">
                    <h3>Are you sure do you want to Approve this Product?</h3>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary approve_btn" id="saveBtn">Yes
                        Approve</button>
                </div>

        </div>
    </div>
</div>
