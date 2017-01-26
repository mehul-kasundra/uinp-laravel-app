@extends('containers.admin')
@section('main')

<h1 class="fa fa-comments pull-left"> All Comments</h1>
  
<?php 
        $search_fields = array_flip($table_fields);
        $search_fields = array_combine($search_fields, $search_fields);
    ?>
    {{ Form::open(array('role' => 'form', 'url' => 'admin/comments', 'method' =>'get', 'class' => 'pull-right top20 table-search-form')) }}
        {{ Form::select('field',$search_fields,'',array('class'=>'form-control pull-left')) }} 
        {{ Form::text('search','',array('class'=>'form-control pull-left')) }}
        {{ Form::submit('Search',array('class'=>'btn btn-info')) }}
    {{ Form::close() }}  

    @if ($comments->count())
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    {{ Common_helper::sorting_table_fields($table_fields) }}
                    <th style="width: 120px;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($comments as $comment)
                    <tr>
                        <td>
                            <a href="/admin/users/edit/{{ $comment->user_id }}">{{ $comment->username }}</a>
                        </td>
                        <td>{{ $comment->created_at }}</td>
                        <td>{{ $comment->table }}</td>
                        <td>
                            <a href="/{{ $comment->path }}/{{ $comment->alias }}">{{ $comment->title}}</a>
                        </td>
                        <td>
                            {{ link_to('admin/comments/edit/'.$comment->id, 'Edit', array('class' => 'btn btn-info btn-xs pull-left left10', 'title'=>'edit')) }}
                            {{ Form::open(array('url' => 'admin/comments/destroy/' . $comment->id, 'method' => 'DELETE')) }}
                                {{ Form::submit('Delete', array('class' => 'btn btn-danger btn-xs left10','onclick'=>'return confirm(\'Delete comment?\')?true:false;'))}}
                            {{ Form::close() }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $comments->links() }}    
    @endif

@stop

