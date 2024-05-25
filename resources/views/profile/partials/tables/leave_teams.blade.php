<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Team name</th>
            <th scope="col">Team Leader Name</th>
            <th scope="col">Team Memebers</th>
            <th scope="col">Leave?</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($user->teams as $team)

        <tr>
            <th scope="row">{{$team->team_name}}</th>
            <td>{{$team->teamleader()->name}}</td>
            <td>
                @include('profile.partials.tables.team_members_modal')
            </td>
            @if($team->user_id != $user->id)
            <td>@include('profile.partials.buttons.leave_team')</td>
            @else
            <td><a href = "{{ route('team.index') }}"> Team Owned </a></td>
            @endif
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
