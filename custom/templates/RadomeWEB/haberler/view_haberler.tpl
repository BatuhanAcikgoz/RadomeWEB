{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container" style="min-height: calc(-175.133px + 100vh);">
    <div class="row">
        <div class="col-md-9">
            <ol class="breadcrumb">
                {foreach from=$BREADCRUMBS item=breadcrumb}
                    <li {if isset($breadcrumb.active)}class="active" {/if}>{if !isset($breadcrumb.active)}<a
                            class="white-link"
                            href="{$breadcrumb.link}">{/if}{$breadcrumb.forum_title}{if !isset($breadcrumb.active)}</a>{/if}
                    </li>
                {/foreach}
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
            {if !empty($SUBFORUMS)}
                <div class="card">
                    <div class="card-header header-theme">{$SUBFORUM_LANGUAGE}</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table forum-table">
                                <colgroup>
                                    <col span="1" style="width:25%">
                                    <col span="1" style="width:20%">
                                    <col span="1" style="width:55%">
                                </colgroup>
                                {foreach from=$SUBFORUMS item=subforum}
                                    <tr>
                                        <td><a class="white-link" href="{$subforum.link}">{$subforum.title}</a></td>
                                        <td><strong>{$subforum.topics}</strong> {$TOPICS}</td>
                                        <td>
                                            {if count($subforum.latest_post)}
                                                <a class="white-link"
                                                    href="{$subforum.latest_post.link}">{$subforum.latest_post.title}</a>
                                                <br /><small><span data-toggle="tooltip" data-trigger="hover"
                                                        data-original-title="{$subforum.latest_post.time}">{$subforum.latest_post.timeago}</span>
                                                    {$BY} <a style="{$subforum.latest_post.last_user_style}"
                                                        href="{$subforum.latest_post.last_user_link}"
                                                        data-poload="{$USER_INFO_URL}{$subforum.latest_post.last_user_id}"
                                                        data-html="true"
                                                    data-placement="top">{$subforum.latest_post.last_user}</a></small> {else}
                                            {$NO_TOPICS} {/if}
                                        </td>
                                    </tr>
                                {/foreach}
                            </table>
                        </div>
                    </div>
                </div>
            {/if}
            <div class="card">
                <div class="card-header header-theme">{$TOPICS}{if $NEW_TOPIC_BUTTON}<span class="float-right"><a
                            href="{$NEW_TOPIC_BUTTON}" class="btn btn-theme btn-sm">{$NEW_TOPIC}</a></span>{/if}</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table forum-table">
                            {if ($LATEST_DISCUSSIONS|@count == 0) && (!count($STICKY_DISCUSSIONS))}
                                {$NO_TOPICS}
                            {/if}

                            {if count($STICKY_DISCUSSIONS)}
                                {foreach from=$STICKY_DISCUSSIONS item=sticky}
                                    <tr>
                                        <td>
                                            {if $sticky.locked == 1}<i class="fas fa-lock"></i> {/if}<i
                                                class="fas fa-thumbtack"></i> {if isset($sticky.labels) && count($sticky.labels)}
                                                    {foreach from=$sticky.labels item=label}
                                                      {$label}
                                                    {/foreach}
                                                  {/if} <a class="white-link"
                                                href="{$sticky.link}">{$sticky.topic_title}</a><br />
                                            <small><span data-toggle="tooltip" data-trigger="hover"
                                                    data-original-title="{$sticky.topic_created}">{$sticky.topic_created_rough}</span>
                                                {$BY} <a style="{$sticky.topic_created_style}" href="{$sticky.author_link}"
                                                    data-poload="{$USER_INFO_URL}{$sticky.topic_created_user_id}"
                                                    data-html="true"
                                                    data-placement="top">{$sticky.topic_created_username}</a></small>
                                        </td>
                                        <td>
                                            <strong>{$sticky.views}</strong> {$VIEWS}<br />
                                            <strong>{$sticky.posts}</strong> {$POSTS}
                                        </td>
                                        <td>
                                            {$LAST_REPLY}:<br />
                                            <small><span data-toggle="tooltip" data-trigger="hover"
                                                    data-original-title="{$sticky.last_reply}">{$sticky.last_reply_rough}</span>
                                                {$BY} <a style="{$sticky.last_reply_style}" href="{$sticky.last_reply_link}"
                                                    data-poload="{$USER_INFO_URL}{$sticky.last_reply_user_id}" data-html="true"
                                                    data-placement="top">{$sticky.last_reply_username}</a></small>
                                        </td>
                                    </tr>
                                {/foreach}
                            {/if}
                            {if $LATEST_DISCUSSIONS|@count > 0} 
                                {foreach from=$LATEST_DISCUSSIONS item=discussion}
                                    <tr>
                                        <td>
                                            <span style="display:inline;">{if isset($discussion.labels) && count($discussion.labels)}
                                                {foreach from=$discussion.labels item=label}
                                                  {$label}
                                                {/foreach}
                                              {/if}</span>{if $discussion.locked == 1}<i class="fas fa-lock"></i> {/if} <a class="white-link"
                                                href="{$discussion.link}">{$discussion.topic_title}</a><br />
                                            <small><span data-toggle="tooltip" data-trigger="hover"
                                                    data-original-title="{$discussion.topic_created}">{$discussion.topic_created_rough}</span>
                                                {$BY} <a style="{$discussion.topic_created_style}"
                                                    href="{$discussion.author_link}"
                                                    data-poload="{$USER_INFO_URL}{$discussion.topic_created_user_id}"
                                                    data-html="true"
                                                    data-placement="top">{$discussion.topic_created_username}</a></small>
                                        </td>
                                        <td>
                                            <strong>{$discussion.views}</strong> {$VIEWS}<br />
                                            <strong>{$discussion.posts}</strong> {$POSTS}
                                        </td>
                                        <td>
                                            {$LAST_REPLY}:<br />
                                            <small><span data-toggle="tooltip" data-trigger="hover"
                                                    data-original-title="{$discussion.last_reply}">{$discussion.last_reply_rough}</span>
                                                {$BY} <a style="{$discussion.last_reply_style}"
                                                    href="{$discussion.last_reply_link}"
                                                    data-poload="{$USER_INFO_URL}{$discussion.last_reply_user_id}"
                                                    data-html="true"
                                                    data-placement="top">{$discussion.last_reply_username}</a></small>
                                        </td>
                                    </tr>
                                {/foreach} 
                            {/if}
                        </table>
                    </div>
                </div>
            </div>
            {$PAGINATION}
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