@props(['route', 'button_id' , 'route' , 'text' => 'Go Live!'])

@php
$class = "btn btn-success text-center text-white inline-flex items-center justify-center";
$title = "Go live";
$icon = "fa-solid fa-check-square";
@endphp

@include('components.buttons.atomic.form_put',
    ['route' => $route, 'button_id' => $button_id , 'text' => "",'title' => $title, 'class' => $class, 'text'=>$text, 'icon'=>$icon])


