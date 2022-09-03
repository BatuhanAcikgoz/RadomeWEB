{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">

    <div class="card">
        <div class="card-header header-theme">
            <span style="display: inline-block; margin-top: 7px;">{$VIEW_POLL->subject}</span>

            <ul class="nav nav-tabs float-right">
                {foreach from=$VIEW_POLL_OPTIONS key=key item=opt}
                    <li class="nav-item">
                        <a class="nav-link {if $key == 0}active{/if}" data-toggle="tab"
                            href="#opt{$opt->id}">{$opt->name}</a>
                    </li>
                {/foreach}
            </ul>
        </div>
        <div class="card-body">

            <div class="tab-content" style="padding: 10px;">
                {foreach from=$VIEW_POLL_OPTIONS key=key item=opt}
                    <div class="tab-pane {if $key == 0}active{/if}" id="opt{$opt->id}">
                        {foreach from=$VIEW_POLL_VOTES[$opt->id] item=user}
                            <span style="padding: 10px;"> {$user}, </span>

                        {/foreach}
                    </div>
                {/foreach}
            </div>

        </div>
    </div>

</div>

{include file='footer.tpl'}