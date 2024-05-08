@props(['route', 'button_id' , 'text' => "" ,'text' => ''])

@php
$class="btn btn-success inline-flex items-center justify-center";
$title="Accept";
$icon="fa-solid fa-check";

@endphp

@include('components.buttons.atomic.form_put',
    ['route' => route('teamuser.accept_invitation', ['team'=>$team]), 'button_id' => 'accept_pending_'.$team->id , 'text' => 'Accept', 'title' => 'Accept', 'class' => $class, 'icon'=>$icon])
