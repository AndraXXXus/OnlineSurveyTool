@php
    $route = route('profile.destroy');
    $id = 'delete_profile_' . Auth::id();
@endphp

<div class="row mt-3 mb-3 text-center">
@if(Auth::User()->teams->count() > 0)
    <a
    title="To Team Management"
    href="{{ route('team.index') }}"
    >
    <h3 style="color:red">You need to delete all your teams / membersips first! </h3>
    </a>
@else
    <form id="{{ $id }}" method="POST" action="{{ $route }}">
        @method('DELETE')
        @csrf
        <a
            title="Delete Profile"
            class = "btn btn btn-danger"
            href="{{ $route }}"
            onclick="event.preventDefault();
            document.getElementById('{{ $id  }}').submit();"
        >

            <i class="fa-solid fa-trash"></i>

            Delete User

        </a>
    </form>
@endif
</div>
