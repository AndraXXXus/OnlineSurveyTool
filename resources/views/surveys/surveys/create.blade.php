@extends('layouts.app')
@section('title', isset($survey) ? "Edit survey" : "New survey" )

@section('content')
<div class="container">
    <div class="col">
        @isset($survey)
            <h1 class="font-semibold text-3xl mb-4">{{"Edit survey" . $survey->survey_title}}</h1>
            <p class="mb-2">You can edit this Survey here.</p>
        @else
            <h1 class="font-semibold text-3xl mb-4">New survey</h1>
            <p class="mb-2">You can create a new Survey here.</p>
        @endif
    </div>

    @isset($survey)
    <form action="{{ route('surveys.update', ["survey" => $survey]) }}" method="POST" enctype="multipart/form-data">
        @method('put')
    @else
    <form action="{{ route('surveys.store') }}" method="POST" enctype="multipart/form-data">
    @endif
        @csrf

        <div class="form-group row mb-3 p-2">
            <label for="team_id" class="col-sm-2 col-form-label">Team*</label>
            <div class="col-sm-10">
                <select name="team_id" id="team_id" class="form-select @error('team_id') is-invalid @enderror" >
                    @isset($survey)
                        @foreach (Auth::User()->teams as $team)
                            <option class="form-group-option" value="{{$team->id}}" {{$team->id === old('team_id', $survey->team_id) ? 'selected' : '' }}>
                                {{$team->team_name}} @if($team->user_id === Auth::id() ) * @endif
                            </option>
                        @endforeach
                    @else

                        <option value="" selected disabled >Select a Team</option>

                        @foreach (Auth::User()->teams as $team)
                        <option value="{{ $team->id }}"
                        @if ($team->id == old('team_id'))
                            selected="selected"
                        @endif
                        >{{ $team->team_name, $team->id }}</option>
                        @endforeach
                    @endisset

                </select>
                @error('team_id')
                    <span class="text-danger d-block" role="alert">
                        {{ $message }}
                    </span>
                @enderror
            </div>


        </div>



        <div class="form-group row mb-3">
            <label for="survey_title" class="col-sm-2 col-form-label">Title*</label>
            <div class="col-sm-10">
                <input
                    type="text"
                    class="form-control @error('survey_title') is-invalid @enderror"
                    id="survey_title"
                    name="survey_title"
                    value="{{ old('survey_title', isset($survey) ? $survey->survey_title : '') }}">

                @error('survey_title')
                <span class="text-danger d-block" role="alert">
                    {{ $message }}
                </span>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="survey_description" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
                <input
                    type="text"
                    class="form-control @error('survey_description') is-invalid @enderror"
                    id="survey_description"
                    name="survey_description"
                    value="{{ old('survey_description', isset($survey) ? $survey->survey_description : '') }}">

                @error('survey_description')
                <span class="text-danger d-block" role="alert">
                    {{ $message }}
                </span>
                @enderror
            </div>
        </div>

        @if(isset($survey) && isset($survey->cover_image_path))
            @include('components.image_form.edit',['cover_image_path'=>$survey->cover_image_path])
        @else
            @include('components.image_form.create')
        @endisset

        <div class="text-center">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> {{isset($survey) ? $buttonLabel = "Update survey" : "Create new survey"}} </button>
        </div>

    </form>
</div>
@endsection



