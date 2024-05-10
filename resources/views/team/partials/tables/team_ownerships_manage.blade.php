<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Team name</th>
            <th scope="col"> Edit Team</th>
            <th scope="col">Team Leader Name</th>
            <th scope="col">Give away Team Leadership</th>
            <th scope="col">Delete Team</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($user->teams_owned() as $team)

        <tr>
            <th scope="row">{{$team->team_name}}</th>
            <td>@include('team.create')</td>
            <td>{{$team->teamleader()->name}}</td>
            <td>@include('team.partials.buttons.change_teamleader_modal',['title'=> 'Change Team Leader', 'data_bs_target'=> 'chage_teamleader_'.$team->id])</td>
            <td>@include('team.partials.buttons.archive_team')</td>
        </tr>

        @endforeach
        </tbody>
    </table>
</div>
