@extends('containers.admin')
 
@section('title') Edit tag @stop
 
@section('main')

    @if (Request::segment(3)=='create')
        {{ Form::open(array('role' => 'form', 'url' => 'admin/tags/store')) }}
    @else
        {{ Form::model($tag, array('role' => 'form', 'url' => 'admin/tags/update/' . $tag->id, 'method' => 'PUT')) }}
    @endif
 
    <div style="overflow: hidden;">
        <h1 class="fa fa-tag"> {{Request::segment(3)=='create'?'Add':'Edit'}} tag</h1>

        <div class='form-group pull-right top20'>
            {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class='form-group '>
                {{ Form::label('name', 'Tag Name') }}
                {{ Form::text('name', null, array('placeholder' => 'Tag name', 'class' => 'form-control')) }}
            </div>
            <div class='form-group '>
                {{ Form::label('frequency', 'Frequency') }}
                {{ Form::text('frequency', null, array('placeholder' => 'Tag frequency', 'class' => 'form-control')) }}
            </div>
            <div class='form-group '>
                {{ Form::label('freq_regions', 'Frequency regions') }}
                {{ Form::text('freq_regions', null, array('placeholder' => 'Frequency regions', 'class' => 'form-control')) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class='form-group'>
                {{ Form::label('mainpages_yandex', 'Yandex main pages') }}
                {{ Form::text('mainpages_yandex', null, array('placeholder' => 'Yandex main pages count', 'class' => 'form-control')) }}
            </div>
            <div class='form-group'>
                {{ Form::label('titles_yandex', 'Yandex titles') }}
                {{ Form::text('titles_yandex', null, array('placeholder' => 'Yandex titles count', 'class' => 'form-control')) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class='form-group'>
                {{ Form::label('mainpages_google', 'Google main pages') }}
                {{ Form::text('mainpages_google', null, array('placeholder' => 'Google main pages count', 'class' => 'form-control')) }}
            </div>
            <div class='form-group'>
                {{ Form::label('titles_google', 'Google titles') }}
                {{ Form::text('titles_google', null, array('placeholder' => 'Google titles count', 'class' => 'form-control')) }}
            </div>
        </div>
    </div>
  
    {{ Form::close() }}

@stop
