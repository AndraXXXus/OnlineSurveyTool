@props(['cover_image_path'])

<div class="form-group row mb-3">
    <label class="col-sm-2 col-form-label">Settings</label>
    <div class="col-sm-10">
        <div class="form-group">
            <div class="form-check">

                <input type="checkbox" class="form-check-input" value="1" id="remove_cover_image"
                    name="remove_cover_image" @checked(old() ? old('remove_cover_image',false) :false ) >
                <label for="remove_cover_image" class="form-check-label">Remove cover image</label>
            </div>
        </div>
    </div>
</div>

<div class="form-group row mb-3" id="cover_image_section">
    <label for="cover_image" class="col-sm-2 col-form-label">Cover image</label>
    <div class="col-sm-10">
        <div class="form-group">
            <div class="row">
                <div class="col-12 mb-3">
                    <input type="file" class="form-control-file @error('cover_image') is-invalid @enderror"
                        id="cover_image" name="cover_image">
                        <button type="button" class="btn btn-secondary" id="clear_image">Orignal Image</button>
                    @error('cover_image')
                        <div id="cover_image_error" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                {{-- <span class="alert alert-warning col-6"> Image size around 640x390 is hightly recommended</span> --}}
                <div id="cover_preview" class="col-12">
                    <p>Cover preview:</p>

                    <img id="cover_preview_image" src="{{ asset(isset($cover_image_path) ?
                        'storage/'.$cover_image_path . '?' . rand() : 'images/logo/logo.png') }}"
                        alt="Cover preview" style="max-width: 640px; height:auto;">
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        const removeCoverInput = document.querySelector('input#remove_cover_image');
        const coverImageSection = document.querySelector('#cover_image_section');
        const coverImageInput = document.querySelector('input#cover_image');
        const coverPreviewContainer = document.querySelector('#cover_preview');
        const coverPreviewImage = document.querySelector('img#cover_preview_image');

        // const switch_checked = document.getElementById('flexSwitchCheckDefault').checked

        coverImageInput.onchange = event => {
            const [file] = coverImageInput.files;
            if (file) {
                coverPreviewImage.src = URL.createObjectURL(file);

            }
        };

        const clear_image = document.querySelector('button#clear_image');
        clear_image.click = function(){
        clearInputFile(coverImageInput);
        };

        function clearInputFile(f){
            f.value = '';
            coverPreviewImage.src =  @js(asset(isset($cover_image_path) ?
                        'storage/'.$cover_image_path . '?'. rand() : 'images/logo/logo.png'));
        };
    });
</script>

