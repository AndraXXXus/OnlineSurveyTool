@props(['button_id' ,'route','title', 'class', 'text','icon'])

<a
    class= "{{$class}}"
    title= "{{$title}}"
    href = "{{ $route }}"
    id = "{{$button_id}}"
    >
    <i class="{{$icon}}"></i>
    {{$text}}
</a>
