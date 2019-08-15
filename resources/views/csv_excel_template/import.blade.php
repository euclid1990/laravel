<!doctype html>
<html>
    <head>
    <title>Import CSV and Excel Data </title>
    </head>
    <body>
    <!-- Message -->
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        @foreach ($errors->all() as $err)
        {{ $err }}
        @endforeach
    </div>
    @if(Session::has('message'))
        <p >{{ Session::get('message') }}</p>
    @endif

    <!-- Form -->
    <form method='post' action={{ route('file.import') }} enctype='multipart/form-data' >
            {{ csrf_field() }}
            <input type='file' name='file' >
            <input type='submit' name='submit' value='Import'>
        </form>
    </body>
</html>