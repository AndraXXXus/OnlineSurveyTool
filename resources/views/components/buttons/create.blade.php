@props(['route', 'button_id' => "" ,'text' => ''])

@php
$class="btn btn-success rounded-circle inline-flex items-center justify-center";
$title="Create New";
$icon = "fa-solid fa-add";
@endphp

@include('components.buttons.atomic.link_to',
    ['route' => $route, 'button_id' => $button_id , 'text' => "",'title' => $title, 'class' => $class, 'text'=>$text, 'icon'=>$icon])
