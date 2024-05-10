<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Team's Name</th>
            <th scope="col">Archived At</th>
            <th scope="col">Restore </th>
            <th scope="col">Permanently Delete </th>
        </tr>
        </thead>
        <tbody>
        @foreach ($user->teams()->onlyTrashed()->get() as $team)

        <tr>
            <th scope="row">{{$team->team_name}}</th>
            <td>{{$team->deleted_at}}</td>
            <td>@include('team.partials.buttons.restore_archived_team')</td>
            <td>@include('team.partials.buttons.forcedelete_team')</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
