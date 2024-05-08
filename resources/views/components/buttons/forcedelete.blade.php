@props(['route', 'button_id' ,'text' => 'Yes, permanently delete this'])

@php
$class = "btn btn-danger inline-flex items-center justify-center";
$title = "Permanently delete";
$icon = "fa-solid fa-trash";
@endphp

@include('components.buttons.atomic.form_delete',
    ['route' => $route, 'button_id' => $button_id , 'title' => $title, 'class' => $class, 'text'=>$text, 'icon'=>$icon])

