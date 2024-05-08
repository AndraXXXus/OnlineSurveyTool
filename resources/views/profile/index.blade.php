@extends('layouts.app')
@section('title', 'User Profile')

@section('content')

<div class="container">
    <div x-data="{show: true}" x-init="setTimeout(() => show = false, 5000)" x-show="show">
        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
    </div>
    <div class="row justify-content-between">

        <div class="col p-3">
            <h1>User Profile
            </h1>
        </div>

        <div class="col p-3">
            <div class="d-flex justify-content-end gap-3">
                <div class="p-3 m-3 text-end">
                    <h1>{{$user->name}}</h1>
                    <p>E-mail Address: {{$user->email}}</p>
                    <p>User ID: {{$user->id}}</p>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="col p-3">
        <h3>Update User Details</h3>
        @include('profile.partials.userforms.user_details')
    </div>
    <hr>
    <div class="col p-3">
        <h3>Change User Password</h3>
        @include('profile.partials.userforms.change_user_password')
    </div>
    <hr>
    <div class="col p-3">
        <h3 >Delete Profile</h3>
        @include('profile.partials.buttons.delete_profile')
    </div>

    <hr>
    <div class="col p-3">
        <h1> User's Team memberships management
        </h1>
    </div>
    <hr>
    <div class="d-flex justify-content-center row mt-3">
        <div class="col-12 col-lg-9">
            <div class="row">
                <h2>Accept Team Invitations</h2>
                @if($user->invitations->count() > 0)

                @include('profile.partials.tables.accept_invitations')

                @else
                <div class="col-12">
                    <div class="alert alert-warning" role="alert">
                        No pending invitations.
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <hr>
    <div class="d-flex justify-content-center row mt-3">
        <div class="col-12 col-lg-9">
            <div class="row">
                <h2>Team Memberships</h2>
                @if($user->teams->count() > 0)

                @include('profile.partials.tables.leave_teams')

                @else
                <div class="col-12">
                    <div class="alert alert-warning" role="alert">
                        No pending invitations.
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <hr>

</div>
@endsection
