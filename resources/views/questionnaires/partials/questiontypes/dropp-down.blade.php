<div id="dropp" class="form-group d-flex justify-content-center align-items-center">
    <select
    name="choice_id"
    id="choice_id"
    title="select"
    class="form-select @error('choice_id') is-invalid @enderror"
    onchange="handleDroppDownOnChange()">

        <option id="ommit_select" class="form-group-option" @disabled(count($previous_answers_choice_ids)===0) @selected(count($previous_answers_choice_ids)===0)  >
            {{count($previous_answers_choice_ids)===0 ? 'Pick an choice' : 'Skip question'}}
        </option>
        @forelse ($choices as $choice)

            <option
            title="{{ $choice->choice_text }}"
            class="form-group-option"
            value="{{ $choice->id }}"
            @selected( old('choice_id') ? $choice->id === old('choice_id') : (count($previous_answers_choice_ids)>0 ? in_array($choice->id, $previous_answers_choice_ids) : false) ) >
                {{ $choice->choice_text }}
            </option>

    @empty
    <div style="color:red; ">
        No choices yet!
    </div>
    @endforelse

    </select>

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
    function handleDroppDownOnChange() {
        if (@js($question->question_required==true)){
            const submit_button = document.getElementById('questionnaire_form_submit_button');
            if(submit_button){
                const any_input_valid_selected = document.getElementById('choice_id').selectedIndex > 0;
                const video_seen =  submit_button.getAttribute('data-video-seen')==="true";

                submit_button.setAttribute('data-inputs-valid',any_input_valid_selected);

                submit_button.disabled = !(any_input_valid_selected && video_seen);
            }
        };

        if (document.getElementById('choice_id').selectedIndex!=0){
            document.getElementById('ommit_select').removeAttribute('disabled');
            document.getElementById('ommit_select').text =  'Skip question';
        }
        else{
            document.getElementById('ommit_select').setAttribute('disabled',true);
            document.getElementById('ommit_select').text =  'Pick a choice';
        };
    }
</script>
