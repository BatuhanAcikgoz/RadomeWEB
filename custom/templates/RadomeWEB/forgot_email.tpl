{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container" style="min-height: calc(-175.133px + 100vh);">
<div class="container">
    <div class="card">
        <div class="card-header header-theme">{$FORGOT_EMAIL}</div>
        <div class="card-body">
            <form role="form" action="" method="post">
                <p>{$FORGOT_EMAIL_INSTRUCTIONS}</p>
                {if isset($ERROR)}
                <div class="alert alert-danger">
                    {$ERROR}
                </div>
                {/if}
                <div class="form-group">
                    <input type="username" id="inputUsername" name="username" placeholder="{$USERNAME_PLACEHOLDER}" class="form-control">
                </div>
                <div class="form-group">
                    <input type="password" id="inputPassword" name="password" placeholder="{$PASSWORD}" class="form-control">
                </div>
                <hr>
                <p>{$NEW_MAIL_TYPE}</p>
                <div class="form-group">
                    <input type="email" id="inputEmail" name="email" placeholder="{$EMAIL}" class="form-control">
                </div>
                <div class="form-group">
                    <input type="hidden" name="token" value="{$FORM_TOKEN}">
                    <button type="submit" class="btn btn-theme">{$SUBMIT}</button>
                </div>
            </form>
        </div>
    </div>
</div>
{include file='footer.tpl'}