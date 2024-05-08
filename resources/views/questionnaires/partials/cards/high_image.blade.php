<div class="card col-xl-12 mb-3" >
    <div class="row g-0">
        <div class="col-xl-4">
            @include('questionnaires.partials.cards.image')
        </div>

        <div class="col-xl-8">
            <div class="card-header">
                <h5 class="card-title"> {{$question->question_text}} {{ $question->question_required == true ? "*" :""}} </h5>
            </div>
            <div class="card-body">
                @include('questionnaires.partials.cards.choices')
            </div>
            <div class="card-footer">
                <p class="card-text">
                    <small class="text-muted"> <i class="far fa-calendar-alt"></i> {{ $question->updated_at }}</small>
                </p>
            </div>
        </div>
    </div>
</div>
