<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/blog' ) ); ?>">
    <input class="form-control form-control_small form-group_white form-group_bordered" name="s" id="searchPosts" placeholder="Поиск" value="<? if(!empty(get_search_query())){ echo get_search_query(); }?>">
</form>
