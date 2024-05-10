
@php
        $route = route('team.destroy',$team);
        $button_id = 'delete_team_'.$team->id;
        $text = 'Delete Team and all surveys attached to it';
        $title = 'Delete Team and all surveys attached to it';
        $icon = "fa-solid fa-trash";
        $class = "btn btn btn-danger";
        $data_bs_target= 'delete_team_modal_'.$team->id;
    @endphp

{{-- @if($team->members->count() > 1)
    Need to kick all other: {{$team->members->count()-1}} user(s)!
@else
    @if($team->surveys()->withTrashed()->count()>0)
    Team still has {{$team->surveys->count()}} survey(s) / {{$team->surveys()->onlyTrashed()->count()}} archived survey(s) left!
    @else --}}
        <button title={{$title}} class="btn btn btn-danger" data-bs-toggle="modal" data-bs-target="#{{$data_bs_target}}">
            <span><i class="{{$icon}}"></i> {{$title}}  </span>
        </button>

        <div class="modal fade" id="{{$data_bs_target}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form id={{ $button_id }} method="POST" action="{{ $route }}">
                    @method('DELETE')
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Confirm Team Leader Change</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to DELETE this team:
                            <strong>{{ $team->team_name }}</strong>?

                            <br>
                            <strong>This decision is permanent!</strong>
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
{{--
    @endif
@endif --}}
