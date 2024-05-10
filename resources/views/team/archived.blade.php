@extends('layouts.app')
@section('title', 'Archived Team Management')

@section('content')

<div class="container">
    <div class="row justify-content-between">
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
        <div class="col p-3">

            <h1>Manage Archived Team's Owned
            </h1>

        </div>

        <div class="col p-3">
            <div class="d-flex justify-content-end gap-3">
                <div class="p-3 m-3 text-end">
                    <h1>{{$user->name}}</h1>
                    <p>{{$user->email}}</p>
                    @include('surveys.surveys.partials.buttons.to_surveys',['base_route'=>'team.index'])
            </div>
        </div>


    </div>
    <hr>

    <div class="d-flex justify-content-center row mt-3">
        <div class="col-12 col-lg-9">
            <div class="row">
                <h2>Team Ownerships</h2>
                @if($user->teams()->onlyTrashed()->count() > 0)

                @include('team.partials.tables.archived_teams')

                @else
                <div class="col-12">
                    <div class="alert alert-warning" role="alert">
                        No Teams Archived.
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
