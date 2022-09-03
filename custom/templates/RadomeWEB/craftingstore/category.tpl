{include file='header.tpl'}{include file='navbar.tpl'}
<div class="container">
    {include file='craftingstore/parts/nav.tpl'}
    {if isset($NO_PACKAGES)}
    <div class="alert alert-info">
        {$NO_PACKAGES}
    </div>
    {else}
    <div class="row">
        {assign var=i value=0}
        {foreach from=$PACKAGES item=package name=packageArray}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header header-theme">{$package.name}</div>
                <div class="card-body">
                    {if $package.image}
                    <center><img class="rounded" style="max-width: 100%;" src="{$package.image}" alt="{$package.name}"></center>
                    <br /> {/if}
                    <span class="store-text-large">{$package.price} {$CURRENCY}</span>
                    <button role="button" class="btn btn-theme pull-right" data-toggle="modal" data-target="#modal{$package.id}">{$BUY}</button>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal{$package.id}" tabindex="-1" role="dialog" aria-labelledby="modal{$package.id}Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="text-align: center;">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal{$package.id}Label">{$package.name}</h4>
                    </div>
                    {if $package.description}
                    <div class="modal-body">
                        <div class="forum_post">
                            {$package.description}
                        </div>
                    </div>
                    {/if}
                    <div class="modal-footer">
                        <span class="mr-auto store-text-large">{$package.price} {$CURRENCY}</span>
                        <a href="http://{$STORE_URL}/package/{$package.id}" target="_blank" rel="nofollow noopener" class="btn btn-theme">{$BUY}</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{$CLOSE}</button>
                    </div>
                </div>
            </div>
        </div>
        {assign var=i value=$i+1}
        {/foreach}
    </div>
    {/if}
</div>
{include file='footer.tpl'}