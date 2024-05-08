
<div class="form-group row mb-3">
    <label for="cover_image" class="col-sm-2 col-form-label">Cover image</label>
    <div class="col-sm-10">
        <div class="form-group">
            <div class="row">
                <div class="col-12 mb-3">
                    <input type="file" class="form-control-file @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image">
                    <button type="button" class="btn btn-secondary" id="clear_image">Clear</button>
                </div>
                {{-- <span class="alert alert-warning col-6"> Image size around 390x640 is highly recommended</span> --}}
                <div id="cover_preview" class="col-12 d-none">

                    <p>Cover preview:</p>
                    <img id="cover_preview_image" src="#" alt="Cover preview" style="max-width: 640px; height:auto;">
                </div>
            </div>
        </div>
    </div>

    @error('cover_image')
        <span id="cover_image_error" class="text-danger d-block" role="alert">
            {{ $message }}
        </span>
    @enderror
</div>




<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        const coverImageInput = document.querySelector('input#cover_image');
        const coverPreviewContainer = document.querySelector('#cover_preview');
        const coverPreviewImage = document.querySelector('img#cover_preview_image');

        coverImageInput.onchange = event => {
            const [file] = coverImageInput.files;
            if (file) {
                coverPreviewContainer.classList.remove('d-none');
                coverPreviewImage.src = URL.createObjectURL(file);
            } else {
                coverPreviewContainer.classList.add('d-none');
            }
        }

        const clear_image = document.querySelector('button#clear_image');
        clear_image.onclick = function(){
        clearInputFile(coverImageInput);
        };

        function clearInputFile(f){
            f.value = ''
            coverPreviewContainer.classList.add('d-none');
        }
    });

</script>

