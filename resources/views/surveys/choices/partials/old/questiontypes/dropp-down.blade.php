<div class="form-control">
    <select name="choice_ids[]" id="choice_id" class="form-control">

    @forelse ($choices as $choice)

    <option value="{{ $choice->id }}">
        {{ $choice->choice_text }}
    </option>

    @empty
        <div class="col-span-3 px-2 py-4 bg-blue-100">
            No choices yet!
        </div>
    @endforelse

    </select>
</div>

