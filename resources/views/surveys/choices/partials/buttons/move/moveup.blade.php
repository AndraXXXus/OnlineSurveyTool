@if($choice->positionCanDecrease()===true)
    @php
    $type='choices.moveup';
    $name="tobemovedup";
    $title="MoveUp";
    $icon="fa-solid fa-arrow-up";
    @endphp
    @include('surveys.choices.partials.buttons.move.move')
@else
@include('surveys.choices.partials.buttons.move.xmark')
@endif
