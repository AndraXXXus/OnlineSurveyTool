<form method="POST" action="{{ route('profile.update')}}" enctype="multipart/form-data">
    @method('put')
    @csrf

    <div class="row mb-3">
        <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('User Name') }}</label>

        <div class="col-md-6">
            <input id="name" type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name ?? old('user_name') }}" required autocomplete="name" autofocus>

            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row mb-0">
        <div class="col-md-6 offset-md-4  d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                {{ __('Update User Name') }}
            </button>
        </div>
    </div>
</form>
