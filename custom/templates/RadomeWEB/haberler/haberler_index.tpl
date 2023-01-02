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
                    <input type="text" class="form-control input-sm" name="haberler_search" placeholder="{$SEARCH}"
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
            {foreach from=$HABERLERS key=category item=haberler}
                {assign var=counter value=1}
                <div class="card">
                    {if !empty($haberler.subhaberlers)}
                        <div class="card-header header-theme">{if empty({$haberler.icon})}<i
                                class="fa fa-folder-open"></i>{else}{$haberler.icon}
                            {/if} <a href="{$haberler.link}">{$haberler.title}</a></div>
                        <div class="card-body">
                            {foreach from=$haberler.subhaberlers item=subhaberler}
                                <div class="row">
                                    <div class="col-2 col-md-1 haberler-icon-col">
                                        {if empty($subhaberler->icon)}<i class="fa fa-comment"></i>{else}{$subhaberler->icon}{/if}
                                    </div>
                                    <div class="col-10 col-md-5">
                                        <strong><a class="white-link" href="{if !isset($subhaberler->redirect_confirm)}{$subhaberler->link}
                                                                    {else}#" data-toggle="modal"
                                                data-target="#confirmRedirectModal{$subhaberler->id}{/if}">{$subhaberler->haberler_title}</a></strong><br /><span
                                            class="subhaberler-description">{$subhaberler->haberler_description}</span>
                                    </div>
                                    <div class="col-4 col-md-2 col-inv">
                                        <strong>{$subhaberler->topics}</strong> {$TOPICS}<br />
                                        <strong>{$subhaberler->posts}</strong> {$POSTS}
                                    </div>
                                    {if $subhaberler->redirect_haberler neq 1}
                                        <div class="col-8 col-md-4 col-inv">
                                            {if isset($subhaberler->last_post)}
                                                <a class="white-link" href="{$subhaberler->last_post->link}">{$subhaberler->last_post->title}</a>
                                                <br /> {$BY}
                                                <a style="{$subhaberler->last_post->user_style}" href="{$subhaberler->last_post->profile}"
                                                    data-poload="{$USER_INFO_URL}{$subhaberler->last_post->post_creator}" data-html="true"
                                                    data-placement="top">{$subhaberler->last_post->username}</a>
                                                <a href="{$subhaberler->last_post->profile}"><img alt="{$subhaberler->last_post->profile}"
                                                        style="height:20px; width:20px;" class="avatar-img"
                                                        src="{$subhaberler->last_post->avatar}" /></a><br />
                                                <span data-toggle="tooltip" data-trigger="hover"
                                                    data-original-title="{$subhaberler->last_post->post_date}">{$subhaberler->last_post->date_friendly}</span>
                                            {else} {$NO_TOPICS}
                                            {/if}
                                        </div>
                                    {else}
                                        <div class="modal fade" id="confirmRedirectModal{$subhaberler->id}" tabindex="-1" role="dialog"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        {$subhaberler->redirect_confirm}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{$NO}</button>
                                                        <a class="btn btn-theme" href="{$subhaberler->redirect_url}" target="_blank"
                                                            rel="noopener nofollow">{$YES}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    {/if}
                                </div>
                                {if isset($subhaberler->subhaberlers)}
                                    <br /> {assign var=sf_counter value=1}
                                    <div class="row">
                                        {foreach from=$subhaberler->subhaberlers item=sub_subhaberler}
                                            <div class="col-md-4">
                                                <i class="fa fa-folder-open" aria-hidden="true"></i>&nbsp;&nbsp;<a class="white-link"
                                                    href="{$sub_subhaberler->link}">{$sub_subhaberler->title}</a>
                                                {assign var=sf_counter value=$sf_counter+1}
                                            </div>
                                            {if $sf_counter eq 4}
                                            </div>
                                            <div class="row">
                                            {/if}
                                        {/foreach}
                                    </div>
                                    {/if} {if ($haberler.subhaberlers|@count) != $counter}
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