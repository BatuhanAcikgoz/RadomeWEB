{include file='header.tpl'}
{include file='navbar.tpl'}

<h2 class="container card-header header-theme">
    {$COOKIE_NOTICE_HEADER}
</h2>

<div class="container card card-body" id="cookies">
    {$COOKIE_NOTICE}

    <div class="ui divider"></div>
    <hr>
    <div class="btn btn-theme" onclick="configureCookies()">{$UPDATE_SETTINGS}</div>
</div>

{include file='footer.tpl'}