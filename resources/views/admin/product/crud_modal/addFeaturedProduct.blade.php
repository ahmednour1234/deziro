<div class="modal fade" id="addFeaturedProduct" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        @csrf
        <div class="modal-content">
            <form id="addFeaturedProductForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Add Featured Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="id" name="id">

                    <div class="row g-2">

                        <div class="col-12 mb-0">
                            <label for="featured_product" class="form-label">Select Featured Product <span
                                    class="text-error"></span></label>
                            <select type="text" id="featured_product" name="featured_product" class="form-control featured_product select2" required>
                                <option value="">Seelect Product</option>
                                @foreach ($listProducts as $product )
                                    <option value="{{ $product->id }}">{{'product Id : '.'  ' . $product->id. '  ' . 'Product Name : '.'  '.$product->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="error_featured_product"></span>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary add_featured_product" id="saveBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
