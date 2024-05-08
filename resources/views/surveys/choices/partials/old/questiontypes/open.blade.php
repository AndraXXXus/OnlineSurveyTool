<div class="form-control">
    @forelse ($choices as $choice)
    <label for="choice_text" class="block text-lg font-medium text-gray-700">Answer</label>
    <input name="choice_id" type="hidden" value="{{ $choice->id }}">
    <input type="text" name="choice_text" id="choice_text" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm @error('choice_text') border-red-500 @else border-gray-300 @enderror" value="">
    @empty
        <div class="col-span-3 px-2 py-4 bg-blue-100">
            No choices yet!
        </div>
    @endforelse
</div>
