@props(['base_route'])

@include('components.buttons.edit',
    [
        'route' => route($base_route, ['question' => $question]),
        'button_id'=> $base_route.'_'.$question->id
        ])

