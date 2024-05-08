<div class="form-group row mb-3">
    <label for="youtube_id" class="col-sm-2 col-form-label">Youtube ID</label>
    <div class="col-sm-10">
        <div class="form-group">
            <div class="row">
                <div class="col-12 mb-3">
                    <label>Youtube Video ID:</label>
                    <input
                        id="youtube_id"
                        type="text"
                        name="youtube_id"
                        class="form-control @error('youtube_id') is-invalid @enderror"
                        placeholder="example: Ahg6qcgoay4"
                        value="{{ old('youtube_id', isset($question) ? $question->youtube_id : '') }}">
                    @error('youtube_id')
                        <strong style="color:red">{{ $message }}</strong>
                    @enderror
                    @if(session()->has('message'))
                        <strong style="color:green">{{ session()->get('message') }}</strong>
                    @endif

                </div>
                <span id="youtube_id_error_messege" hidden=true class="text-danger" role="alert">
                    Not a valid Yotube ID!
                </span>
                <span id="youtube_id_passed_messege" hidden=true class="text-danger" role="alert">
                    <strong style="color:green">This is a valid Yotube ID!</strong>
                </span>

                @isset($question->youtube_id)
                <div id="youtube_preview"  class="{{"col-4" . !isset($question->youtube_id) ? '' : " d-none"}}">
                @else
                <div id="youtube_preview"  class="col-4 d-none">
                @endisset
                    <p>Video preview:</p>
                    <div alt="Video preview"  id="youtube_preview_video" class="embed-responsive embed-responsive-16by9">
                        <iframe title="video" id="yt_iframe"  class="embed-responsive-item" src="{{ isset($question->youtube_id) ? "https://www.youtube.com/embed/".$question->youtube_id : "https://www.youtube.com/embed/zGDzdps75ns"}}">

                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>


<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        const videoInput = document.querySelector('input#youtube_id');
        const videoPreviewContainer = document.querySelector('#youtube_preview');
        const previewVideo = document.querySelector('div#youtube_preview_video');
        const video_src = document.querySelector('#yt_iframe');

        videoInput.oninput = event => {

            function validVideoId(id) {
                var img = new Image();
                img.src = "http://img.youtube.com/vi/" + id + "/mqdefault.jpg";
                img.onload = function () {
                    if (this.width===120){
                        videoPreviewContainer.classList.add('d-none');
                        previewVideo.src = "https://www.youtube.com/embed/Ahg6qcgoay4";
                        youtube_id_error_messege.hidden = videoInput.value==="" || false;
                        youtube_id_passed_messege.hidden = true;
                    }else{
                        videoPreviewContainer.classList.remove('d-none');
                        video_src.src = "https://www.youtube.com/embed/" + videoInput.value;
                        youtube_id_error_messege.hidden = true;
                        youtube_id_passed_messege.hidden = false;
                    }
                }
            };
            validVideoId(videoInput.value);
        };
    });


</script>


