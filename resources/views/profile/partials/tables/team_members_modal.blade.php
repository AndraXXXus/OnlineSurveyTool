<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target={{"#teamMember_".$team->id}} >
    Team Members
</button>

<div class="modal fade" id={{"teamMember_".$team->id}} tabindex="-1" aria-labelledby={{"teamMember_".$team->id}} aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Team: {{$team->team_name}} members</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">


            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Memeber name</th>
                        <th scope="col">Memeber email</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($team->members as $user)

                    <tr>
                        <th scope="row">{{$user->name}} @if($user->id === $team->user_id) * @endif @if($user->id === Auth::id()) (You) @endif </th>
                        <td>
                            {{$user->email}}
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>




        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
