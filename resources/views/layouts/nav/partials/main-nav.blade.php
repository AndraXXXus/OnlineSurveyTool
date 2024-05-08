<ul class="navbar-nav me-auto">
    <li class="nav-item">
        <a class="{{ Route::is('surveys.index') ? "nav-link active d-flex border-bottom border-2 border-info" : "nav-link"}}" href="{{ route('surveys.index') }}">{{ __('User Surveys') }}</a>
    </li>
    <li class="nav-item">
        <a class="{{ Route::is('questionnaires.index') ? "nav-link active d-flex border-bottom border-2 border-info" : "nav-link"}}" href="{{ route('questionnaires.index') }}">{{ __('Team Questionnaires') }}</a>
    </li>
</ul>
