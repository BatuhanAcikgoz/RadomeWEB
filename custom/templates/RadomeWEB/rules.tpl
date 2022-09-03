{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container">
    <div class="row">

        {if count($WIDGETS_LEFT)}
            <div class="col-md-3">
                {foreach from=$WIDGETS_LEFT item=widget}
                    {$widget}
                {/foreach}
            </div>
        {/if}

        <div class="{if count($WIDGETS_LEFT) && count($WIDGETS_RIGHT)}col-md-6{elseif count($WIDGETS_LEFT) || count($WIDGETS_RIGHT)}col-md-9{else}col-md-12{/if}">
            <div class="card">
                <div class="card-header header-theme">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                aria-controls="home" aria-selected="true"><i class="fas fa-gavel"></i> {$RULES}</a>
                        </li>
                        {foreach from=$CATAGORIES item=catagory}
                            <li class="nav-item">
                                <a class="nav-link" id="id-{$catagory.id}-tab" data-toggle="tab" href="#id-{$catagory.id}"
                                    role="tab" aria-controls="id-{$catagory.id}"
                                    aria-selected="false">{if isset($catagory.icon) && $catagory.icon|count_characters > 0}{$catagory.icon}
                                    {/if}{$catagory.name}</a>
                            </li>
                        {/foreach}
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            {$MESSAGE}
                            {if !empty($BUTTONS)}<br />
                                <div style="text-align:center">
                                    {foreach from=$BUTTONS item=button}
                                        <a class="btn btn-theme btn-rules mb-2" href="{$button.link}">{$button.name}</a>
                                    {/foreach}
                            </div>{/if}
                        </div>
                        {foreach from=$CATAGORIES item=catagory}
                            <div class="tab-pane" id="id-{$catagory.id}" role="tabpanel"
                            aria-labelledby="id-{$catagory.id}-tab">{$catagory.rules}</div>{/foreach}
                    </div>
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