<div id="youtube_player"></div>

<script>
    // 2. This code loads the IFrame Player API code asynchronously.
    var tag = document.createElement('script');

    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    // 3. This function creates an <iframe> (and YouTube player)
    //    after the API code downloads.
    var player;

    function onYouTubeIframeAPIReady() {
        player = new YT.Player('youtube_player', {
            width: Math.min(0.80*window.innerWidth,640).toString(),
            height: (Math.min(0.80*window.innerWidth,640)/640*390).toString(),
            videoId: @js(isset($question->video_no_controlls) ? $question->youtube_id : 'QohH89Eu5iM'),
            playerVars: {
            'playsinline': 1,
            'controls': @js($question->video_no_controlls ? 1 : 0),
            'disablekb': @js($question->video_no_controlls ? 1 : 0),
            // 'mute':1,
            },
            events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
            }
        });
    }

    // 4. The API will call this function when the video player is ready.
    function onPlayerReady(event) {
        event.target.playVideo();
    }

    // 5. The API calls this function when the player's state changes.
    //    The function indicates that when playing a video (state=1),
    //    the player should play for six seconds and then stop.
    function onPlayerStateChange(event) {
        // console.log(event.data)
        if (event.data===0) {
            player.stopVideo();
            const submit_button = document.getElementById('questionnaire_form_submit_button');
            if(submit_button){
                document.getElementById('questionnaire_form_submit_button').setAttribute('data-video-seen',true);
                const inputs_valid =  submit_button.getAttribute('data-inputs-valid')==="true";

                submit_button.disabled = !(inputs_valid && true);
            }
        }
    }
    function stopVideo() {
        player.stopVideo();
    }
</script>
