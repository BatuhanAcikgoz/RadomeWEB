{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container" style="min-height: calc(-175.133px + 100vh);">
    <ol class="breadcrumb">
        {foreach from=$BREADCRUMBS item=breadcrumb}
        <li{if isset($breadcrumb.active)} class="active" {/if}>{if !isset($breadcrumb.active)}<a class="white-link" href="{$breadcrumb.link}">{/if}{$breadcrumb.haberler_title}{if !isset($breadcrumb.active)}</a>{/if}
            </li>
        {/foreach}
    </ol>
    <div class="row">
        <div class="col-md-12">
            <span class="float-right haberler-btns">
            <div class="btn-group">
            	<button type="button" class="btn dropdown-toggle btn-theme" data-toggle="dropdown" style="vertical-align:baseline;">{$SHARE} <span class="caret"></span></button>
            	    <ul class="dropdown-menu dropdown-menu-right" role="menu">
                    <li><a target="_blank" class="dropdown-item" href="{$SHARE_TWITTER_URL}"><i class="fab fa-twitter"></i> {$SHARE_TWITTER}</a></li>
                    <li><a target="_blank" class="dropdown-item" href="{$SHARE_FACEBOOK_URL}"><i class="fab fa-facebook"></i> {$SHARE_FACEBOOK}</a></li>
            	</ul>
            </div>
            {if isset($CAN_MODERATE)}
            <div class="btn-group">
                <button type="button" class="btn dropdown-toggle btn-theme" data-toggle="dropdown">{$MOD_ACTIONS} <span class="caret"></span></button>
            	<ul class="dropdown-menu" role="menu">
                	<li><a class="dropdown-item" href="" data-toggle="modal" data-target="#deleteModal"><i class="fas fa-trash"></i> {$DELETE}</a></li>
            	</ul>
            </div>
            {/if}
            </span>
        </div>
    </div>
    <div class="card">
<div class="card-header text-white header-theme"><a href="{$reply.url}" class="white-text">{if count($TOPIC_LABELS)}{foreach from=$TOPIC_LABELS item=label}{$label} {/foreach}{/if}{if isset($LOCKED) && $smarty.foreach.arr.first}
     <span class="fas fa-lock"></span> {/if}{$reply.heading}</a>
</div>
<div class="card-body" id="post-{$reply.id}">
    <div class="row">
        <div class="col-md-2 col-inv forum-col">
            <center>
                <img class="avatar-img" style="max-width:100px; max-height:100px;" src="{$reply.avatar}" />
                <br/><br />
                <strong><a style="{$reply.user_style}" href="{$reply.profile}" data-poload="{$USER_INFO_URL}{$reply.user_id}" data-html="true" data-placement="top">{$reply.username}</a></strong>
                <br/> {foreach from=$reply.user_groups item=group} {$group}
                <br/> {/foreach} {if $reply.user_title}
                <br/>
                <small>{$reply.user_title}</small> {/if}
                <hr/> {$reply.user_posts_count}
                <br/> {$reply.user_topics_count}
                <br/>
                <hr /> {if count($reply.fields)} {foreach from=$reply.fields item=field} {$field.name}: {$field.value}<br/> {/foreach} {/if}

                        {* Badges Module *}
                        {if isset($USER_BADGES_LIST)}
                            {include file='badges/forum_bdg.tpl'}
                        {/if}
                        {* /Badges Module *}
            </center>
        </div>
        <div class="col-md-10">
            <span data-toggle="tooltip" data-trigger="hover" data-original-title="{$reply.post_date}">{$reply.post_date_rough}</span>
            <span class="float-right">
           {if isset($reply.buttons.edit)}
           <a class="btn btn-theme btn-sm" data-toggle="tooltip" data-trigger="hover" data-original-title="{$reply.buttons.edit.TEXT}" href="{$reply.buttons.edit.URL}"><i class="fas fa-pen fa-fw" aria-hidden="true"></i></a>
           {/if}
           {if isset($reply.buttons.report)}
           <button class="btn btn-theme btn-sm" rel="tooltip" data-trigger="hover" data-original-title="{$reply.buttons.report.TEXT}" data-toggle="modal" data-target="#report{$reply.id}Modal"><i class="fas fa-exclamation-triangle fa-fw" aria-hidden="true"></i></button>
           {/if}
           {if isset($reply.buttons.spam)}
           <button class="btn btn-theme btn-sm" rel="tooltip" data-trigger="hover" data-original-title="{$reply.buttons.spam.TEXT}" data-toggle="modal" data-target="#spam{$reply.id}Modal"><i class="fas fa-flag fa-fw" aria-hidden="true"></i></button>
           {/if}
           {if isset($reply.buttons.delete)}
           <button class="btn btn-theme btn-sm" rel="tooltip" data-trigger="hover" data-original-title="{$reply.buttons.delete.TEXT}" data-toggle="modal" data-target="#delete{$reply.id}Modal"><i class="fas fa-trash fa-fw" aria-hidden="true"></i></button>
           {/if}
           </span>
            <hr/>
            <div class="forum_post">
                {$reply.content}
            </div><br/> {if $reply.edited !== null}
            <small><span rel="tooltip" data-toggle="hover"
              data-original-title="{$reply.edited_full}">{$reply.edited}</span>
           </small>{/if} {if count($reply.post_reactions)}
            <span class="float-right" data-toggle="modal" data-target="#reactions{$reply.id}Modal">
           {foreach from=$reply.post_reactions name="reactions" item=reaction}
           {$reaction.html} x {$reaction.count}
           {if !($smarty.foreach.reactions.last)} | {/if}
           {/foreach}
           </span> {/if} {if $reply.user_id !== $USER_ID} {if isset($REACTIONS) && count($REACTIONS)}
            <br/>
            <div class="well">
                {foreach from=$REACTIONS item=reaction}
                <form class="inline-form" action="{$REACTIONS_URL}" method="post">
                    <input type="hidden" name="token" value="{$TOKEN}">
                    <input type="hidden" name="reaction" value="{$reaction->id}">
                    <input type="hidden" name="post" value="{$reply.id}">
                    <a href="#" onclick="$(this).closest('form').submit();" style="padding:10px;" rel="tooltip" data-toggle="hover" data-original-title="{$reaction->name}">{$reaction->html}</a>
                </form>
                {/foreach}
            </div>
            {else}
            <br/> {/if} {else}
            <br/> {/if}
            <hr/> {$reply.signature}
        </div>
    </div>
</div>
</div>
{if isset($CAN_MODERATE)}
<div class="modal fade" id="spam{$reply.id}Modal" tabindex="-1" role="dialog" aria-labelledby="spam{$reply.id}ModalLabel" aria-hidden="true">
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
<div class="modal fade" id="delete{$reply.id}Modal" tabindex="-1" role="dialog" aria-labelledby="delete{$reply.id}ModalLabel" aria-hidden="true">
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
{if isset($CAN_MODERATE)}
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
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