@extends('layouts.app')
@section('title', 'Surveys')

@section('content')

<div class="container">
    <div class="row justify-content-between">

        <div class="col p-3">
            <h1>{{ isset($archive) ? 'Archived' : '' }} User Surveys
            </h1>
        </div>

        <div class="col p-3">
            <div class="d-flex justify-content-end gap-3">
                @if(!isset($archive))
                    @if(Auth::User()->teams()->count() > 0)
                        @include('surveys.surveys.partials.buttons.create',['base_route'=>'surveys.create'])
                    @endif
                    @include('surveys.surveys.partials.buttons.to_archives',['base_route'=>'surveys.archive'])
                @else
                    @include('surveys.surveys.partials.buttons.to_surveys',['base_route'=>'surveys.index'])
                @endif
            </div>
        </div>


    </div>
    <hr>

    <div x-data="{show: true}" x-init="setTimeout(() => show = false, 5000)" x-show="show">
        @if (Session::has('survey_deleted'))
            <div class="alert alert-success">
                Survey successfully softdeleted!
            </div>
        @endif
        @if (Session::has('survey_forcedeleted'))
            <div class="alert alert-success">
                Survey permanently deleted!
            </div>
        @endif
        @if (Session::has('survey_cloned'))
            <div class="alert alert-success">
                Survey successfully cloned!
            </div>
        @endif
        @if(Auth::User()->invitations->count() > 0)
            <div class="alert alert-warning">
                You have pending invitations!
            </div>
        @endif
    </div>

    <div class="d-flex justify-content-center row mt-3">
        <div class="col-12 col-lg-9">
            <div class="row">
                @forelse (Auth::User()->teams as $team)
                    <h1>{{$team->team_name }} @if($team->user_id === Auth::id() ) * @endif</h1>

                    <hr>
                    @php
                        $surveys = isset($archive) ? $team->unpublished_surveys()->onlyTrashed()->get() : $team->unpublished_surveys;
                    @endphp

                    @forelse ($surveys->sortByDesc('updated_at')->where('user_id',Auth::id()) as $survey)

                        @include('surveys.surveys.partials.cards.card')
                    @empty
                    <div class="col-12">
                        <div class="alert alert-warning" role="alert">
                            {{ isset($archive) ? 'No archived surveys yet!' : 'No surveys yet!' }}
                        </div>
                    </div>
                @endforelse
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning" role="alert">
                            @if(Auth::User()->teams()->count() === 0)
                            <a
                                title="To Team Management"
                                href="{{ route('team.index') }}"
                                >
                                <h3 style="color:red">You are not a member any team! <br>
                                    You need to create one or join one to be able to create surveys!</h3>
                                </a>

                            @endif
                        </div>
                    </div>
                @endforelse
            </div>


        </div>
    </div>
</div>
@endsection
