@php
    $route = @isset($team) ? route('team.update', $team) : route('team.store');
    $button_id = @isset($team) ? 'edit_team_'.$team->id : 'create_team';
    $data_bs_target = @isset($team) ? 'data_bs_target_edit_team_'.$team->id : 'data_bs_target_create_team';
    $text = @isset($team) ? 'Edit Team Name' : 'Create my new Team';
    $title = @isset($team) ? 'Edit Team Name' :'Create my new Team';
    $icon = @isset($team) ? "fa-solid fa-edit" : "fa-solid fa-add";
    $class = @isset($team) ? "btn btn btn-primary" : "btn btn btn-success";
    $modal_class = @isset($team) ? "btn btn btn-primary" : "btn btn btn-success rounded-circle";
    $id = @isset($team) ? "team_name_".$team->id : "team_names";
@endphp


<button title={{$title}} class="{{$modal_class}}" data-bs-toggle="modal" data-bs-target="#{{$data_bs_target}}">
    <span><i class="{{$icon}}"></i>  </span>
</button>


<div class="modal fade" id="{{$data_bs_target}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id={{ $button_id }} method="POST" action="{{ $route }}"  enctype="multipart/form-data">
        @isset($team)
            @method('put')
        @endisset
            @csrf

        <div class="form-group row mb-3 p-2">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Team Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group row mb-3">
                        <label for="team_name" class="col-sm-2 col-form-label">Name*</label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control @error('team_name') is-invalid @enderror"
                                id="team_name"
                                name="team_name"
                                value="{{ old('team_name', isset($team) ? $team->team_name : '') }}">

                            @error('team_name')
                            <span class="text-danger d-block" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>
                    </div>
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
        </div>
        </form>
    </div>
</div>

