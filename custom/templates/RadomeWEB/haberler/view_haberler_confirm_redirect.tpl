{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container" style="min-height: calc(-175.133px + 100vh);">
    <div class="card">
        <div class="card-header header-theme">{$CONFIRM_REDIRECT}</div>
        <div class="card-body">
            {$CONFIRM_REDIRECT}
            <hr />
            <div class="btn-group" role="group">
                <a href="{$FORUM_INDEX}" class="btn btn-secondary">{$NO}</a>
                <a href="{$REDIRECT_URL}" target="_blank" rel="noopener nofollow" class="btn btn-theme">{$YES}</a>
            </div>
        </div>
    </div>
</div>
{include file='footer.tpl'}