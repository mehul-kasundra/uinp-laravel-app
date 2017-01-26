@extends('containers.admin')
 
@section('title') Tags @stop

@section('styles')
    <style type="text/css">
        .table th {
          min-width: 115px;
        }
    </style>
@stop
 
@section('main')
    <h1 class="fa fa-tag pull-left"> Tags</h1>

    {{ link_to('admin/tags/import/', 'Import', array('class'=>'pull-right btn btn-primary top20 left10')) }}
    {{ link_to('admin/tags/create/', 'Add new tag', array('class'=>'pull-right btn btn-primary top20 left10')) }}

    <?php 
        $search_fields = array_flip($table_fields);
        $search_fields = array_combine($search_fields, $search_fields);
    ?>
    {{ Form::open(array('role' => 'form', 'url' => 'admin/tags', 'method' =>'get', 'class' => 'pull-right top20 table-search-form')) }}
        {{ Form::select('field',$search_fields,'',array('class'=>'form-control pull-left')) }} 
        {{ Form::text('search','',array('class'=>'form-control pull-left')) }}
        {{ Form::submit('Search',array('class'=>'btn btn-info')) }}
    {{ Form::close() }}  

    @if ($tags->count())
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    {{ Common_helper::sorting_table_fields($table_fields) }}
                    <th style="min-width: 130px;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($tags as $tag)
                    <tr>
                        <td>{{ $tag->name }}</td>
                        <td>{{ $tag->frequency }}</td>
                        <td>{{ $tag->freq_regions }}</td>
                        <td>{{ $tag->mainpages_yandex }}</td>
                        <td>{{ $tag->titles_yandex }}</td>
                        <td>{{ $tag->mainpages_google }}</td>
                        <td>{{ $tag->titles_google }}</td>
                        <td>{{ $tag->created_at }}</td>
                        <td>
                            {{ link_to('admin/tags/edit/'.$tag->id, 'Edit', array('class' => 'btn btn-info btn-xs pull-left left10', 'title'=>'edit')) }}
                            {{ link_to('admin/tags/delete/'.$tag->id, 'Delete', array('class' => 'btn btn-danger btn-xs pull-left left10', 'title'=>'delete','onclick'=>'return confirm(\'Delete tag?\')?true:false;' )) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $tags->links() }}    
    @endif
@stop