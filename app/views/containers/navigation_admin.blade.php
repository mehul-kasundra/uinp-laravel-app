<nav class="navbar navbar-inverse" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
      </button>
      {{ link_to('/','UINP',array('class'=>'navbar-brand')) }}
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="{{ Request::segment(2)==''?'active':'' }}">{{ link_to('admin','Dashboard') }}</li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Content <b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li>{{ link_to('admin/folders','Folders') }}</li>
                    <li>{{ link_to('admin/articles','Articles') }}</li>
                    <li>{{ link_to('admin/comments','Comments') }}</li>
                    <li>{{ link_to('admin/tags','Tags') }}</li>
                    <!--<li class="divider"></li>-->                    
                  </ul>
                </li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">SEO <b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li>{{ link_to('admin/seo','URL table') }}</li> 
                    <li>{{ link_to('admin/mumble','Mumble') }}</li>                 
                  </ul>
                </li>
                <li class="{{ Request::segment(2)=='menus'?'active':'' }}">{{ link_to('admin/menus','Menus') }}</li>
                <li class="{{ Request::segment(2)=='users'?'active':'' }}">{{ link_to('admin/users','Users') }}</li>
                                                 
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Parsers <b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li class="{{ Request::segment(2)=='parser'?'active':'' }}">{{ link_to('admin/parser','Parser RSS') }}</li>
                    <li class="{{ Request::segment(2)=='parser2'?'active':'' }}">{{ link_to('admin/parser2','Parser V2') }}</li>                
                  </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                  <div class="current_user_email">{{ Auth::user()->email }}</div>
                </li>
                <li>{{ link_to('auth/logout','Logout') }}</li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

