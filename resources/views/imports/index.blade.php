@extends('layouts.app')

@section('content')
<!-- Message -->
@if (count($errors))
    <div class="alert alert-danger">
        @foreach ($errors->all() as $err)
            <span>{{ $err }}</span>
        @endforeach
    </div>
@endif
@if(Session::has('message'))
    <p >{{ Session::get('message') }}</p>
@endif
<div class="container">
    <div class="row justify-content-center">
        <!-- Form -->
        <form method='post' action={{ route('import.create') }} enctype='multipart/form-data' >
            {{ csrf_field() }}
            <div>
                <input type='file' name='file' >
                <input type='submit' name='submit' value='{{ trans('import.index.import') }}'>
            </div>
        </form>
    </div>
</div>
@endsection
