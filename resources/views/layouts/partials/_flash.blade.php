@if(session()->has('success'))
<div class="notification is-primary">
    {{session('success')}}
</div>
@elseif(session()->has('error'))
<div class="notification is-danger">
    {{session('error')}}
</div>
@endif