@extends('containers.admin')
 
@section('title') Create article @stop

@section('styles')
    <link href="{{ asset('packages/jq_tags_input/jquery.tagsinput.css') }}" rel="stylesheet">
@stop
 
@section('main')

<div class='col-4 col-offset-4'>


    @if (Request::segment(3)=='create')
        {{ Form::open(array('role' => 'form', 'url' => 'admin/articles/store', 'files' => true)) }}
    @else
        {{ Form::model($article, array('role' => 'form', 'url' => 'admin/articles/update/' . $article->id, 'method' => 'PUT', 'files' => true)) }}
    @endif
 
    <div style="overflow: hidden;">
        <h1 class="fa fa-edit pull-left"> {{Request::segment(3)=='create'?'Add':'Edit'}} article</h1>

        <div class='form-group pull-left' style="margin: 32px 0 0 10px;">
            {{ Form::label('publishnow', 'Publish now') }}
            {{ Form::checkbox('publishnow', 1, true) }}
        </div>
        <div class='form-group pull-left' style="margin: 32px 0 0 10px;">
            {{ Form::label('removelinks', 'Remove links') }}
            {{ Form::checkbox('removelinks', 1, true) }}
        </div>

        <div class='form-group pull-right top20 left10'>
            @if (Request::segment(3)=='create')

                {{ Form::label('vkcheckbox', 'VK') }}
                {{ Form::checkbox('vkcheckbox', 1) }}
            @endif

            {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('title', 'Name') }}
                {{ Form::text('title', null, array('placeholder' => 'Title', 'class' => 'form-control', 'id' =>'article-title-input')) }}
            </div>
            <div class="form-group">
                {{ Form::label('title', 'Alias') }}
                <div class="row">
                    <div class="col-md-10">
                        {{ Form::text('alias', null, array('placeholder' => 'Alias', 'class' => 'form-control', 'id' => 'article-alias-input')) }}
                    </div>
                    <div class="col-md-2 generate-alias">
                        <a class="btn btn-default" onClick="generateSlug()">Generate</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class='form-group'>
                {{ Form::label('tags', 'Tags') }}
                {{ Form::text('tags', isset($tags)?$tags:null, array('class' => 'form-control', 'id' => 'tags')) }}
            </div>
        </div>
    </div>

    <!-- SEO -->
    @include('content.admin.seo.main')
    <!-- end seo -->

    <div class='form-group'>
        {{ Form::label('content', 'Text') }}
        {{ Form::textarea('content', null, array('class' => 'form-control article-textarea')) }}
    </div>

    <div class="row">
        <div class='form-group col-md-8'>
            {{ $tree }}                 
        </div>
        <div class='form-group col-md-4'>        
            <div class="panel panel-default">
                <div class="panel-heading">Media <i class="fa fa-times delete_image pull-right" title="Delete"></i></div>
                <div class="panel-body">
                    <div class="upload_img_cont form-group">                                             
                        <img id="img_preview" src="{{ !empty($article->image)?'/'.$article->image:'/assets/images/no-image.jpg' }}" alt="your image" /><br><br>
                        {{ Form::file('userfile', array('id' => 'imgInp')) }}
                        <input type="hidden" name="image" id="image_path" value="{{ !empty($article->image)?$article->image:'' }}">
                    </div>
                </div>
            </div>
            <div class='form-group'>
                {{ Form::label('video', 'Video (youtube identifier)') }}
                {{ Form::text('video', null, array('class' => 'form-control', 'id' => 'video')) }}
            </div>
        </div>
    </div>
 
    {{ Form::close() }}
 
</div>
 
@stop

@section('scripts')
    <link rel="stylesheet" type="text/css" href="/packages/fancyBox/jquery.fancybox.css" media="screen" />
    <script type="text/javascript" src="/packages/fancyBox/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="/packages/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="/packages/jq_tags_input/jquery.tagsinput.min.js"></script>   
    <script type="text/javascript" src="//vk.com/js/api/openapi.js"></script>
    <script type="text/javascript"> VK.init({apiId:4776567});</script>

    <script type="text/javascript">
        tinymce.init({
            selector: ".article-textarea",
            height : 300,
            relative_urls: false,
            plugins: [
                "advlist autolink lists link image responsivefilemanager charmap print preview anchor textcolor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste jbimages, pagebreak"
            ],
            external_filemanager_path:"/packages/filemanager/",
            filemanager_title:"Менеджер файлов" ,
            external_plugins: { "filemanager" : "/packages/tinymce/plugins/responsivefilemanager/plugin.min.js"},
            pagebreak_separator: "<pagebreak>",
            //outdent indent
            toolbar: "insertfile undo redo | styleselect | fontsizeselect | fontselect | backcolor | forecolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image jbimages responsivefilemanager",
            relative_urls: false,
            //resize: false,
            forced_root_block : 'div',
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#img_preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function generateSlug () {
            var str = $('#article-title-input').val();
            if(str.length>1){
                var space = '_';
                str = str.toLowerCase();
                var transl = {
                    'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e', 'ж': 'zh', 
                    'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
                    'о': 'o', 'п': 'p', 'р': 'r','с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h',
                    'ц': 'c', 'ч': 'ch', 'ш': 'sh', 'щ': 'sh','ъ': '', 'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu', 'я': 'ya'
                }
                var link = '';
                for (var i = 0; i < str.length; i++) {
                    if(/[а-яё]/.test(str.charAt(i))) { //если текущий символ - русская буква, то меняем его
                        link += transl[str.charAt(i)];
                    } else if (/[a-z0-9]/.test(str.charAt(i))) {
                        link += str.charAt(i); //если текущий символ - английская буква или цифра, то оставляем как есть
                    } else {
                        if (link.slice(-1) !== space) link += space; // если не то и не другое то вставляем space
                    }
                }
                $('#article-alias-input').val(link);
            }
        } 

        $(document).ready(function(){
            $('body').on('change','#imgInp',function(){
                readURL(this); 
            });

            $('#tags').tagsInput({
               'width':'100%',
               'height':'107px'
              //autocomplete_url:'http://myserver.com/api/autocomplete'
            });
            
            $('.delete_image').on('click',function(){
                $('#img_preview').attr('src','/assets/images/no-image.jpg');
                $('#imgInp').val('');
                $('#image_path').val('');
                $('#image_thumb').val('');
            });

            $('.iframe-btn').fancybox({ 
                'width'     : '100%',
                'height'    : '600px',
                'type'      : 'iframe',
                'autoScale' : false
            });
        });
    </script>
@stop