@if($question->positionCanDecrease()===true)
    @php
    $type='questions.moveup';
    $name="tobemovedup";
    $title="MoveUp";
    $icon = "fa-solid fa-arrow-up";
    @endphp
    @include('surveys.questions.partials.buttons.move.move')
@else
@include('surveys.questions.partials.buttons.move.xmark')
@endif
