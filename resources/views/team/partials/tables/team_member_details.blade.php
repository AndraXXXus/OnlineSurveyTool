<div class="card p-4">
    <h4>Invite member to Team: "{{$team->team_name}}" via their e-mail address</h4>
    <div class="mb-4">
        @include('team.partials.forms.send_team_invite')
    </div>
</div>
<hr>
<div class="card p-4">
    <div class="d-flex justify-content-center row mb-3">
        @if($team->invitations->count() > 0)
        @include('team.partials.tables.teams_members_invited')
        @else
        <div class="col-12">
            <div class="alert alert-warning" role="alert">
                No pending invitations.
            </div>
        </div>
        @endif
    </div>
</div>
<hr>
<div class="card p-4">
    <div class="d-flex justify-content-center row mb-3">
        @if($team->members->count() > 1)
        @include('team.partials.tables.teams_owned_members')
        @else
        <div class="col-12">
            <div class="alert alert-warning" role="alert">
                No other members beside you
            </div>
        </div>

        @endif
    </div>
</div>
