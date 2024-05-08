
<img
src="{{ isset($question->cover_image_path) ? asset('storage/'.$question->cover_image_path . '?' . rand() ) : asset('images/logo/logo.png') }}"
    alt="Question cover"
    class="card-img-top"
    style="width: auto; max-height:390px;"
/>
