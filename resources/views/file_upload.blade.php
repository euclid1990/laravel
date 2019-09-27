@extends('layouts.app')

@section('content')

@if (Session::has('error'))
    <div class="alert alert-error">
        <ul>
            <li>{{ Session::get('error') }}</li>
        </ul>
    </div>
@endif

@if (Session::has('success'))
    <div class="alert alert-success">
        <ul>
            <li>{{ Session::get('success') }}</li>
        </ul>
    </div>
@endif

<form action="{{ url('/file/upload') }}" method="post" enctype="multipart/form-data">
	@csrf
	<input type="file" name="files[]" multiple>
	<button type="submit">Submit</button>
</form>
@endsection
