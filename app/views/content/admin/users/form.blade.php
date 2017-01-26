@extends('containers.admin')
 
@section('title') Create User @stop
 
@section('main')
 
    <h1><i class='fa fa-user'></i> {{Request::segment(3)=='create'?'Add':'Edit'}} User</h1>
 
    @if (Request::segment(3)=='create')
        {{ Form::open(array('role' => 'form', 'url' => 'admin/users/store','files' => true)) }}
    @else
        {{ Form::model($user, array('role' => 'form', 'url' => 'admin/users/update/' . $user->id, 'method' => 'PUT', 'files' => true)) }}
    @endif

    <div class='col-lg-4'>

        <div class='form-group'>
            {{ Form::label('username', 'Username') }}
            {{ Form::text('username', null, array('placeholder' => 'Username', 'class' => 'form-control')) }}
        </div>
     
        <div class='form-group'>
            {{ Form::label('email', 'Email') }}
            {{ Form::email('email', null, array('placeholder' => 'Email', 'class' => 'form-control')) }}
        </div>

        <div class='form-group'>
            {{ Form::label('role', 'Role') }}
            {{ Form::select('role', $roles_dd,isset($user->role->id)?$user->role->id:'3',array('class'=>'form-control')); }}
        </div>

        <div class='form-group'>
            {{ Form::label('google_account', 'Google account') }}
            {{ Form::text('google_account', null, array('placeholder' => 'Full url', 'class' => 'form-control')) }}
        </div>
     
        <div class='form-group'>
            {{ Form::label('password', 'Password') }}
            {{ Form::password('password', array('placeholder' => 'Password', 'class' => 'form-control')) }}
        </div>
     
        <div class='form-group'>
            {{ Form::label('password_confirmation', 'Confirm Password') }}
            {{ Form::password('password_confirmation', array('placeholder' => 'Confirm Password', 'class' => 'form-control')) }}
        </div>
     
        <div class='form-group'>
            {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
        </div>

    </div>

    <div class="col-lg-4">
        <div class="panel panel-default ">
            <div class="panel-heading">Media <i class="fa fa-times delete_image pull-right" title="Delete"></i></div>
            <div class="panel-body">
                <div class="upload_img_cont form-group">                                             
                    <img id="img_preview" src="{{ !empty($user->image)?'/'.$user->image:'/assets/images/no-image.jpg' }}" alt="your image" /><br><br>
                    {{ Form::file('userfile', array('id' => 'imgInp')) }}
                    <input type="hidden" name="image" id="image_path" value="{{ !empty($user->image)?$user->image:'' }}">
                </div>
            <div>
        </div>
    </div>
 
    {{ Form::close() }}
 
@stop

@section('scripts')
<script type="text/javascript"> 
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#img_preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    } 

    $(document).ready(function(){
        $('body').on('change','#imgInp',function(){
            readURL(this); 
        });
        
        $('.delete_image').on('click',function(){
            $('#img_preview').attr('src','/assets/images/no-image.jpg');
            $('#imgInp').val('');
            $('#image_path').val('');
            $('#image_thumb').val('');
        });
    });
</script>
@stop