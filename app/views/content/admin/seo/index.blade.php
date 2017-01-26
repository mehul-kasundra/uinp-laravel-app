@extends('containers.admin')
@section('main')

<h1 class="fa fa-gear pull-left"> Url seo</h1>
{{ link_to('admin/seo/create/', 'Add new url', array('class'=>'pull-right btn btn-primary top20 left10')) }}
  
<?php 
        $search_fields = array_flip($table_fields);
        $search_fields = array_combine($search_fields, $search_fields);
    ?>
    {{ Form::open(array('role' => 'form', 'url' => 'admin/urlseo', 'method' =>'get', 'class' => 'pull-right top20 table-search-form')) }}
        {{ Form::select('field',$search_fields,'',array('class'=>'form-control pull-left')) }} 
        {{ Form::text('search','',array('class'=>'form-control pull-left')) }}
        {{ Form::submit('Search',array('class'=>'btn btn-info')) }}
    {{ Form::close() }}  

    @if ($items->count())
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    {{ Common_helper::sorting_table_fields($table_fields) }}
                    <th style="width: 170px;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->url }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td>
                            {{ link_to('admin/seo/edit/'.$item->id, 'Edit', array('class' => 'btn btn-info btn-xs pull-left left10', 'title'=>'edit')) }}
                            {{ Form::open(array('url' => 'admin/seo/destroy/' . $item->id, 'method' => 'DELETE')) }}
                                {{ Form::submit('Delete', array('class' => 'btn btn-danger btn-xs left10','onclick'=>'return confirm(\'Delete item?\')?true:false;'))}}
                            {{ Form::close() }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $items->links() }}    
    @endif

@stop

