{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container" style="min-height: calc(-175.133px + 100vh);">
    <ol class="breadcrumb">
        {foreach from=$BREADCRUMBS item=breadcrumb}
            <li{if isset($breadcrumb.active)} class="active" {/if}>{if !isset($breadcrumb.active)}<a class="white-link"
                    href="{$breadcrumb.link}">{/if}{$breadcrumb.haberler_title}{if !isset($breadcrumb.active)}</a>{/if}
                </li>
            {/foreach}
    </ol>
    <div class="row">
        <div class="col-md-12">
            <span class="float-right haberler-btns">
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle btn-theme" data-toggle="dropdown"
                        style="vertical-align:baseline;">{$SHARE} <span class="caret"></span></button>
                    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                        <li><a target="_blank" class="dropdown-item" href="{$SHARE_TWITTER_URL}"><i
                                    class="fab fa-twitter"></i> {$SHARE_TWITTER}</a></li>
                        <li><a target="_blank" class="dropdown-item" href="{$SHARE_FACEBOOK_URL}"><i
                                    class="fab fa-facebook"></i> {$SHARE_FACEBOOK}</a></li>
                    </ul>
                </div>
                {if isset($CAN_MODERATE)}
                    <div class="btn-group">
                        <button type="button" class="btn dropdown-toggle btn-theme" data-toggle="dropdown">{$MOD_ACTIONS}
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a class="dropdown-item" href="" data-toggle="modal" data-target="#deleteModal"><i
                                        class="fas fa-trash"></i> {$DELETE}</a></li>
                        </ul>
                    </div>
                {/if}
            </span>
        </div>
    </div>
    {foreach from=$REPLIES item=reply name=arr}
    <div class="card">
        <div class="card-header text-white header-theme"><a href="{$reply.url}" class="white-text">{$reply.heading}</a>
        </div>
        <div class="card-body" id="post-{$reply.id}">
            <div class="row">
                <div class="col-md-2 col-inv forum-col">
                    <center>
                        <img class="avatar-img" style="max-width:100px; max-height:100px;" src="{$reply.avatar}" />
                        <br /><br />
                        <strong><a style="{$reply.user_style}" href="{$reply.profile}"
                                data-poload="{$USER_INFO_URL}{$reply.user_id}" data-html="true"
                                data-placement="top">{$reply.username}</a></strong>
                        <br />
                        {if $reply.user_title}
                            <br />
                            <small>{$reply.user_title}</small>
                            <hr />
                        {/if}

                        {* Badges Module *}
                        {if isset($USER_BADGES_LIST)}
                            {include file='badges/forum_bdg.tpl'}
                        {/if}
                        {* /Badges Module *}
                    </center>
                </div>
                <div class="col-md-10">
                    <span data-toggle="tooltip" data-trigger="hover"
                        data-original-title="{$reply.post_date}">{$reply.post_date_rough}</span>
                    <span class="float-right">
                        {if isset($reply.buttons.edit)}
                            <a class="btn btn-theme btn-sm" data-toggle="tooltip" data-trigger="hover"
                                data-original-title="{$reply.buttons.edit.TEXT}" href="{$reply.buttons.edit.URL}"><i
                                    class="fas fa-pen fa-fw" aria-hidden="true"></i></a>
                        {/if}
                        {if isset($reply.buttons.delete)}
                            <button class="btn btn-theme btn-sm" rel="tooltip" data-trigger="hover"
                                data-original-title="{$reply.buttons.delete.TEXT}" data-toggle="modal"
                                data-target="#delete{$reply.id}Modal"><i class="fas fa-trash fa-fw"
                                    aria-hidden="true"></i></button>
                        {/if}
                    </span>
                    <hr />
                    <div class="forum_post">
                        {$reply.content}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {if isset($CAN_MODERATE)}
        <div class="modal fade" id="spam{$reply.id}Modal" tabindex="-1" role="dialog"
            aria-labelledby="spam{$reply.id}ModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="modal-title" id="spam{$reply.id}ModalLabel">{$MARK_AS_SPAM}</span>
                    </div>
                    <div class="modal-body">
                        {$CONFIRM_SPAM_POST}
                        <form action="{$reply.buttons.spam.URL}" method="post">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{$CANCEL}</button>
                            <input type="hidden" name="post" value="{$reply.id}">
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <button type="submit" class="btn btn-theme">{$MARK_AS_SPAM}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    {/if}
    <div class="modal fade" id="delete{$reply.id}Modal" tabindex="-1" role="dialog"
        aria-labelledby="delete{$reply.id}ModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="delete{$reply.id}ModalLabel">{$CONFIRM_DELETE_SHORT}</span>
                </div>
                <div class="modal-body">
                    {$CONFIRM_DELETE_POST}<br /><br />
                    <form action="{$reply.buttons.delete.URL}" method="post">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{$CANCEL}</button>
                        <input type="hidden" name="tid" value="{$TOPIC_ID}">
                        <input type="hidden" name="number" value="{$reply.buttons.delete.NUMBER}">
                        <input type="hidden" name="pid" value="{$reply.id}">
                        <input type="hidden" name="token" value="{$TOKEN}">
                        <button type="submit" class="btn btn-theme">{$reply.buttons.delete.TEXT}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
{/foreach}
{if isset($CAN_MODERATE)}
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="deleteModalLabel">{$CONFIRM_DELETE_SHORT}</span>
                </div>
                <div class="modal-body">
                    {$CONFIRM_DELETE}<br /><br />
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{$CANCEL}</button>
                    <a href="{$DELETE_URL}" class="btn btn-theme">{$DELETE}</a>
                </div>
            </div>
        </div>
    </div>   
{/if} {include file='footer.tpl'}