@extends('containers.admin')
 
@section('title') Edit comment @stop
 
@section('main')


    {{ Form::model($comment, array('role' => 'form', 'url' => 'admin/comments/update', 'method' => 'post')) }}
 
    <div style="overflow: hidden;">
        <h1 class="fa fa-comment"> Edit comment</h1>

        <div class='form-group pull-right top20'>
            {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
        </div>
    </div>

    <div class="row">
        <div class='col-md-6'>
            Author: <span style="font-size:20px; margin-right:20px">{{ $comment->username }}</span> {{ $comment->created_at }}        
        </div>
        <div class='form-group col-md-12'>
            {{ Form::textarea('content', null, array('class' => 'form-control')) }}
            {{ Form::hidden('id') }}
        </div>
    </div>  
 
    {{ Form::close() }}

@stop