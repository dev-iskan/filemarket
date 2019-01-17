<form action="{{route('checkout.free', $file)}}" method="post">
    @csrf
    <div class="field has-addons">
        <p class="control">
            <input class="input" type="email" name="email" id="email" required placeholder="you@somwhere.com"
            value="{{old('email')}}">
        </p>
        <p class="control">
            <button class="button is-primary">Download for free</button>
        </p>
    </div>
    @if($errors->has('email'))
        <p class="help is-danger">{{$errors->first('email')}}</p>
    @endif
</form>