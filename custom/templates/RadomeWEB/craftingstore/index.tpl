{include file='header.tpl'}{include file='navbar.tpl'}
<div class="container">
    {include file='craftingstore/parts/nav.tpl'}
    <div class="card">
        <div class="card-header header-theme">
            {$STORE}
        </div>
        <div class="card-body">
            {$CONTENT}
        </div>
    </div>
</div>
{include file='footer.tpl'}