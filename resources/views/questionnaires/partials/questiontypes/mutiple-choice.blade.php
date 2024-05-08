

<div id="multiple" class="row row-cols-xl-6  d-flex justify-content-center align-items-center">

    @forelse ($choices as $choice)
        <div class="col-xl-6">
            <div class="form-control m-2 @error('choice_ids') is-invalid @enderror">
                <label title="{{ $choice->choice_text }}" for={{"choice_id_".$choice->id}} class="form-control-label">
                    <input
                    type="checkbox"
                    name = "choice_ids[]"
                    class="form-control-input"
                    id={{"choice_id_".$choice->id}} value="{{ $choice->id }}"
                    onchange="handleMultipleOnChange()"
                    @checked( old('choice_ids') ? in_array($choice->id, old('choice_ids')) : (count($previous_answers_choice_ids)>0 ? in_array($choice->id, $previous_answers_choice_ids) : false) ) />

                    {{ $choice->choice_text }}

                </label>
            </div>

        </div>

    @empty
    <div style="color:red; ">
        No choices yet!
    </div>
    @endforelse



</div>

@if ($errors->any())

    <div class="alert alert-danger m-4">
        <ul>
            @foreach ($errors->all() as $message)
            <span class="text-danger d-block" role="alert">
                {{ $message }}
            </span>
            @endforeach
        </ul>
    </div>
@endif

<script>
    function handleMultipleOnChange() {
        if (@js($question->question_required)==true) {
            const submit_button = document.getElementById('questionnaire_form_submit_button');
            if(submit_button){
                const any_input_checked = document.querySelectorAll('#multiple input:checked').length > 0;
                const video_seen =  submit_button.getAttribute('data-video-seen')==="true";

                submit_button.setAttribute('data-inputs-valid',any_input_checked);

                submit_button.disabled = !(any_input_checked && video_seen);
            }
        }
    }
</script>
