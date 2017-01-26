@extends('containers.admin')
 
@section('title') Import tags @stop
 
@section('main')


    {{ Form::open(array('role' => 'form', 'url' => 'admin/tags/import', 'files'=>true)) }}

 
    <div style="overflow: hidden;">
        <h1 class="fa fa-tag"> Import tags</h1>

        <div class='form-group pull-right top20'>
            {{ Form::submit('Submit', array('class' => 'btn btn-primary')) }}
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class='form-group '>
                {{ Form::label('file', 'XCell file') }}
                {{ Form::file('file', null, array('class' => 'form-control')) }}
            </div>
        </div>
    </div>
  
    {{ Form::close() }}

@stop
