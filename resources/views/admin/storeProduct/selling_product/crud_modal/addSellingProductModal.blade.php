<div class="modal fade" id="addSellingProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">


            @csrf
            <div class="modal-content">
                {{-- <form id="AddProductForm" method="POST" enctype="multipart/form-data"> --}}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Add Selling Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="id" name="id">

                    <div class="row g-2">

                        <div class="col-6 mb-0">
                            <label for="store" class="form-label"> Select Store <span
                                    class="text-error"></span></label>
                            <select class="form-control addSelect store" id="store"
                                aria-label="Default select example" name="store" required>
                                <option value=""> Select Store ...</option>
                                 @foreach ($listStore as $store)
                                    <option value={{$store->id }}>{{$store->store_name}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="error_store"></span>
                        </div>



                        <div class="col-6 mb-0">
                            <label for="subCategory" class="form-label"> Select SubCategory <span
                                    class="text-error"></span></label>
                            <select class="form-select addSubSelect subCategory" id="subCategory" aria-label="Default select example"
                                name="subCategory" required>
                                <option value=""> Select Sub-Category ...</option>
                                @foreach ($listSubCategory as $subCategory)
                                    <option value="{{ $subCategory->id }}">
                                        {{ $subCategory->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="error_subCategory"></span>
                        </div>

                        <div class="col-6 mb-0">
                            <label for="name" class="form-label">Product Name <span
                                    class="text-error"></span></label>
                            <input type="text" id="name" name="name" class="form-control name">
                            <span class="text-danger" id="error_name"></span>
                        </div>



                        <div class="col-6 mb-0">
                            <label for="condition" class="form-label">Select Condition <span
                                    class="text-error"></span></label>
                            <select class="form-control select2 condition" id="condition" name="condition">
                                <option value="">Select</option>
                                <option value="New">New</option>
                                <option value="Used">Used</option>
                                <option value="LikeNew">Like New</option>
                                <option value="Defective">Defective</option>
                            </select>
                            <span class="text-danger" id="error_condition"></span>
                        </div>



                        <div class="col-6 mb-0">
                            <label for="available_quantity" class="form-label">Available Quantity <span
                                    class="text-error"></span></label>
                            <input type="text" id="available_quantity"
                                name="available_quantity"class="form-control available_quantity">
                            <span class="text-danger" id="error_available_quantity"></span>
                        </div>


                        <div class="col-6 mb-0 ">
                            <label for="price" class="form-label"> Price <span class="text-error"></span></label>
                            <input type="text" id="price" name="price"class="form-control price">
                            <span class="text-danger" id="error_price"></span>
                        </div>


                        <div class="col-12 mb-0 ">
                            <label for="description" class="form-label"> Description <span
                                    class="text-error"></span></label>
                            <textarea name="description" id="description" class="description form-control" cols="69" rows="4"></textarea>
                            <span class="text-danger" id="error_description"></span>
                        </div>


                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary add_selling_product" id="saveBtn">Save</button>
                </div>
            </form>
            </div>


    </div>
</div>
