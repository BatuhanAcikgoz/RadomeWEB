<div class="card">
    <div class="content">
        <div class="card-header header-theme">{$COOKIE_NOTICE_HEADER}</h4>
        <div class="card-body">
            <p>{$COOKIE_NOTICE_BODY}</p>
            {if $COOKIE_DECISION_MADE}
            <a class="btn btn-theme" href="{$COOKIE_URL}">{$COOKIE_NOTICE_CONFIGURE}</a>
            {/if}
        </div>
    </div>
</div>