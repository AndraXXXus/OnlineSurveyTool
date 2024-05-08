@if($question->positionCanIncrease()===true)
    @php
    $type='questions.movedown';
    $name="tobemoveddown";
    $title="MoveDown";
    $icon = "fa-solid fa-arrow-down";
    @endphp
    @include('surveys.questions.partials.buttons.move.move',["icon"=>$icon,"name","title","type"])
@else
@include('surveys.questions.partials.buttons.move.xmark')
@endif
