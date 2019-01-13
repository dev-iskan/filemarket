@extends('account.layouts.default')

@section('account.content')
    <h1 class="title">Make changes to {{$file->title}}</h1>

    @if($approval)
        @include('account.files.partials._changes')
    @endif

    <form action="{{route('account.files.update', $file)}}" method="post" class="form">
        @csrf
        {{method_field('PATCH')}}

        <input type="hidden" name="live" value="0">
        <div class="field">
            <p class="control">
                <label for="live" class="checkbox">
                    <input type="checkbox" name="live" id="live"{{ $file->live ? ' checked' : '' }} value="1">
                    Live
                </label>
            </p>
        </div>

        <div class="field">
            <label for="title" class="label">Title</label>
            <p class="control">
                <input type="text" class="input{{$errors->has('title') ? ' is-danger' : ''}}" name="title" id="title"
                value="{{old('title') ?? $file->title}}">
            </p>
            @if($errors->has('title'))
                <p class="help is-danger">
                    {{$errors->first('title')}}
                </p>
            @endif
        </div>
        <div class="field">
            <label for="overview_short" class="label">Short Overview</label>
            <p class="control">
                <input type="text" class="input{{$errors->has('overview_short') ? ' is-danger' : ''}}"
                       name="overview_short" id="overview_short"
                       value="{{old('overview_short') ?? $file->overview_short}}">
            </p>
            @if($errors->has('overview_short'))
                <p class="help is-danger">
                    {{$errors->first('overview_short')}}
                </p>
            @endif
        </div>
        <div class="field">
            <label for="overview" class="label">Overview</label>
            <p class="control">
                <textarea name="overview" id="overview" class="textarea{{$errors->has('overview') ? ' is-danger' : ''}}">{{old('overview') ?? $file->overview}}
                </textarea>
            </p>
            @if($errors->has('overview'))
                <p class="help is-danger">
                    {{$errors->first('overview')}}
                </p>
            @endif
        </div>
        <div class="field">
            <label for="price" class="label">Price ($)</label>
            <p class="control">
                <input type="text" class="input{{$errors->has('price') ? ' is-danger' : ''}}"
                       name="price" id="price" value="{{old('price') ?? $file->price}}">
            </p>
            @if($errors->has('price'))
                <p class="help is-danger">
                    {{$errors->first('price')}}
                </p>
            @endif
        </div>

        <div class="field is-grouped">
            <p class="control">
                <button class="button is-primary">Submit</button>
            </p>
            <p>Your files may be checked to review</p>
        </div>
    </form>
@endsection