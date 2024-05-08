@props(['base_route'])

@include('components.buttons.to_archives',
    [
        'route'=> route($base_route,['question'=>$question]),
        'button_id'=> $base_route.'_'.$question->id,
        'title'=>"To Archived Choices",
        ])
