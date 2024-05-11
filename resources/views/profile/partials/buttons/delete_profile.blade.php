@php
$route = route('profile.destroy');
$button_id = 'forcedelete_team_'.Auth::id();
$text = 'Permanently Delete Team and lose access to it';
$title = 'Permanently Delete User and lose access to it';
$icon = "fa-solid fa-trash";
$class = "btn btn btn-danger";
$data_bs_target= 'forcedelete_user_modal_'.Auth::id();
@endphp

<div class="mt-3 mb-3 text-center">
@if(Auth::User()->teams_owned_withArchived()->count() > 0)
    <a
    title="To Team Management"
    href="{{ route('team.index') }}"
    >
    <h3 style="color:red">You cannot own any <strong>{{Auth::User()->teams_owned()->count() > 0 ? "" : "Archived"}} Teams!</strong>  </h3>
    </a>
@else

<button title={{$title}} class="btn btn btn-danger" data-bs-toggle="modal" data-bs-target="#{{$data_bs_target}}">
    <span><i class="{{$icon}}"></i> {{$title}}  </span>
</button>

<div class="modal fade" id="{{$data_bs_target}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id={{ $button_id }} method="POST" action="{{ $route }}">
            @method('DELETE')
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Confirm Permanent User Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to PERMANENTLY DELETE your account,
                    <strong>{{ Auth::User()->name }}</strong>?

                    <br>
                    <strong>You will lose acces to it!</strong>
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

@endif
