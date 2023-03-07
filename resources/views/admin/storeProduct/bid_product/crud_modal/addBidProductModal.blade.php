<div class="modal fade" id="addBidProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">


        @csrf
        <div class="modal-content">
            <form id="AddBidProductForm" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Add Bid Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="id" name="id">

                    <div class="row g-2">

                        <div class="col-6 mb-0">
                            <label for="store" class="form-label"> Select Store <span
                                    class="text-error"></span></label>
                            <select class="form-control  addSelect store" id="store"
                                aria-label="Default  example" name="store" required>
                                <option value=""> Select Store ...</option>
                                @foreach ($listStore as $user)
                                    <option value={{ $user->id }}>{{ $user->store_name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="error_store"></span>
                        </div>



                        <div class="col-6 mb-0">
                            <label for="category" class="form-label"> Select SubCategory <span
                                    class="text-error"></span></label>
                            <select class="form-select addSubSelect category" id="category" aria-label="Default select example"
                                name="category" required>
                                <option value=""> Select Sub-Category ...</option>
                                @foreach ($listCategory as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}</option>
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

                        <div class="col-12 mb-0 ">
                            <label for="" class="form-label"> Count Down <span
                                    class="text-error"></span></label>
                            <div class="d-flex">
                                <div class="col-4 ">
                                    <input type="number" id="day" name="day"class="form-control day"
                                        placeholder="Day ">
                                    <span class="text-danger" id="error_day"></span>
                                </div>
                                <div class="col-4 mx-1">
                                    <input type="number" id="hour" name="hour"class="form-control hour"
                                        placeholder="Hour">
                                    <span class="text-danger" id="error_hour"></span>
                                </div>
                                <div class="col-4 ">
                                    <input type="number" id="minute" name="minute"class="form-control minute"
                                        placeholder="Minute">
                                    <span class="text-danger" id="error_minute"></span>
                                </div>
                            </div>

                        </div>


                        <div class="col-12 mb-0 ">
                            <label for="bid_starting_price" class="form-label"> Bid Starting Price <span
                                    class="text-error"></span></label>
                            <input type="number" id="bid_starting_price"
                                name="bid_starting_price"class="form-control bid_starting_price">
                            <span class="text-danger" id="error_bid_starting_price"></span>
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
                    <button type="submit" class="btn btn-primary add_Bid_product" id="saveBtn">Save</button>
                </div>
            </form>
        </div>


    </div>
</div>
