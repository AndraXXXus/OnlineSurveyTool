<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Invited Users's Name</th>
            <th scope="col">Invited Users's E-mail</th>
            <th scope="col">Cancel Invitation</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($team->invitations as $invite)

        <tr>
            <th scope="row">{{$invite->name}}</th>
            <td>{{$invite->email}}</td>
            <td>@include('team.partials.buttons.cancel_invitation')</td>
        </tr>

        @endforeach
        </tbody>
    </table>
</div>
