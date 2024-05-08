
<div class="card">
    <div class="card-body">
        @if ($questions->count() == 0 )
            <div class="col-12">
                <div class="alert alert-warning " role="alert">
                    {{ isset($archive) ? 'No archived questions yet!' : 'No questions yet!' }}
                </div>
            </div>
        @else

        @isset($archive)
            <table class="table-responsive table-hover table-bordered">
        @else
            <div class="table-responsive table-bordered">
        @endisset
            <caption>List of questions</caption>

        <table class="table text-center">
            @isset($archive)
            <thead class="table-dark">
            @else
            <thead class="table-secondary">
            @endisset
                <tr >
                    @isset($archive)

                        <th scope="col">Question text</th>
                        <th scope="col">Question type</th>

                        <th scope="col">Restore</th>
                        <th scope="col">Perma-delete</th>

                    @else
                        <th scope="col">Position</th>

                        <th scope="col">Question text</th>
                        <th scope="col">Question type</th>

                        <th scope="col">Move Up</th>
                        <th scope="col">Move Down</th>
                        <th scope="col">Answers</th>

                        <th scope="col">Edit</th>
                        <th scope="col">Clone</th>
                        <th scope="col">Archive</th>
                    @endisset
                </tr>
            </thead>
            <tbody>
                @foreach($questions as $question)

                    @isset($archive)

                        <tr>
                            <td scope="row" title="{{$question->id}}">{{$question->question_text}}</td>
                            <td>{{$question->question_type}}</td>

                            <td>@include('surveys.questions.partials.buttons.restore',['base_route'=>'questions.restore'])</td>
                            <td>@include('surveys.questions.partials.buttons.forcedelete',['base_route'=>'questions.forcedelete'])</td>
                        </tr>

                    @else

                        <tr @if($question->question_required==true) class="table-secondary" @endif>
                            <th scope="row" title="{{$question->question_position}}">{{$question->question_position}}@if($question->question_required==true) * @endif</th>

                            <td title="{{$question->question_text}}">{{$question->question_text}}</td>
                            <td title="{{$question->question_type}}">{{$question->question_type}}</td>

                            <td> @include('surveys.questions.partials.buttons.move.moveup') </td>
                            <td> @include('surveys.questions.partials.buttons.move.movedown') </td>
                            <td> @include('surveys.questions.partials.buttons.answers',['base_route'=>'survey.question.show']) </td>

                            <td> @include('surveys.questions.partials.buttons.edit',['base_route'=>'questions.edit'])</td>
                            <td> @include('surveys.questions.partials.buttons.clone',['base_route'=>'questions.clone'])</td>
                            <td> @include('surveys.questions.partials.buttons.archive',['base_route'=>'questions.destroy'])</td>
                        </tr>

                    @endisset

                @endforeach

            </tbody>
        </table>
        @endif
    </div>
</div>
