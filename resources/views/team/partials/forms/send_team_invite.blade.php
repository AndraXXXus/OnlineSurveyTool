@php
    $button_id = 'teamuser_invitation_' . $team->id;
    $route = route('teamuser.invitation', ['team'=> $team]);
    $class = "btn btn-success";
    $icon = "fa-solid fa-paper-plane";
@endphp

<form id={{ $button_id }} method="POST" action="{{ $route }}">
    @csrf
    <div class='row'>
        <div class="col-md-6">

            <input id="{{'email_'.$team->id}}" type="email" class="form-control @error('email_'.$team->id) is-invalid @enderror" name="{{'email_'.$team->id}}" value="{{ old('email_'.$team->id) }}" required>

            @error('email_'.$team->id)
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-4">
            <a
                title="Send Team Invitation"
                class = "{{$class}}"
                href="{{ $route }}"
                onclick="event.preventDefault();
                document.getElementById('{{$button_id}}').submit();"
            >

                <i class="{{$icon}}"></i>

                "Send Invitation"

            </a>
        </div>
    </div>
</form>

