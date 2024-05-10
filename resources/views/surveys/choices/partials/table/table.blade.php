
<div class="card">
    <div class="card-body">
        @if ($choices->count() === 0 )
            <div class="col-12">
                <div class="alert alert-warning " role="alert">
                    {{ isset($archive) ? 'No archived choices yet!' : 'No choices yet!' }}
                </div>
            </div>
        @else

        @isset($archive)
            <div class="table-responsive table-hover table-bordered">
        @else
            <div class="table-responsive table-bordered">
        @endisset

            <caption>List of choices</caption>

        <table class="table text-center">
            @isset($archive)
                <thead class="table-dark">
            @else
                <thead class="table-secondary">
            @endisset

                <tr>
                    @isset($archive)

                        <th scope="col"> {{ ($question->question_type === "open")  ? "Placeholder text" : "Choice text*"}} </th>

                        <th scope="col">Restore</th>
                        <th scope="col">Perma-delete</th>

                    @else
                        <th scope="col">Position</th>
                        <th scope="col">{{ ($question->question_type === "open")  ? "Placeholder text" : "Choice text*"}}</th>

                        <th scope="col">Move Up</th>
                        <th scope="col">Move Down</th>

                        <th scope="col">Edit</th>

                        <th scope="col">Archive</th>
                    @endisset
                </tr>
            </thead>
            <tbody>
                @foreach($choices as $choice)

                    @isset($archive)

                        <tr>
                            <tr>
                                <td scope="row" title="{{$choice->id}}">{{$choice->choice_text}}</td>

                                <td>@include('surveys.choices.partials.buttons.restore',['base_route'=>'choices.restore'])</td>
                                <td>@include('surveys.choices.partials.buttons.forcedelete',['base_route'=>'choices.forcedelete'])</td>
                            </tr>
                        </tr>

                    @else

                        <tr>
                            <th scope="row" title="{{$choice->choice_position}}"> {{$choice->choice_position}} </th>
                            <td title="{{$choice->choice_text}}">{{$choice->choice_text}}</td>

                            <td> @include('surveys.choices.partials.buttons.move.moveup') </td>
                            <td> @include('surveys.choices.partials.buttons.move.movedown') </td>

                            <td> @include('surveys.choices.partials.buttons.edit',['base_route'=>'choices.edit'])</td>
                            <td> @include('surveys.choices.partials.buttons.archive',['base_route'=>'choices.destroy'])</td>
                        </tr>

                    @endisset

                @endforeach

            </tbody>
        </table>
        @endif
    </div>
</div>
