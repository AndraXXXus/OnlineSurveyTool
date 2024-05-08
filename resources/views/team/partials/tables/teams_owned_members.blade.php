<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Team Member's Name</th>
            <th scope="col">Team Member's E-mail</th>
            <th scope="col">Kick Member </th>
        </tr>
        </thead>
        <tbody>
        @foreach ($team->members as $team_member)
        @if($team_member->id != $team->user_id)
        <tr>
            <th scope="row">{{$team_member->name}}</th>
            <td>{{$team_member->email}}</td>
            <td>@include('team.partials.buttons.kick_user_from_team')</td>
        </tr>
        @endif
        @endforeach
        </tbody>
    </table>
</div>
