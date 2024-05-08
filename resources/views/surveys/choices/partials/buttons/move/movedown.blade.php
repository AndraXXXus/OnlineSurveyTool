@if($choice->positionCanIncrease()===true)
    @php
    $type='choices.movedown';
    $name="tobemoveddown";
    $title="MoveDown";
    $icon="fa-solid fa-arrow-down";
    @endphp
    @include('surveys.choices.partials.buttons.move.move')
@else
@include('surveys.choices.partials.buttons.move.xmark')
@endif
