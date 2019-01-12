<nav class="navbar">
    <div class="container">
        <div class="navbar-brand">
            <a class="navbar-burger burger">
                <span></span>
                <span></span>
                <span></span>
            </a>
        </div>

        <div class="navbar-menu">
            <div class="navbar-start">
                <a href="{{route('home')}}" class="navbar-item">
                   {{config('app.name')}}
                </a>

            </div>

            <div class="navbar-end">
                <div class="navbar-item">
                    <div class="buttons">
                        @if(auth()->check())
                        <a href="{{route('logout')}}" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();" class="button is-primary">
                            <strong>Log out</strong>
                        </a>
                        <a class="button is-light">
                            Your profile
                        </a>
                        @else
                        <a href="{{route('login')}}" class="button is-primary">
                            <strong>Log in</strong>
                        </a>
                        <a href="{{route('register')}}" class="button is-light">
                            Start selling
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>