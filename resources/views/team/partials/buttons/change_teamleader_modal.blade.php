@php
    $route = route('team.giveawayteamleadership',$team);
    $button_id = 'giveawayteamleadership_'.$team->id;
    $text = 'Give away team-leadership';
    $title = 'Give away team-leadership';
    $icon = "fa-solid fa-crown";
    $class = "btn btn btn-danger";
@endphp


@if($team->members->count()>1)
    <button title={{$title}} class="btn btn btn-warning" data-bs-toggle="modal" data-bs-target="#{{$data_bs_target}}">
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
                        Are you sure you want to give away the ownership of this team:
                        <strong>{{ $team->team_name }}</strong>?
                        <br>
                        <div class="form-group row mb-3 p-2">
                            <label for="new_teamleader_user_id" class="col-sm-2 col-form-label">To: *</label>
                            <div class="col-sm-10">
                                <select name="new_teamleader_user_id" id="new_teamleader_user_id" class="form-control @error('new_teamleader_user_id') is-invalid @enderror" >

                                        @foreach ($team->members as $user)
                                            <option value="{{$user->id}}" {{$user->id === old('new_teamleader_user_id', $user->id) ? 'selected' : '' }}>
                                                @if ($user->id === Auth::id())
                                                    (You),
                                                @endif
                                                {{$user->name}}, {{$user->email}}
                                            </option>
                                        @endforeach
                                </select>
                                @error('new_teamleader_user_id')
                                    <span class="text-danger d-block" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>


                        </div>
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
@else
No other members
@endif
