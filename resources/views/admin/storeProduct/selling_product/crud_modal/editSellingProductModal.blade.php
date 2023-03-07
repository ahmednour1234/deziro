<div class="modal fade" id="editSellingProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        @csrf
        <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Update Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">

                        <div id="update_error_message"></div>

                        <input type="hidden" id="edit_selling_id" name="edit_id">

                        {{-- <input type="text" id="individual_name" name="individual_name" class="individual_name"> --}}

                        <div class="col-12 mb-0 ">
                            {{-- <label for="individual_id" class="form-label">individual_id<span
                                    class="text-error"></span></label> --}}
                            <input type="hidden" name="individual_id" id="edit_selling_individual_id" class="individual_id form-control"  >
                            <span class="text-danger" id="error_edit_individual_id"></span>
                        </div>

                    <div class="row g-2">

                        <div class="col-6 mb-0">

                            <label for="store" class="form-label">Select Store <span
                                    class="text-error"></span></label>
                            <select class="form-select editSelect" id="edit_selling_store"
                                aria-label="Default select example" name="store" required>
                                <option value=""> Select Store ...</option>
                                @foreach ($listStore as $store)
                                    <option value={{$store->id }}>{{$store->store_name}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="error_edit_selling_store"></span>
                        </div>


                        <div class="col-6 mb-0">
                            <label for="category" class="form-label">SubCategory Name <span
                                    class="text-error"></span></label>
                            <select class="form-select editSubSelect category" id="edit_selling_subcategory" aria-label="Default select example" name="category" required>
                                <option value=""> Select Sub ... </option>

                                @foreach ($listCategory as $category)
                                    <option value="{{ $category->id }}"> {{ $category->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="error_edit_selling_subcategory"></span>
                        </div>

                        <div class="col-6 mb-0">
                            <label for="name" class="form-label">Product Name <span
                                    class="text-error"></span></label>
                            <input type="text" id="edit_selling_name" name="name"
                                class="form-control name">
                            <span class="text-danger" id="error_selling_name"></span>
                        </div>



                        <div class="col-6 mb-0">
                            <label for="condition" class="form-label">Product Condition <span
                                    class="text-error"></span></label>
                            <select class="form-control select2 condition" id="edit_selling_condition"
                                name="condition">
                                <option value="">Select</option>
                                <option value="New">New</option>
                                <option value="Used">Used</option>
                                <option value="LikeNew">Like New</option>
                                <option value="Defective">Defective</option>
                            </select>
                            <span class="text-danger" id="error_edit_selling_condition"></span>
                        </div>



                        <div class="col-6 mb-0">
                            <label for="quantity" class="form-label">Product Quantity <span
                                    class="text-error"></span></label>
                            <input type="text" id="edit_selling_quantity" name="quantity"class="form-control quantity">
                            <span class="text-danger" id="error_edit_selling_quantity"></span>
                        </div>


                        <div class="col-6 mb-0 ">
                            <label for="price" class="form-label">Product Price <span
                                    class="text-error"></span></label>
                            <input type="text" id="edit_selling_price" name="price"class="form-control price">
                            <span class="text-danger" id="error_edit_selling_price"></span>
                        </div>


                        <div class="col-12 mb-0 ">
                            <label for="description" class="form-label">Description<span
                                    class="text-error"></span></label>
                            <textarea name="description" id="edit_selling_description" class="description form-control" cols="69" rows="3"></textarea>
                            <span class="text-danger" id="error_edit_selling_description"></span>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary update_sellingProduct" id="saveBtn">Update</button>
                </div>

        </div>

    </div>
</div>


