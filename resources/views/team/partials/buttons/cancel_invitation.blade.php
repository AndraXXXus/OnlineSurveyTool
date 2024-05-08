@php
    $route = route('teamuser.cancel_invitation',['team'=>$team,'user'=>$invite->id]);
    $button_id = 'teamuser_cancel_invitation_'.$invite->id;
    $text = 'Withdraw Invitation';
    $title = 'Withdraw Invitation';
    $icon = "fa-solid fa-xmark";
    $class = "btn btn btn-warning";
@endphp
<form id={{ $button_id }} method="POST" action="{{ $route }}">
    @method('put')
    @csrf
    <a
        title="{{$title}}"
        class = "{{$class}}"
        href="{{ $route }}"
        onclick="event.preventDefault();
        document.getElementById('{{$button_id}}').submit();"
    >

        <i class="{{$icon}}"></i>

        {{$text}}

    </a>
</form>
