<div class="row">
    <h4 class="fa fa-plus pointer seotoggle"> SEO</h4>
    <div class="seocont">
        <div class='col-md-6'>
            <div class="form-group">
                {{ Form::label('seo_title', 'SEO Title') }}
                {{ Form::text('seo_title', null, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('img_alt', 'Image alt') }}
                {{ Form::text('img_alt', null, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('img_title', 'Image title') }}
                {{ Form::text('img_title', null, array('class' => 'form-control')) }}
            </div>
        </div>
        <div class='col-md-6'>
            <div class="form-group">
                {{ Form::label('keywords', 'Keywords') }}
                {{ Form::textarea('keywords', null, array('class' => 'form-control hgt100')) }}
            </div>
            <div class="form-group">
                {{ Form::label('description', 'Description') }}
                {{ Form::textarea('description', null, array('class' => 'form-control hgt100')) }}
            </div>
        </div>

        {{ Form::hidden('seoid', null, array('class' => 'form-control')) }}
        {{ Form::hidden('table', 'articles', array('class' => 'form-control')) }}
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('.seotoggle').on('click',function(){
            $(this).parent().find('.seocont').slideToggle();
            $(this).toggleClass('fa-minus');
        });
    })
</script>
