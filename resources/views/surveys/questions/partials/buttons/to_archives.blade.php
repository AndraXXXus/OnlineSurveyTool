@props(['base_route'])

@include('components.buttons.to_archives',
    [
        'route'=> route($base_route,['survey'=>$survey]),
        'button_id'=> $base_route.'_'.$survey->id,
        'title'=>"To Archived Questions",
        ])
