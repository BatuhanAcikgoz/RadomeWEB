{if isset($THEME_NAVBAR) && $THEME_NAVBAR eq 'double'}
<nav class="navbar navbar-expand-md navbar-theme navbar-double">
    <div class="container">
        <button class="navbar-toggler nav-link" type="button"{* data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation"*}><i class="fas fa-bars"></i> {$MENU}</button>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav mx-auto">
                {foreach from=$NAV_LINKS key=name item=item} {if isset($item.items)}
                <li class="nav-item dropdown more-dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{$item.icon} {$item.title}</a>
                    <div class="dropdown-menu more-dropdown-menu">
                        {foreach from=$item.items item=dropdown}
                        <a class="dropdown-item" href="{$dropdown.link}" {if isset($dropdown.target) && $dropdown.target|count_characters> 2} target="{$dropdown.target}"{/if}>{$dropdown.icon} {$dropdown.title}</a> {/foreach}
                    </div>
                </li>
                {else}
                <li class="nav-item">
                    <a class="nav-link {if isset($item.active)}nav-link-active{/if}" href="{$item.link}" {if isset($item.target) && $item.target|count_characters> 2} target="{$item.target}"{/if}>{$item.icon} {$item.title}</a>
                </li>
                {/if} {/foreach}
            </ul>
        </div>
    </div>
</nav>
{/if}
{if isset($THEME_NAVBAR) && $THEME_NAVBAR eq 'top'}
<nav class="navbar navbar-expand-md navbar-theme">
    <div class="container">
        <button class="navbar-toggler nav-link" type="button"{* data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation"*}><i class="fas fa-bars"></i> {$MENU}</button>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="nav navbar-nav">
                {foreach from=$NAV_LINKS key=name item=item} {if isset($item.items)}
                <li class="nav-item dropdown more-dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{$item.icon} {$item.title}</a>
                    <div class="dropdown-menu more-dropdown-menu">
                        {foreach from=$item.items item=dropdown}
                        <a class="dropdown-item" href="{$dropdown.link}" {if isset($dropdown.target) && $dropdown.target|count_characters> 2} target="{$dropdown.target}"{/if}>{$dropdown.icon} {$dropdown.title}</a> {/foreach}
                    </div>
                </li>
                {else}
                <li class="nav-item">
                    <a class="nav-link {if isset($item.active)}nav-link-active{/if}" href="{$item.link}" {if isset($item.target) && $item.target|count_characters> 2} target="{$item.target}"{/if}>{$item.icon} {$item.title}</a>
                </li>
                {/if} {/foreach}
            </ul>
            <ul class="nav navbar-nav ml-auto">
                {if isset($MESSAGING_LINK)}
                <li class="nav-item dropdown alert-dropdown">
                    <a href="#" class="nav-link dropdown-toggle no-caret" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="msg-alert">
                            <i class="fa fa-flag"></i> 
                            <span class="mobile_only">{$ALERTS}</span>
                            <div class="alerts"></div>
                        </span>
                    </a>
                    <div class="dropdown-menu alert-dropdown-menu">
                        <div class="dropdown-item alert_dropdown">{$LOADING}</div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{$ALERTS_LINK}">{$VIEW_ALERTS}</a>
                    </div>
                </li>
                {/if} {foreach from=$USER_AREA key=name item=item} {if isset($item.items)}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{$item.title}</a>
                    <div class="dropdown-menu">
                        {foreach from=$item.items item=dropdown} {if isset($dropdown.separator)}
                        <div class="dropdown-divider"></div>
                        {else}
                        <a class="dropdown-item" href="{$dropdown.link}" {if isset($dropdown.target) && $dropdown.target|count_characters> 2} target="{$dropdown.target}"{/if}>{$dropdown.title}</a> {/if} {/foreach}
                    </div>
                </li>
                {else}
                <li class="nav-item{if isset($item.active)} active{/if}">
                    <a class="nav-link" href="{$item.link}" {if isset($item.target) && $item.target|count_characters> 2} target="{$item.target}"{/if}>{$item.title}</a>
                </li>
                {/if} {/foreach} {if isset($LOGGED_IN_USER)} {if isset($USER_DROPDOWN)} {foreach from=$USER_DROPDOWN key=name item=item} {if isset($item.items)}
                <li class="nav-item dropdown user-dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    {if isset($LOGGED_IN_USER)}
                    <img src="{$LOGGED_IN_USER.avatar}" alt="{$LOGGED_IN_USER.username}" class="avatar-img" style="max-height:20px;max-width:20px;"/>{/if} {$item.title}
                    </a>
                    <div class="dropdown-menu user-dropdown-menu">
                        {foreach from=$item.items item=dropdown} {if isset($dropdown.separator)}
                        <div class="dropdown-divider"></div>
                        {else}
                        <a class="dropdown-item" href="{$dropdown.link}" {if isset($dropdown.target) && $dropdown.target|count_characters> 2} target="{$dropdown.target}"{/if}>{$dropdown.title}</a> {/if} {/foreach}
                    </div>
                </li>
                {else} {* Normal link *}
                <li class="nav-item{if isset($item.active)} active{/if}" style="padding-right:10px;">
                    <a class="nav-link bottom-nav" href="{$item.link}" {if isset($item.target) && $item.target|count_characters> 2} target="{$item.target}"{/if}>{$item.title}</a>
                </li>
                {/if} {/foreach} {/if} {else} {if isset($USER_DROPDOWN)} {foreach from=$USER_DROPDOWN key=name item=item} {if isset($item.items)} {foreach from=$item.items item=dropdown} {if isset($dropdown.separator)} {else}
                <li class="nav-item">
                    <a class="nav-link bottom-nav {if isset($item.active)}nav-link-active{/if}" href="{$dropdown.link}" {if isset($dropdown.target) && $dropdown.target|count_characters> 2} target="{$dropdown.target}"{/if}>{$dropdown.title}</a>
                </li>
                {/if} {/foreach}{/if} {/foreach}{/if} {/if}
            </ul>
        </div>
    </div>
</nav>
{/if}
<div class="color-overlay">
    <div class="header" id="header-pjs" {if isset($THEME_C_OVERLAY) && $THEME_C_OVERLAY|count_characters > 2}style="opacity: 0.2; z-index: 1"{else}style="opacity: 1; z-index: 4"{/if}></div>
</div>

<a href="/">
<picture>
    {if isset($THEME_LOGO_WEBP) && $THEME_LOGO_WEBP|count_characters > 4}<source srcset="{$THEME_LOGO_WEBP}" type="image/webp">{/if}
    <source srcset="{$THEME_LOGO}"> 
    <img class="logo{if isset($THEME_AL) && $THEME_AL|count_characters > 2} animated-logo{/if}" alt="logo" src='{$THEME_LOGO}'>
</picture>
</a>

{if isset($THEME_DS_BOX) && $THEME_DS_BOX|count_characters > 2} {if isset($MINECRAFT)}
<div class="box box1">
    {if isset($CLICK_TO_COPY_TOOLTIP)}
        <div class="col-lg-12 discordheader2 d-none d-lg-block">
        <svg class="creeper" viewBox="0 0 24 24">
            <path fill="#93bf85" d="M4,2H20A2,2 0 0,1 22,4V20A2,2 0 0,1 20,22H4A2,2 0 0,1 2,20V4A2,2 0 0,1 4,2M6,6V10H10V12H8V18H10V16H14V18H16V12H14V10H18V6H14V10H10V6H6Z" />
        </svg>
        <div class="discord-text-right">
        <span class="online-discord" onclick="copyToClipboard('#ip')" data-toggle="tooltip" title="{$CLICK_TO_COPY_TOOLTIP}"><small>{$CONNECT_WITH}</small></span>
        {/if} {if isset($SERVER_QUERY)} {if isset($SERVER_QUERY.status_value) && $SERVER_QUERY.status_value == 1}
        <span class="join-discord"> {$SERVER_QUERY.x_players_online}</span> {else}
        <span class="join-discord"> {$SERVER_OFFLINE}</span> {/if}
            </div>
        </a>
    </div>
{/if}
</div>
{/if}
<div class="box box2">
<div class="col-lg-12 discordheader d-none d-lg-block">
                    <a class="discord-href" href="https://{$THEME_DISCORD_SERVER}" target="_blank">
                        <div class="discord-text-right">
                            <span class="online-discord"><small">{$DISCORD_API_COUNT} {$DISCORD_BOX_STATUS_1}</small></span><span class="join-discord">{$DISCORD_BOX_STATUS_2}</span>
                        </div>
                        <i class="fab fa-discord"></i>
                    </a>
                </div>
</div>
{/if}
{if isset($THEME_NAVBAR) && $THEME_NAVBAR eq 'bottom'}
<nav class="navbar navbar-expand-md navbar-theme">
    <div class="container">
        <button class="navbar-toggler nav-link" type="button"{* data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation"*}><i class="fas fa-bars"></i> {$MENU}</button>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="nav navbar-nav">
                {foreach from=$NAV_LINKS key=name item=item} {if isset($item.items)}
                <li class="nav-item dropdown more-dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{$item.icon} {$item.title}</a>
                    <div class="dropdown-menu more-dropdown-menu">
                        {foreach from=$item.items item=dropdown}
                        <a class="dropdown-item" href="{$dropdown.link}" {if isset($dropdown.target) && $dropdown.target|count_characters> 2} target="{$dropdown.target}"{/if}>{$dropdown.icon} {$dropdown.title}</a> {/foreach}
                    </div>
                </li>
                {else}
                <li class="nav-item">
                    <a class="nav-link {if isset($item.active)}nav-link-active{/if}" href="{$item.link}" {if isset($item.target) && $item.target|count_characters> 2} target="{$item.target}"{/if}>{$item.icon} {$item.title}</a>
                </li>
                {/if} {/foreach}
            </ul>
            <ul class="nav navbar-nav ml-auto">
                {if isset($MESSAGING_LINK)}
                <li class="nav-item dropdown alert-dropdown">
                    <a href="#" class="nav-link dropdown-toggle no-caret" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="msg-alert">
                            <i class="fa fa-flag"></i> 
                            <span class="mobile_only">{$ALERTS}</span>
                            <div class="alerts"></div>
                        </span>
                    </a>
                    <div class="dropdown-menu alert-dropdown-menu">
                        <div class="dropdown-item alert_dropdown">{$LOADING}</div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{$ALERTS_LINK}">{$VIEW_ALERTS}</a>
                    </div>
                </li>
                {/if}{foreach from=$USER_AREA key=name item=item} {if isset($item.items)}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{$item.title}</a>
                    <div class="dropdown-menu">
                        {foreach from=$item.items item=dropdown} {if isset($dropdown.separator)}
                        <div class="dropdown-divider"></div>
                        {else}
                        <a class="dropdown-item" href="{$dropdown.link}" {if isset($dropdown.target) && $dropdown.target|count_characters> 2} target="{$dropdown.target}"{/if}>{$dropdown.title}</a> {/if} {/foreach}
                    </div>
                </li>
                {else}
                <li class="nav-item{if isset($item.active)} active{/if}">
                    <a class="nav-link" href="{$item.link}" {if isset($item.target) && $item.target|count_characters> 2} target="{$item.target}"{/if}>{$item.title}</a>
                </li>
                {/if} {/foreach} {if isset($LOGGED_IN_USER)} {if isset($USER_DROPDOWN)} {foreach from=$USER_DROPDOWN key=name item=item} {if isset($item.items)}
                <li class="nav-item dropdown user-dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    {if isset($LOGGED_IN_USER)}
                    <img src="{$LOGGED_IN_USER.avatar}" alt="{$LOGGED_IN_USER.username}" class="avatar-img" style="max-height:20px;max-width:20px;"/>{/if} {$item.title}
                    </a>
                    <div class="dropdown-menu user-dropdown-menu">
                        {foreach from=$item.items item=dropdown} {if isset($dropdown.separator)}
                        <div class="dropdown-divider"></div>
                        {else}
                        <a class="dropdown-item" href="{$dropdown.link}" {if isset($dropdown.target) && $dropdown.target|count_characters> 2} target="{$dropdown.target}"{/if}>{$dropdown.title}</a> {/if} {/foreach}
                    </div>
                </li>
                {else} {* Normal link *}
                <li class="nav-item{if isset($item.active)} active{/if}" style="padding-right:10px;">
                    <a class="nav-link bottom-nav" href="{$item.link}" {if isset($item.target) && $item.target|count_characters> 2} target="{$item.target}"{/if}>{$item.title}</a>
                </li>
                {/if} {/foreach} {/if} {else} {if isset($USER_DROPDOWN)} {foreach from=$USER_DROPDOWN key=name item=item} {if isset($item.items)} {foreach from=$item.items item=dropdown} {if isset($dropdown.separator)} {else}
                <li class="nav-item">
                    <a class="nav-link bottom-nav {if isset($item.active)}nav-link-active{/if}" href="{$dropdown.link}" {if isset($dropdown.target) && $dropdown.target|count_characters> 2} target="{$dropdown.target}"{/if}>{$dropdown.title}</a>
                </li>
                {/if} {/foreach}{/if} {/foreach}{/if} {/if}
            </ul>
        </div>
    </div>
</nav>
{/if}
{if isset($THEME_NAVBAR) && $THEME_NAVBAR eq 'double'}
<nav class="navbar navbar-theme navbar-double navbar-double-bottom navbar-expand">
    <div class="container">
        <ul class="navbar-nav mx-auto">
            {if isset($MESSAGING_LINK)}
            <li class="nav-item dropdown alert-dropdown">
                <a href="#" class="nav-link dropdown-toggle no-caret" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="msg-alert">
                        <i class="fa fa-flag"></i> 
                        <span class="col-inv">{$ALERTS}</span>
                        <div class="alerts"></div>
                    </span>
                </a>
                <div class="dropdown-menu alert-dropdown-menu">
                    <div class="dropdown-item alert_dropdown">{$LOADING}</div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{$ALERTS_LINK}">{$VIEW_ALERTS}</a>
                </div>
            </li>
            {/if}{foreach from=$USER_AREA key=name item=item} {if isset($item.items)}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{$item.title}</a>
                <div class="dropdown-menu">
                    {foreach from=$item.items item=dropdown} {if isset($dropdown.separator)}
                    <div class="dropdown-divider"></div>
                    {else}
                    <a class="dropdown-item" href="{$dropdown.link}" {if isset($dropdown.target) && $dropdown.target|count_characters> 2} target="{$dropdown.target}"{/if}>{$dropdown.title}</a> {/if} {/foreach}
                </div>
            </li>
            {else}
            <li class="nav-item{if isset($item.active)} active{/if}">
                <a class="nav-link" href="{$item.link}" {if isset($item.target) && $item.target|count_characters> 2} target="{$item.target}"{/if}>{$item.title}</a>
            </li>
            {/if} {/foreach} {if isset($LOGGED_IN_USER)} {if isset($USER_DROPDOWN)} {foreach from=$USER_DROPDOWN key=name item=item} {if isset($item.items)}
            <li class="nav-item dropdown user-dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                {if isset($LOGGED_IN_USER)}
                <img src="{$LOGGED_IN_USER.avatar}" alt="{$LOGGED_IN_USER.username}" class="avatar-img" style="max-height:20px;max-width:20px;"/>{/if} {$item.title}
                </a>
                <div class="dropdown-menu user-dropdown-menu">
                    {foreach from=$item.items item=dropdown} {if isset($dropdown.separator)}
                    <div class="dropdown-divider"></div>
                    {else}
                    <a class="dropdown-item" href="{$dropdown.link}" {if isset($dropdown.target) && $dropdown.target|count_characters> 2} target="{$dropdown.target}"{/if}>{$dropdown.title}</a> {/if} {/foreach}
                </div>
            </li>
            {else} {* Normal link *}
            <li class="nav-item{if isset($item.active)} active{/if}" style="padding-right:10px;">
                <a class="nav-link bottom-nav" href="{$item.link}" {if isset($item.target) && $item.target|count_characters> 2} target="{$item.target}"{/if}>{$item.title}</a>
            </li>
            {/if} {/foreach} {/if} {else} {if isset($USER_DROPDOWN)} {foreach from=$USER_DROPDOWN key=name item=item} {if isset($item.items)} {foreach from=$item.items item=dropdown} {if isset($dropdown.separator)} {else}
            <li class="nav-item">
                <a class="nav-link bottom-nav {if isset($item.active)}nav-link-active{/if}" href="{$dropdown.link}" {if isset($dropdown.target) && $dropdown.target|count_characters> 2} target="{$dropdown.target}"{/if}>{$dropdown.title}</a>
            </li>
            {/if} {/foreach}{/if} {/foreach}{/if} {/if}
        </ul>
    </div>
</nav>
{/if}
<div class="radomeweb-navbar-menu">
    <div class="nav-header">
        <div class="nav-header-text">{$smarty.const.SITE_NAME}
            <div id="nav-header-close" class="nav-header-close">x</div>
        </div>
    </div>
    {foreach from=$NAV_LINKS key=name item=item} {if isset($item.items)}
        <li class="nav-item dropdown more-dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{$item.icon} {$item.title}</a>
            <div class="dropdown-menu more-dropdown-menu">
                {foreach from=$item.items item=dropdown}
                <a class="dropdown-item" href="{$dropdown.link}" {if isset($dropdown.target) && $dropdown.target|count_characters> 2} target="{$dropdown.target}"{/if}>{$dropdown.icon} {$dropdown.title}</a> {/foreach}
            </div>
        </li>
    {else}
        <li class="nav-item">
            <a class="nav-link {if isset($item.active)}nav-link-active{/if}" href="{$item.link}" {if isset($item.target) && $item.target|count_characters> 2} target="{$item.target}"{/if}>{$item.icon} {$item.title}</a>
        </li>
    {/if} {/foreach}


    {if isset($THEME_NAVBAR) && ($THEME_NAVBAR eq 'bottom' || $THEME_NAVBAR eq 'top')}
<hr />
                        {if isset($MESSAGING_LINK)}
                        <li class="nav-item dropdown alert-dropdown">
                            <a href="#" class="nav-link dropdown-toggle no-caret" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="msg-alert">
                                    <i class="fa fa-flag"></i> 
                                    <span class="mobile_only">{$ALERTS}</span>
                                    <div class="alerts"></div>
                                </span>
                            </a>
                            <div class="dropdown-menu alert-dropdown-menu">
                                <div class="dropdown-item alert_dropdown">{$LOADING}</div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{$ALERTS_LINK}">{$VIEW_ALERTS}</a>
                            </div>
                        </li>
                        {/if}{foreach from=$USER_AREA key=name item=item} {if isset($item.items)}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{$item.title}</a>
                            <div class="dropdown-menu">
                                {foreach from=$item.items item=dropdown} {if isset($dropdown.separator)}
                                <div class="dropdown-divider"></div>
                                {else}
                                <a class="dropdown-item" href="{$dropdown.link}" {if isset($dropdown.target) && $dropdown.target|count_characters> 2} target="{$dropdown.target}"{/if}>{$dropdown.title}</a> {/if} {/foreach}
                            </div>
                        </li>
                        {else}
                        <li class="nav-item{if isset($item.active)} active{/if}">
                            <a class="nav-link" href="{$item.link}" {if isset($item.target) && $item.target|count_characters> 2} target="{$item.target}"{/if}>{$item.title}</a>
                        </li>
                        {/if} {/foreach} {if isset($LOGGED_IN_USER)} {if isset($USER_DROPDOWN)} {foreach from=$USER_DROPDOWN key=name item=item} {if isset($item.items)}
                        <li class="nav-item dropdown user-dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            {if isset($LOGGED_IN_USER)}
                            <img src="{$LOGGED_IN_USER.avatar}" alt="{$LOGGED_IN_USER.username}" class="avatar-img" style="max-height:20px;max-width:20px;"/>{/if} {$item.title}
                            </a>
                            <div class="dropdown-menu user-dropdown-menu">
                                {foreach from=$item.items item=dropdown} {if isset($dropdown.separator)}
                                <div class="dropdown-divider"></div>
                                {else}
                                <a class="dropdown-item" href="{$dropdown.link}" {if isset($dropdown.target) && $dropdown.target|count_characters> 2} target="{$dropdown.target}"{/if}>{$dropdown.title}</a> {/if} {/foreach}
                            </div>
                        </li>
                        {else} {* Normal link *}
                        <li class="nav-item{if isset($item.active)} active{/if}" style="padding-right:10px;">
                            <a class="nav-link bottom-nav" href="{$item.link}" {if isset($item.target) && $item.target|count_characters> 2} target="{$item.target}"{/if}>{$item.title}</a>
                        </li>
                        {/if} {/foreach} {/if} {else} {if isset($USER_DROPDOWN)} {foreach from=$USER_DROPDOWN key=name item=item} {if isset($item.items)} {foreach from=$item.items item=dropdown} {if isset($dropdown.separator)} {else}
                        <li class="nav-item">
                            <a class="nav-link bottom-nav {if isset($item.active)}nav-link-active{/if}" href="{$dropdown.link}" {if isset($dropdown.target) && $dropdown.target|count_characters> 2} target="{$dropdown.target}"{/if}>{$dropdown.title}</a>
                        </li>
                        {/if} {/foreach}{/if} {/foreach}{/if} {/if}
        {/if}


</div>
<div class="overlay"></div>
<div class="container mt-4"{if isset($THEME_NAVBAR) && $THEME_NAVBAR eq 'top'}style="margin-top: -60px"{/if}>
    {if isset($MAINTENANCE_ENABLED)}
    <div class="alert alert-danger" role="alert">
        {$MAINTENANCE_ENABLED}
    </div>
    {/if} {if isset($MUST_VALIDATE_ACCOUNT)}
    <div class="alert alert-danger">
        {$MUST_VALIDATE_ACCOUNT}
    </div>
    {/if}
    {if !isset($LOGGED_IN_USER)}
        {if isset($THEME_WB_T) && $THEME_WB_T|count_characters > 0}
            <div class="card mb-3">
                <div class="card-body welcome-card">
                    <div class="row">
                        <div class="col-md-3 col-inv">
                            <picture>
                                <source srcset="none"  media="(max-width: 767px)">
                                <source srcset="https://mc-heads.net/body/{$THEME_WB_S1}/120"  media="(min-width: 768px)">
                                <img src="https://mc-heads.net/body/{$THEME_WB_S1}/120" alt="Avatar" class="welcome-avatar">
                            </picture>
                            <picture>
                                <source srcset="none"  media="(max-width: 767px)">
                                <source srcset="https://mc-heads.net/body/{$THEME_WB_S2}/120"  media="(min-width: 768px)">
                                <img src="https://mc-heads.net/body/{$THEME_WB_S2}/120" alt="Avatar" class="welcome-avatar av-80px">
                            </picture>
                            <picture>
                                <source srcset="none"  media="(max-width: 767px)">
                                <source srcset="https://mc-heads.net/body/{$THEME_WB_S3}/120"  media="(min-width: 768px)">
                                <img src="https://mc-heads.net/body/{$THEME_WB_S3}/120" alt="Avatar" class="welcome-avatar av-160px av-inv">
                            </picture>
                        </div>
                        <div class="col-md-6 text-center">
                            <span class="welcome-title">{$THEME_WB_T}</span><br />
                            <span class="welcome-text">{$THEME_WB_D}</span><br />
                            <br />
                            <div class="row">
                                <div class="col-6">
                                    <a href="{$THEME_WB_1L}" class="btn btn-theme float-right">{$THEME_WB_1N}</a>
                                </div>
                                <div class="col-6">
                                    <a href="{$THEME_WB_2L}" class="btn btn-theme float-left">{$THEME_WB_2N}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-inv">
                            <picture>
                                <source srcset="none"  media="(max-width: 767px)">
                                <source srcset="https://mc-heads.net/body/{$THEME_WB_S4}/120"  media="(min-width: 768px)">
                                <img src="https://mc-heads.net/body/{$THEME_WB_S4}/120" alt="Avatar" class="welcome-avatar av-right">
                            </picture>
                            <picture>
                                <source srcset="none"  media="(max-width: 767px)">
                                <source srcset="https://mc-heads.net/body/{$THEME_WB_S5}/120"  media="(min-width: 768px)">
                                <img src="https://mc-heads.net/body/{$THEME_WB_S5}/120" alt="Avatar" class="welcome-avatar av-80px av-right">
                            </picture>
                            <picture>
                                <source srcset="none"  media="(max-width: 767px)">
                                <source srcset="https://mc-heads.net/body/{$THEME_WB_S6}/120"  media="(min-width: 768px)">
                                <img src="https://mc-heads.net/body/{$THEME_WB_S6}/120" alt="Avatar" class="welcome-avatar av-160px av-right av-inv">
                            </picture>
                        </div>
                    </div>
                </div>
            </div>
        {/if}
    {/if}

    {if !empty($ANNOUNCEMENTS)}
        {foreach from=$ANNOUNCEMENTS item=$ANNOUNCEMENT}
            <div class="alert{if $ANNOUNCEMENT->closable} alert-dismissible fade show{/if}" id="announcement-{$ANNOUNCEMENT->id}" style="background-color:{$ANNOUNCEMENT->background_colour}; color:{$ANNOUNCEMENT->text_colour}" role="alert">
                {if isset($ANNOUNCEMENT->icon)}
                    <i class="{$ANNOUNCEMENT->icon} float-left mr-3" style="font-size: 40px; line-height: 0.7;"></i>
                {/if}
                <b style="font-size: 17px">{$ANNOUNCEMENT->header}</b><br />
                {if $ANNOUNCEMENT->closable}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                {/if}
            </div>
        {/foreach}
    {/if}

    <div class="chatbox mb-3" id="chatbox-top"></div>
</div>