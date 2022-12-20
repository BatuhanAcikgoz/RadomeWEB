{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container" style="min-height: calc(-175.133px + 100vh);">
    <div class="row">
        <div class="col-md-9">
            <ol class="breadcrumb">
                <li><a class="grey-link" href="{$BREADCRUMB_URL}">{$BREADCRUMB_TEXT}</a></li>
            </ol>
        </div>
        <div class="col-md-3">
            <form class="form-horizontal" role="form" method="post" action="{$SEARCH_URL}">
                <div class="input-group">
                    <input type="text" class="form-control input-sm" name="forum_search" placeholder="{$SEARCH}"
                        minlength="3" maxlength="128">
                    <input type="hidden" name="token" value="{$TOKEN}">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-theme">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </form>
            <br />
        </div>
    </div>

    <div class="row">
        {if count($WIDGETS_LEFT)}
            <div class="col-md-3">
                {foreach from=$WIDGETS_LEFT item=widget}
                    {$widget}
                {/foreach}
            </div>
        {/if}

        <div class="{if count($WIDGETS_LEFT) && count($WIDGETS_RIGHT)}col-md-6{elseif count($WIDGETS_LEFT) || count($WIDGETS_RIGHT)}col-md-9{else}col-md-12{/if}">

            {if isset($SPAM_INFO)}
                <div class="alert alert-info">{$SPAM_INFO}</div>
            {/if}
            {foreach from=$FORUMS key=category item=forum}
                {assign var=counter value=1}
                <div class="card">
                    {if !empty($forum.subforums)}
                        <div class="card-header header-theme">{if empty({$forum.icon})}<i
                                class="fa fa-folder-open"></i>{else}{$forum.icon}
                            {/if} <a href="{$forum.link}">{$forum.title}</a></div>
                        <div class="card-body">
                            {foreach from=$forum.subforums item=subforum}
                                <div class="row">
                                    <div class="col-2 col-md-1 forum-icon-col">
                                        {if empty($subforum->icon)}<i class="fa fa-comment"></i>{else}{$subforum->icon}{/if}
                                    </div>
                                    <div class="col-10 col-md-5">
                                        <strong><a class="white-link" href="{if !isset($subforum->redirect_confirm)}{$subforum->link}
                                                                    {else}#" data-toggle="modal"
                                                data-target="#confirmRedirectModal{$subforum->id}{/if}">{$subforum->forum_title}</a></strong><br /><span
                                            class="subforum-description">{$subforum->forum_description}</span>
                                    </div>
                                    <div class="col-4 col-md-2 col-inv">
                                        <strong>{$subforum->topics}</strong> {$TOPICS}<br />
                                        <strong>{$subforum->posts}</strong> {$POSTS}
                                    </div>
                                    {if $subforum->redirect_forum neq 1}
                                        <div class="col-8 col-md-4 col-inv">
                                            {if isset($subforum->last_post)}
                                                <a class="white-link" href="{$subforum->last_post->link}">{$subforum->last_post->title}</a>
                                                <br /> {$BY}
                                                <a style="{$subforum->last_post->user_style}" href="{$subforum->last_post->profile}"
                                                    data-poload="{$USER_INFO_URL}{$subforum->last_post->post_creator}" data-html="true"
                                                    data-placement="top">{$subforum->last_post->username}</a>
                                                <a href="{$subforum->last_post->profile}"><img alt="{$subforum->last_post->profile}"
                                                        style="height:20px; width:20px;" class="avatar-img"
                                                        src="{$subforum->last_post->avatar}" /></a><br />
                                                <span data-toggle="tooltip" data-trigger="hover"
                                                    data-original-title="{$subforum->last_post->post_date}">{$subforum->last_post->date_friendly}</span>
                                            {else} {$NO_TOPICS}
                                            {/if}
                                        </div>
                                    {else}
                                        <div class="modal fade" id="confirmRedirectModal{$subforum->id}" tabindex="-1" role="dialog"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        {$subforum->redirect_confirm}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{$NO}</button>
                                                        <a class="btn btn-theme" href="{$subforum->redirect_url}" target="_blank"
                                                            rel="noopener nofollow">{$YES}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    {/if}
                                </div>
                                {if isset($subforum->subforums)}
                                    <br /> {assign var=sf_counter value=1}
                                    <div class="row">
                                        {foreach from=$subforum->subforums item=sub_subforum}
                                            <div class="col-md-4">
                                                <i class="fa fa-folder-open" aria-hidden="true"></i>&nbsp;&nbsp;<a class="white-link"
                                                    href="{$sub_subforum->link}">{$sub_subforum->title}</a>
                                                {assign var=sf_counter value=$sf_counter+1}
                                            </div>
                                            {if $sf_counter eq 4}
                                            </div>
                                            <div class="row">
                                            {/if}
                                        {/foreach}
                                    </div>
                                    {/if} {if ($forum.subforums|@count) != $counter}
                                <hr /> {/if}
                                {assign var=counter value=$counter+1}
                            {/foreach}
                        </div>
                    {/if}
                </div>
            {/foreach}
        </div>

        {if count($WIDGETS_RIGHT)}
            <div class="col-md-3">
                {foreach from=$WIDGETS_RIGHT item=widget}
                    {$widget}
                {/foreach}
            </div>
        {/if}

    </div>
</div>
{include file='footer.tpl'}