
<div id="open" class="row row-cols-xl-6  d-flex justify-content-center align-items-center">

    @forelse ($choices as $choice)
        <div class="col-xl-6">
            <div class="form-control m-2">
                <div class="row p-1">
                <label for="choice_text" class="form-control-label" class="block text-lg font-medium text-gray-700"> {{ $choice->choice_text }}
                </label>

                <input
                    type="text"
                    title="text_input_{{ $choice->id }}"
                    name="open_ids_texts[{{ $choice->id }}]"
                    id="{{ 'input_texts_' . $choice->id }}"
                    class="form-control @error('open_ids_texts.' . $choice->id) is-invalid @enderror"
                    onkeyup="handleOpenOnChange()"
                    value="{{old('open_ids_texts.' . $choice->id, isset($previous_answers_choice_texts[$choice->id]) ? $previous_answers_choice_texts[$choice->id] : '' ) }}" />
                </div>

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
    function handleOpenOnChange() {
        // console.log(1);
        if (@js($question->question_required)==true) {
            const submit_button = document.getElementById('questionnaire_form_submit_button');

            if(submit_button){
                const all_inputtext_nonempty = Array.from(document.querySelectorAll('#open input')).filter(function(input) {
                return input.value.trim() === '';
            }).length === 0;

            submit_button.setAttribute('data-inputs-valid', all_inputtext_nonempty);

            const video_seen =  submit_button.getAttribute('data-video-seen')==="true";
            submit_button.disabled = !(all_inputtext_nonempty && video_seen);
            }
        }
    }
</script>
