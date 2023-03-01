{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container" style="min-height: calc(-175.133px + 100vh);">
    <div class="card">
        <div class="card-header header-theme">{$CREATING_TOPIC_IN}</div>
        <div class="card-body">
            <div class="container">
                {if isset($ERROR)}
                <div class="alert alert-danger">
                    {foreach from=$ERROR item=item name=arr} {$item}
                    <br /> {/foreach}
                </div>
                {/if}
                <form action="" method="post">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-lg" name="title" placeholder="{$TOPIC_TITLE}" maxlength="64">
                    </div>
                    {if count($LABELS)}
                    <div class="form-group">
                        {foreach from=$LABELS item=label}
                        <label for="{$label.id}">{$label.html}</label>
                        <input type="checkbox" name="topic_label[]" id="{$label.id}" value="{$label.id}"{if $label.checked} checked{/if}> {/foreach}
                    </div>
                    {/if} {if !isset($MARKDOWN)}
                    <div class="form-group">
                        <textarea style="width:100%" name="content" id="reply" rows="20">{$CONTENT}</textarea>
                    </div>
                    {else}
                    <div class="form-group">
                        <textarea class="form-control" style="width:100%" id="markdown" name="content" rows="20">{$CONTENT}</textarea>
                        <span class="float-right"><i data-toggle="popover" data-placement="top" data-html="true" data-content="{$MARKDOWN_HELP}" class="fa fa-question-circle text-info" aria-hidden="true"></i></span>
                    </div>
                    <div class="form-group custom-control custom-switch">
                        <input id="inputSendAlert" name="send_alert" type="checkbox" class="custom-control-input" />
                        <label class="custom-control-label" for="inputSendAlert">{$SEND_ALERT}</label>
                    </div>
                    <div class="form-group custom-control custom-switch">
                        <input id="inputSendEmail" name="send_email" type="checkbox" class="custom-control-input" />
                        <label class="custom-control-label" for="inputSendEmail">{$SEND_EMAIL}</label>
                    </div>
                    {/if} {$TOKEN}
                    <button type="submit" class="btn btn-theme">{$SUBMIT}</button>
                    <a href="#" class="btn btn-secondary" data-toggle="modal" data-target="#cancelModal">{$CANCEL}</a>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Cancellation modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title" id="cancelModalLabel">{$CANCEL}</span>
            </div>
            <div class="modal-body">
                {$CONFIRM_CANCEL}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-theme" data-dismiss="modal">{$CLOSE}</button>
                <a href="{$HABERLER_LINK}" class="btn btn-secondary">{$CANCEL}</a>
            </div>
        </div>
    </div>
</div>
{include file='scripts.tpl'}
{include file='footer.tpl'}