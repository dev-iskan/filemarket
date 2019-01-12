<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials._head')
</head>
<body>
<div id="app">
    <section class="hero is-primary is-large">
        <div class="hero-head">
            @include('layouts.partials._navigation')
        </div>
        @yield('content')
    </section>
</div>
</body>
</html>
