{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container" style="min-height: calc(-175.133px + 100vh);">
    <div class="row">
        <div class="col-md-3">
            {include file='user/navigation.tpl'}
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header header-theme">
                    {$FOLLOWING_TOPICS}
                    {if count($TOPICS_LIST)}
                        <a class="btn btn-secondary btn-sm float-right" href="#" data-toggle="modal"
                            data-target="#modal-delete">{$UNFOLLOW_ALL}</a>
                    {/if}
                </div>
                <div class="card-body">

                    {nocache}
                    {if count($TOPICS_LIST)}
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <tbody>
                                    {foreach from=$TOPICS_LIST item=topic}
                                        <tr>
                                            <td>
                                                <div class="row" onclick="window.location.href = '{$topic.last_post_link}'"
                                                    style="cursor: pointer">
                                                    <div class="col-md-4">
                                                        {if $topic.unread}
                                                            <i class="bell icon"></i><strong>{$topic.topic_title}</strong>
                                                        {else}
                                                            {$topic.topic_title}
                                                        {/if}
                                                    </div>
                                                    <div class="col-md-6">
                                                        <img class="avatar-img" style="height: 30px;"
                                                            src="{$topic.reply_author_avatar}">
                                                        <a href="{$topic.reply_author_link}" data-toggle="popup"
                                                            data-poload="{$USER_INFO_URL}{$topic.reply_author_id}"
                                                            style="{$topic.reply_author_style}">{$topic.reply_author_username}</a>
                                                        <div data-toggle="tooltip" data-content="{$topic.reply_date_full}">
                                                            {$topic.reply_date}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a href="{$topic.unfollow_link}"
                                                            class="btn btn-secondary">{$UNFOLLOW_TOPIC}</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    {/foreach}
                                </tbody>
                            </table>
                        </div>

                        {$PAGINATION}
                    {else}
                        <div class="ui info message">
                            <div class="content">
                                {$NO_TOPICS}
                            </div>
                        </div>
                    {/if}
                    {/nocache}

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="modal-deleteLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title" id="modal-deleteLabel">{$UNFOLLOW_ALL}</span>
            </div>
            <div class="modal-body">
                {$CONFIRM_UNFOLLOW}<br /><br />
                <form class="form-control inline-form" action="" method="post" style="background-color: transparent; border: transparent; padding: 0 !important;">
                    <input type="hidden" name="token" value="{$TOKEN}">
                    <input type="hidden" name="action" value="purge">
                    <button type="submit" class="btn btn-theme">{$YES}</button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{$NO}</button>
            </div>
        </div>
    </div>
</div>

{include file='footer.tpl'}