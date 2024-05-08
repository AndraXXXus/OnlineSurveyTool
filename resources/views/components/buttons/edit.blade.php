@props(['route', 'button_id' , 'text' => "" ,'text' => ''])

@php
$class="btn btn-rebeccapurple inline-flex items-center justify-center";
$title="Edit";
$icon="fa-solid fa-edit";
@endphp

@include('components.buttons.atomic.link_to',
    ['route' => $route, 'button_id' => $button_id , 'text' => "",'title' => $title, 'class' => $class, 'icon'=>$icon])

