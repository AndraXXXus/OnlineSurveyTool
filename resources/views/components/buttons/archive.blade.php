@props(['route', 'button_id' , 'text' => ''])

@php
$class = "btn btn-danger inline-flex items-center justify-center";
$title = "Archive";
$icon = "fa-solid fa-trash";
@endphp

@include('components.buttons.atomic.form_delete',
        ['route' => $route, 'button_id' => $button_id ,'title' => $title, 'class' => $class, 'text'=>$text, 'icon'=>$icon])
