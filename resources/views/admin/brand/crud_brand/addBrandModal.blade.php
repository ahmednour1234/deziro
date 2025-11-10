<div class="modal fade" id="addBrandModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        <form id="AddBrandForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">


                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Add Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="id" name="id">

                    <div class="row g-2">



                        <div class="col-12 mb-0">
                            <label for="name" class="form-label">Brand Name <span
                                    class="text-error"></span></label>
                            <input type="text" id="name" name="name" class="form-control name">
                            <span class="text-danger" id="error_name"></span>
                        </div>


                        <div class="col-6 mb-0">
                            <label for="image" class="form-label"> Add Image <span class="text-error"></span></label>

                            <input type="file" class="form-control image" name="image_path" id="image"
                                onchange="displayAddImage(event)" />
                            <span class="text-danger" id="error_image"></span>
                        </div>

                        <div class="col-6 mb-0 " id="display_image">
                            <img id="showImg" width="100%" height="170">
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary add_brand" id="saveBtn">Save</button>
                </div>

            </div>
        </form>
    </div>
</div>






<script>
    document.getElementById('showImg').style.display = 'none'

    function displayAddImage(event) {
        document.getElementById('showImg').style.display = 'block'
        document.getElementById('showImg').src = URL.createObjectURL(event.target.files[0]);
    }
</script>
