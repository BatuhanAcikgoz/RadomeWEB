{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container" style="min-height: calc(-175.133px + 100vh);">
    <div class="card">
        <div class="card-header header-theme">{$SEARCH_RESULTS}<span class="float-right"><a href="{$NEW_SEARCH_URL}" class="btn btn-theme btn-sm">{$NEW_SEARCH}</a></span></div>
        <div class="card-body">
            {if isset($RESULTS)} {foreach from=$RESULTS item=result}
            <a class="white-link" href="{$result.post_url}">{$result.topic_title}</a><span class="float-right"><a href="{$result.post_url}" class="btn btn-theme">{$READ_FULL_POST} <i class="fas fa-share"></i></a></span><br /> {$BY}
            <a href="{$result.post_author_profile}" style="{$result.post_author_style}" data-poload="{$USER_INFO_URL}{$result.post_author_id}" data-html="true" data-placement="top">{$result.post_author} <img class="avatar-img" src="{$result.post_author_avatar}" style="max-height:20px; max-width:20px;"/></a>            &bull; <span data-toggle="tooltip" title="{$result.post_date_full}">{$result.post_date_friendly}</span>
            <hr /> {/foreach} {else}
            <div class="alert alert-info">
                {$NO_RESULTS}
            </div>
            {/if} {if isset($PAGINATION)} {$PAGINATION} {/if}
        </div>
    </div>
</div>
{include file='footer.tpl'}