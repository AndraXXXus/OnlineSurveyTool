@props(['route', 'button_id' ,'text' => ''])

@php
$class="btn btn-info inline-flex items-center justify-center";
$title="Clone";
$icon = "fa-regular fa-copy";
@endphp

@include('components.buttons.atomic.form_post',
    ['route' => $route, 'button_id' => $button_id , 'text' => "",'title' => $title, 'class' => $class, 'text'=>$text, 'icon'=>$icon])
