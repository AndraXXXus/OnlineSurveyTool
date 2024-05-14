
<img
src="{{ isset($question->cover_image_path) ? asset('storage/'.$question->cover_image_path . '?' . rand() ) : asset('images/logo/logo.png') }}"
    alt="Question cover"
    class="card-img-top"
    id = "card-img-top"
    style="width: auto; max-height:390px;"
/>

<script>
    document.getElementById("card-img-top").style['max-height'] = (Math.min(0.60*window.innerWidth,640)/640*390).toString()
    // width: Math.min(0.80*window.innerWidth,640).toString(),
    //         height: (Math.min(0.80*window.innerWidth,640)/640*390).toString(),
</script>

