
<img
src="{{ isset($survey->cover_image_path) ? asset('storage/'.$survey->cover_image_path . '?' . rand() ) : asset('images/logo/logo.png') }}"
    alt="Survey cover"
    class="img-fluid rounded"
    style="display: block; max-width: 100px; height:auto;"
/>
