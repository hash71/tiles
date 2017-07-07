<div id="page-header" class="clearfix no-print">
    <div id="header-logo">
        <a href="javascript:;" class="tooltip-button" data-placement="bottom" title="Close sidebar" id="close-sidebar">
            <i class="glyph-icon icon-caret-left"></i>
        </a>
        <a href="javascript:;" class="tooltip-button hidden" data-placement="bottom" title="Open sidebar" id="rm-close-sidebar">
            <i class="glyph-icon icon-caret-right"></i>
        </a>
        <a href="javascript:;" class="tooltip-button hidden" title="Navigation Menu" id="responsive-open-menu">
            <i class="glyph-icon icon-align-justify"></i>
        </a>
        <i class="opacity-80">ERP System</i>
    </div>

    <div class="user-profile dropdown">
        <a href="javascript:;" title="" class="user-ico clearfix" data-toggle="dropdown">
            
            {{ HTML::image('assets/images/gravatar.jpg', '', ['width'=>36]) }}           
            
            <span>{{Auth::user()->user_name}}</span>                         
            

            <i class="glyph-icon icon-chevron-down"></i>
        </a>
        <ul class="dropdown-menu float-right">
            
            <li>
                
                    <i class="glyph-icon icon-signout font-size-13 mrg5R"></i>
                    
                    <span class="font-bold">
                        
                        {{ HTML::linkAction('SessionsController@destroy','Logout') }}


                    </span>
                    <!-- <span class="font-bold">Logout</span> -->
                
            </li>

        </ul>
    </div>

</div><!-- #page-header -->