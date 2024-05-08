<form method="POST" action="{{ route($type, ['choice'=>$choice]) }}">
    @method('PUT')
    @csrf
    <button class="btn btn-light" title={{$title}} type="submit" name={{$name}} value="{{ $question->id }}">

            <i class="{{$icon}}"></i>

    </button>
</form>
