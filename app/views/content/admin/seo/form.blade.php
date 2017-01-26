@extends('containers.admin')
 
@section('title') SEO params @stop
 
@section('main')

    @if (empty($seo))
        {{ Form::open(array('role' => 'form', 'url' => 'admin/seo/store')) }}
    @else
        {{ Form::model($seo, array('role' => 'form', 'url' => 'admin/seo/update/' . $seo->id, 'method' => 'PUT')) }}
    @endif
 
    <div style="overflow: hidden;">
        <h1 class="fa fa-cog"> {{empty($seo)?'Add':'Edit'}} SEO </h1> 

        <div class='form-group pull-right top20'>
            {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
        </div>
        
        @if(isset($item) && isset($table))
            "{{ $item->title }}" ({{ $table }})
            {{ Form::hidden('table', $table, array('class' => 'form-control')) }}
            {{ Form::hidden('item_id', $elementId, array('class' => 'form-control')) }}
        @else
            <div class="row">
                <div class='form-group col-md-12'>
                    {{ Form::label('url', 'Url') }}
                    {{ Form::text('url', null, array('class' => 'form-control')) }}
                </div>
            </row>
        @endif
    </div>

    <div class="row">
        <div class='form-group col-md-12'>
            {{ Form::label('seo_title', 'Title') }}
            {{ Form::text('seo_title', null, array('class' => 'form-control')) }}
        </div>

        <div class='form-group col-md-6'>
            {{ Form::label('keywords', 'Keywords') }}
            {{ Form::textarea('keywords', null, array('class' => 'form-control')) }}
        </div>

        <div class='form-group col-md-6'>
            {{ Form::label('description', 'Description') }}
            {{ Form::textarea('description', null, array('class' => 'form-control')) }}
        </div>

        <div class='form-group col-md-6'>
            {{ Form::label('img_alt', 'Image alt') }}
            {{ Form::text('img_alt', null, array('class' => 'form-control')) }}
        </div>

        <div class='form-group col-md-6'>
            {{ Form::label('img_title', 'Image title') }}
            {{ Form::text('img_title', null, array('class' => 'form-control')) }}
        </div>
    </div>
 
    {{ Form::close() }}

@stop