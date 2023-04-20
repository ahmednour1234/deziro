<div class="modal fade" id="editBrandModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        <form id="EditBrandForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">


                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Edit Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">

                    <div class="row g-2">



                        <div class="col-12 mb-0">
                            <label for="name" class="form-label">Brand Name <span
                                    class="text-error"></span></label>
                            <input type="text" id="edit_name" name="name" class="form-control name">
                            <span class="text-danger" id="error_edit_name"></span>
                        </div>


                        <div class="col-12 mb-0">
                            <label for="categorie" class="form-label">Select categories <span
                                    class="text-error"></span></label>
                            <select  id="categorie" name="categorie[]" multiple>
                                @foreach ($listCategories as $categorie)
                                    <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="edit_error_categorie"></span>
                        </div>

                        {{--
                        <div class="col-12 mb-0">
                            <label for="attributes" class="form-label">Filterable Attributes <span
                                    class="text-error"></span></label>
                            <select class="form-control select2" id="attributes" name="attributes" multiple>
                                @foreach ($attributes as $attribute)
                                    <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger" id="edit_error_attribute"></span>
                        </div> --}}

                        <div class="col-6 mb-0">
                            <label for="image" class="form-label"> Update Image <span class="text-error"></span></label>

                            <input type="file" class="form-control image" name="image_path" id="edit_image"
                                onchange="displayeditImage(event)" />
                            <span class="text-danger" id="error_edit_image"></span>
                        </div>

                        <div class="col-6 mb-0 " id="display_image">
                            <img id="edit_showImg"  width="100%" height="170">
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary update_brand" id="saveBtn">Update</button>
                </div>

            </div>
        </form>
    </div>
</div>






<script>
    // document.getElementById('edit_showImg').style.display = 'none'

    function displayeditImage(event) {
        document.getElementById('edit_showImg').style.display = 'block'
        document.getElementById('edit_showImg').src = URL.createObjectURL(event.target.files[0]);
    }
</script>
