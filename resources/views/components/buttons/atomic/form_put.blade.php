@props(['button_id' ,'route','title', 'class', 'text','icon'])

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
