<div class="form-control">
    @forelse ($choices as $choice)
    <label for={{"choice_id_".$choice->id}} class="form-control">
        <input type="radio" name = "choice_ids[]" class="" id={{"choice_id_".$choice->id}} value="{{ $choice->id }}">
        {{ $choice->choice_text }}
        </label>

    </div>
    @empty
        <div class="col-span-3 px-2 py-4 bg-blue-100">
            No choices yet!
        </div>
    @endforelse


</div>
