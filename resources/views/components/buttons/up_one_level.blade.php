@props(['route', 'button_id' , 'text' => "", 'title'=>"Up One Level"])

@php
$class="btn btn-primary inline-flex items-center justify-center";
$icon="fa-solid fa-arrow-turn-up";
@endphp

@include('components.buttons.atomic.link_to',
    ['route' => $route, 'button_id' => $button_id ,'title' => $title, 'class' => $class, 'text'=>$text])




