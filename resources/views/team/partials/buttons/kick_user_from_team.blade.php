
@php
        $route = route('teamuser.kick',['team' => $team, 'user' => $team_member]);
        $button_id = 'from_team_' . $team->id . 'kick_member_' . $team_member->id;
        $title = 'Kick Member and gain their surveys';
        $text = "Kick Member and gain ownership of the member's team associated surveys";
        $icon = "fa-solid fa-user-xmark";
        $class = "btn btn btn-danger";
        $data_bs_target= 'modal_from_team_' . $team->id . 'kick_member_' . $team_member->id;
    @endphp


<button title={{$title}} class="btn btn btn-danger" data-bs-toggle="modal" data-bs-target="#{{$data_bs_target}}">
    <span><i class="{{$icon}}"></i> {{$title}}  </span>
</button>

<div class="modal fade" id="{{$data_bs_target}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id={{ $button_id }} method="POST" action="{{ $route }}">
            @method('put')
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Confirm Team Leader Change</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to kick <strong>{{$team_member->name}}</strong> from
                    <strong>{{ $team->team_name }}</strong>?
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button title="Cancel" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

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

                </div>
            </div>
        </form>
    </div>
</div>


