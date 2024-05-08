<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Team name</th>
            <th scope="col">Team Leader Name</th>
            <th scope="col">Accept</th>
            <th scope="col">Decline</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($user->invitations as $team)
        <tr>
            <th scope="row">{{$team->team_name}}</th>
            <td>{{$team->teamleader()->name}}</td>
            <td>@include('profile.partials.buttons.accept_invitation')</td>
            <td>@include('profile.partials.buttons.decline_invitation')</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
