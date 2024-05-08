@props(['route', 'button_id' => "" ,'text' => '', 'title'=>""])

@php
$class="btn btn-primary inline-flex items-center justify-center";
$icon = "fa-solid fa-list";
@endphp

@include('components.buttons.atomic.link_to',
    ['route' => $route, 'button_id' => $button_id , 'text' => "",'title' => $title, 'class' => $class, 'text'=>$text, 'icon'=>$icon])


