@props(['route', 'button_id' , 'title'=> "To Archived" , 'text' => ""])

@php
$class="btn btn-secondary inline-flex items-center justify-center";
$icon="fa-solid fa-box-archive";
@endphp

@include('components.buttons.atomic.link_to',
    ['route' => $route, 'button_id' => $button_id , 'text' => "", 'title' => $title, 'class' => $class, 'text'=>$text])


