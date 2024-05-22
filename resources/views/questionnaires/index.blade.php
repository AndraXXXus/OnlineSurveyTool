@extends('layouts.app')
@section('title', 'Questionnaires')

@section('content')

<div class="container">
    <div class="row justify-content-between">
        <div x-data="{show: true}" x-init="setTimeout(() => show = false, 5000)" x-show="show">
            @if (Session::has('questionnaire_deleted'))
                <div class="alert alert-success">
                    Questionnaire successfully softdeleted!
                </div>
            @endif
            @if (Session::has('questionnaire_forcedeleted'))
                <div class="alert alert-success">
                    Questionnaire permanently deleted!
                </div>
            @endif
            @if (Session::has('questionnaire_restored'))
            <div class="alert alert-success">
                Questionnaire successfully restored!
            </div>
            @endif
            @if(Auth::User()->invitations->count() > 0)
            <div class="alert alert-warning">
                You have pending invitations!
            </div>
            @endif
        </div>

        <div class="col p-3">
            <h1>{{ isset($archive) ? 'Archived' : '' }} Team Questionnaires
            </h1>
        </div>

        <div class="col p-3">
            <div class="d-flex justify-content-end gap-3">
                @if(!isset($archive))
                    @include('questionnaires.partials.buttons.to_archives')
                @else
                    @include('questionnaires.partials.buttons.to_questionnaires')
                @endif
            </div>
        </div>


    </div>
    <hr>

    @forelse ($teams as $team)

    @php
        $surveys =  isset($archive) ? $team->questionnaires()->onlyTrashed()->get() : $team->questionnaires;
    @endphp
        {{-- @if($team->surveys->count()>0) --}}
            <div class="d-flex justify-content-center row mt-3">
                <div class="col-12 col-lg-9">
                    <div class="row">
                        <h1>{{$team->team_name }} @if($team->user_id === Auth::id() ) * @endif</h1>

                        <hr>
                        @forelse ($surveys->sortByDesc('updated_at') as $survey)

                            @include('questionnaires.partials.cards.card')
                        @empty
                        <div class="col-12">
                            <div class="alert alert-warning" role="alert">
                                {{ isset($archive) ? 'No archived questionnaires yet!' : 'No questionnaires for this team yet!' }}
                                @if(Auth::User()->teams()->count() === 0)
                                    You are not a member any team!
                                @endif
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        {{-- @endif --}}
    @empty
    <div class="col-12">
        <div class="alert alert-warning" role="alert">
            {{ isset($archive) ? 'No archived questionnaires for any of your teams yet!' : 'None of your teams have questionnaires yet!' }}
        </div>
    </div>
    @endforelse

</div>
@endsection
