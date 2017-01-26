<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>Login | User Admin</title>
 
        <link rel='stylesheet' href="{{ asset('assets/css/bootstrap.min.css') }}">
        <link rel='stylesheet' href="{{ asset('assets/css/font-awesome.min.css') }}">
        <style>
            body {
                margin-top: 5%;
            }
        </style>
    </head>
    <body>
        <div class='container-fluid'>
            <div style="max-width: 400px; margin: 0 auto;">
                @if ($errors->has())
                    @foreach ($errors->all() as $error)
                        <div class='bg-danger alert'>{{ $error }}</div>
                    @endforeach
                @endif

                <h1><i class='fa fa-lock'></i> Вход</h1>
             
                {{ Form::open(array('role' => 'form')) }}
             
                <div class='form-group'>
                    {{ Form::label('username', 'Email') }}
                    {{ Form::text('username', null, array('placeholder' => 'Email', 'class' => 'form-control')) }}
                </div>
             
                <div class='form-group'>
                    {{ Form::label('password', 'Пароль') }}
                    {{ Form::password('password', array('placeholder' => 'Password', 'class' => 'form-control')) }}
                </div>
             
                <div class='form-group'>
                    {{ Form::submit('Вход', array('class' => 'btn btn-primary')) }}
                </div>
             
                {{ Form::close() }}
             
            </div>
        </div>
    </body>
</html>