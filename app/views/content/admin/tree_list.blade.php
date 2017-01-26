<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width:20px; min-width:20px;"></th>
            <th>Title</th>
            <th>Type</th>
            @if (!$inMenuEdit)
                <th style="min-width:155px;">Created</th>
                <th style="min-width:130px;">Actions</th>
            @endif
        </tr>
    </thead>

    <tbody>
        @if (isset($entities[0]))
            @foreach ($entities as $item)
                <tr data-type="{{ $item->type }}" data-id="{{ $item->id }}">
                    <td style="text-align:center; color:grey"><i class="fa {{ $item->type=='Folder'?'fa-folder-open':'fa-file-o'}}"></i></td>
                    <td><span title="{{ $item->alias }}">{{ $item->title }}</span></td>
                    <td>{{ $item->type }}</td>
                    @if (!$inMenuEdit)
                        <td>{{ $item->created_at }}</td>
                        <td>
                            {{ link_to('admin/'.strtolower($item->tname).'/edit/'.$item->id, 'Edit', array('class' => 'btn btn-info btn-xs pull-left left10')) }}
                            @if(!isset($item->undeletable) || $item->undeletable==0)
                                {{ Form::open(array('url' => 'admin/'.strtolower($item->tname).'/destroy/' . $item->id, 'method' => 'DELETE')) }}
                                    {{ Form::submit('Delete', array('class' => 'btn btn-danger btn-xs left10','onclick'=>'return confirm(\'Delete?\')?true:false;'))}}
                                {{ Form::close() }}
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach            
        @else
            <tr>
                <td colspan=6>Folder id emtpy</td>
            </tr>
        @endif
    </tbody>    
</table>
{{ $entities->links() }}