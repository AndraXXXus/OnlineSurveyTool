@extends('layouts.app')
@section('title', 'Team Management')

@section('content')

<div class="container">
    <div class="row justify-content-between">
        <div class="col p-3">

            <h1>Manage Team's Owned
            </h1>
            @include('team.create')


        </div>

        <div class="col p-3">
            <div class="d-flex justify-content-end gap-3">
                <div class="p-3 m-3 text-end">
                    <h1>{{$user->name}}</h1>
                    <p>{{$user->email}}</p>
                    <p>{{$user->id}}</p>
                </div>
            </div>
        </div>
        <div x-data="{show: true}" x-init="setTimeout(() => show = false, 5000)" x-show="show">
            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif
            @if(session()->has('warning'))
                <div class="alert alert-warning">
                    {{ session()->get('warning') }}
                </div>
            @endif
            @if(session()->has('danger'))
            <div class="alert alert-warning">
                {{ session()->get('danger') }}
            </div>
        @endif
        </div>

    </div>
    <hr>

    <div class="d-flex justify-content-center row mt-3">
        <div class="col-12 col-lg-9">
            <div class="row">
                <h2>Team Ownerships</h2>
                @if($user->teams_owned()->count() > 0)

                @include('team.partials.tables.team_ownerships_manage')

                @else
                <div class="col-12">
                    <div class="alert alert-warning" role="alert">
                        No Teams Owned.
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <hr>
    <h1>Manage Owned Team's members</h1>
    @if($user->teams_owned()->count() > 0)
    <div class="d-flex justify-content-center row m-3">
        <div class="col-12 col-lg-9">
            <div class="row">

                @foreach ($user->teams_owned() as $team)
                <div class="card p-3 m-2">
                    <div class="card p-4">
                        <h4>{{$team->team_name}}</h4>
                        <div class="mb-4">
                            @include('team.partials.forms.send_team_invite')
                        </div>
                    </div>
                    <hr>
                    <div class="card p-4">
                        <div class="d-flex justify-content-center row mb-3">
                            @if($team->invitations->count() > 0)
                            @include('team.partials.tables.teams_members_invited')
                            @else
                            <div class="col-12">
                                <div class="alert alert-warning" role="alert">
                                    No pending invitations.
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="card p-4">
                        <div class="d-flex justify-content-center row mb-3">
                            @if($team->members->count() > 1)
                            @include('team.partials.tables.teams_owned_members')
                            @else
                            <div class="col-12">
                                <div class="alert alert-warning" role="alert">
                                    No other members beside you
                                </div>
                            </div>

                            @endif
                        </div>
                    </div>
                </div>


                @endforeach

            </div>

        </div>
    </div>
    @else
    <div class="col-12">
        <div class="alert alert-warning" role="alert">
            No Teams Owned.
        </div>
    </div>
    @endif
    <hr>

</div>
@endsection
