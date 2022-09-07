{include file='header.tpl'}
{include file='navbar.tpl'}
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <ol class="breadcrumb">
                <li><a href="{$WIKI_HOME_LINK}" class="white-link">{$WIKI}</a></li>
                <div class="divider" style="margin: 0 12px"> / </div>
                <li class="active">{$SEARCH_RESULTS_LANG} {$SEARCH_RESULT}</li>
            </ol> 
            <div class="card">
                <div class="card-header header-theme">{$SEARCH_RESULTS_LANG} {$SEARCH_RESULT}</div>
                <div class="card-body">

                    {if $SEARCH_RESULTS|@count <= 0}
                        {$SEARCH_NO_RESULTS}
                    {else}
                       {foreach from=$SEARCH_RESULTS item=result}
                            <div class="card server">
                                <div class="card-body">
                                <i class="{$result->icon}"></i>
                                {$result->button}
                                <a href="../wiki/{$result->nameid}" class="btn btn-theme btn-sm float-right">View</a>
                                </div>
                            </div>
                        {/foreach}     
                    {/if}

                </div>
            </div>
        </div>
        <div class="col-md-3">
            {include file='wiki/widgets/search-box.tpl'}
            {include file='wiki/widgets/side-menu.tpl'}
        </div>
    </div>
</div>
{include file='footer.tpl'}