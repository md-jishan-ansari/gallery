<button 
    class="btn btn-success uploadBtn" 
    data-bs-target="#imageUploadModal" 
    data-bs-toggle="modal" 
>
    <i class="bi bi-upload"></i> <span>Upload Images</span>
</button>

<div class="modal fade" id="imageUploadModal" aria-labelledby="imageUploadModalLabel" tabindex="-1"
  style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="imageUploadModalLabel">Upload Images</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
          ></button>
      </div>
      <div class="modal-body">
        <form action="../../controllers/image_handler.php" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="gallery_images" class="form-label">Select Images</label>
            <input type="file" class="form-control" id="gallery_images"  aria-describedby="emailHelp" accept="image/*" name="gallery_images[]" multiple>
          </div>
          
          <button type="submit" class="btn btn-primary navBtn" name="upload" value="Upload">Upload</button>
        </form>
      </div>
      
    </div>
  </div>
</div>