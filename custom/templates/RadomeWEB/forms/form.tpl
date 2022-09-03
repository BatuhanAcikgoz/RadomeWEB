{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container">
    {if isset($SUCCESS)}
        <div class="alert alert-success">
            {$SUCCESS}
        </div>
        {/if} {if isset($ERRORS)}
        <div class="alert alert-danger">
            {foreach from=$ERRORS item=error} {$error}
            <br /> {/foreach}
        </div>
    {/if}
    <div class="row">
        {if count($WIDGETS_LEFT)}
            <div class="col-md-3">
                {foreach from=$WIDGETS_LEFT item=widget}
                    {$widget}
                {/foreach}
            </div>
        {/if}

        <div
            class="{if count($WIDGETS_LEFT) && count($WIDGETS_RIGHT)}col-md-6{elseif count($WIDGETS_LEFT) || count($WIDGETS_RIGHT)}col-md-9{else}col-md-12{/if}">
            <div class="card">
                <div class="card-header header-theme">{$TITLE}</div>
                <div class="card-body">
                    <form action="" method="post" id="forms">
                        {foreach from=$FIELDS item=field}
                            <div class="form-group">
                                {if $field.type == 5}
                                    <hr />
                                {elseif $field.type == 4}
                                    {', '|implode:$field.options}
                                {else}
                                    <label for="{$field.id}">{$field.name} {if $field.required} <span
                                            class="text-danger"><strong>*</strong></span>{/if}</label>
                                {/if}
                                {if $field.type == "1"}
                                    <input class="form-control" type="text" name="{$field.id}" id="{$field.id}" placeholder="{$field.name}"{if $field.required} required{/if}>
                                {elseif $field.type == "2"}
                                    <select class="form-control"  name="{$field.id}" id="{$field.id}"{if $field.required} required{/if}>
                                        {foreach from=$field.options item=option}
                                            <option value="{$option}">{$option}</option>
                                        {/foreach}
                                    </select>
                                {elseif $field.type == "3"}
                                    <textarea class="form-control" name="{$field.id}" id="{$field.id}"{if $field.required} required{/if}></textarea>
                                {elseif $field.type == "6"}
                                    <input type="number" class="form-control" name="{$field.id}" id="{$field.id}" placeholder="{$field.name}"{if $field.required} required{/if}>
                                {elseif $field.type == "7"}
                                    <input type="email" class="form-control" name="{$field.id}" id="{$field.id}" placeholder="{$field.name}"{if $field.required} required{/if}>
                                {/if}
                            </div>
                        {/foreach}
                        {if $CAPTCHA}
                            <div class="form-group">
                              {$CAPTCHA}
                            </div>
                        {/if}
                        <input type="hidden" name="token" value="{$TOKEN}">
                        <button type="submit" class="btn btn-theme">{$SUBMIT}</button>
                    </form>
                </div>
            </div>
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