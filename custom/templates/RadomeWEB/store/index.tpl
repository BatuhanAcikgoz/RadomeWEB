{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container" style="min-height: calc(-175.133px + 100vh);">
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            {include file='store/navbar.tpl'}
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header header-theme">{$STORE}</div>
                <div class="card-body">{$CONTENT}</div>
                <div class="card-body">
                <div class="row">              
                {foreach from=$CATEGORIES item=category}
                <div class="col-md-3">
                <div class="img-card-wrapper">
                <div class="img-container">
                <a class="{if $category.active}active {/if}img-card" href="{$category.url}">
                    <img class="card-img-top lazyload loaded" src="https://premium.leaderos.com.tr/apps/main/public/assets/img/servers/1f803f144d902384e58557e0f8b5ed62.jpg" alt="{$STORE}-{$category.title}" data-ll-status="loaded">
                </a>
                <div class="img-card-bottom">
                <h5 class="mb-0">{$category.title}</h5>
                </div>
                </div>
                </div>
                </div>
                {/foreach}
                </div>
            </div>
        </div>

        {if count($WIDGETS_RIGHT)}
            <div class="col-lg-3">
                {foreach from=$WIDGETS_RIGHT item=widget}
                    {$widget}
                {/foreach}
            </div>
        {/if}
    </div>
</div>
{include file='footer.tpl'}