{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container">
    <div class="card">
        <div class="card-header header-theme">{$VERIFY_ACCOUNT}</div>
        <div class="card-body">
            <div class="alert alert-info">{$VERIFY_ACCOUNT_HELP}</div>
            {if !isset($STEP)}
                <div id="mcassoc-body">{$MCASSOC}</div>
            {else}
                {if isset($ERROR)}
                    <div class="alert alert-danger">
                        {$ERROR}
                        <br />
                        <a class="btn btn-danger" href="{$RETRY_LINK}">{$RETRY_TEXT}</a>
                    </div>
                {elseif isset($SUCCESS)}
                    <div class="alert alert-success">
                        {$SUCCESS}
                        <br />
                        <a class="btn btn-theme" href="{$LOGIN_LINK}">{$LOGIN_TEXT}</a>
                    </div>
                {/if}
            {/if}
        </div>
    </div>
</div>
{include file='footer.tpl'}