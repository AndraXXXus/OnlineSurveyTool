@props(['route', 'button_id' , 'text' => ''])

@php
$class="btn btn-primary inline-flex items-center justify-center text-center text-white";
$title="Restore";
$icon = "fa-solid fa-redo-alt";
@endphp

@include('components.buttons.atomic.form_post',
    ['route' => $route, 'button_id' => $button_id , 'text' => "", 'title' => $title, 'class' => $class, 'text'=>$text, 'icon'=>$icon])


