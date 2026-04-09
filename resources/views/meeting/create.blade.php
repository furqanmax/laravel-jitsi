@extends('layouts.app')

@section('content')
<div class="container">
    <form method="POST" action="/meeting/store">
        @csrf
        <button type="submit" class="btn btn-primary">Create Meeting</button>
    </form>
</div>
@endsection
