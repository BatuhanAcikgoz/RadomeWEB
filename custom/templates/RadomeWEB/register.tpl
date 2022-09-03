{include file='header-top.tpl'}
        <style>
        @media (min-width: 768px) {
		{if $THEME_E_BG_WEBP !== ""}
			.webp body {
            	background: url('{$THEME_R_BG_WEBP}') no-repeat center;
        	}
        	.no-webp body {
          		background: url('{$THEME_R_BG}') no-repeat center;
        	}
		{else}
        	body {
        	    background: url('{$THEME_R_BG}') no-repeat center;
        	}
		{/if}
		.logo {
			margin-top: {$THEME_ELR_MARGIN} !important;
            height: {$THEME_ELR_LOGO} !important;
		}
        }
		html, body, .container-fluid, .row-h, .elr-col-right {
			height: 100vh;
		}
		.elr-title {
			font-size: 40px;
			font-weight: bold;
		}
        </style>
    </head>
    <body>
        <div class="container-fluid h-100">
            <div class="row row-h">
                <div class="col-md-8 col-inv">
                    <picture>
                        <source srcset="none"  media="(max-width: 767px)">
                        {if isset($THEME_LOGO_WEBP) && $THEME_LOGO_WEBP|count_characters > 4}<source srcset="{$THEME_LOGO_WEBP}" type="image/webp">{/if}
                        <source srcset="{$THEME_LOGO}"> 
                        <img class="logo{if isset($THEME_AL) && $THEME_AL|count_characters > 2} animated-logo{/if}" alt="logo" src='{$THEME_LOGO}'>
                    </picture>
                </div>
                <div class="col-md-4 elr-col-right" style="overflow-y: scroll">
                    <div class="container elr-container">
                        <span class="elr-title">{$CREATE_AN_ACCOUNT}</span>
			            <br /><br />
			            <form action="" method="post">
                        {if isset($REGISTRATION_ERROR)}
                        <div class="alert alert-danger">
                            {foreach from=$REGISTRATION_ERROR item=error} {$error}
                            <br /> {/foreach}
                        </div>
                        {/if} {if isset($NICKNAMES)}
                        <div class="form-group">
                            <input type="text" name="{if isset($MINECRAFT)}nickname{else}username{/if}" id="username" class="form-control form-control-lg" placeholder="{$NICKNAME}" tabindex="1" value="{if isset($MINECRAFT)}{$NICKNAME_VALUE}{else}{$USERNAME_VALUE}{/if}">
                        </div>
                        {if isset($MINECRAFT)}
                        <div class="form-group">
                            <input type="text" name="username" id="mcname" autocomplete="off" class="form-control form-control-lg" placeholder="{$MINECRAFT_USERNAME}" tabindex="2" value="{$USERNAME_VALUE}">
                        </div>
                        {/if} {else} {if isset($MINECRAFT)}
                        <div class="form-group">
                            <input type="text" name="username" id="mcname" autocomplete="off" class="form-control form-control-lg" placeholder="{$MINECRAFT_USERNAME}" tabindex="1" value="{$USERNAME_VALUE}">
                        </div>
                        {else}
                        <div class="form-group">
                            <input type="text" name="username" id="mcname" autocomplete="off" class="form-control form-control-lg" placeholder="{$NICKNAME}" tabindex="2" value="{$USERNAME_VALUE}">
                        </div>
                        {/if} {/if}
                        <div class="form-group">
                            <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="{$EMAIL}" tabindex="3" value="{$EMAIL_VALUE}">
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="{$PASSWORD}" tabindex="4">
                        </div>
                        <div class="form-group">
                            <input type="password" name="password_again" id="password_again" class="form-control form-control-lg" placeholder="{$CONFIRM_PASSWORD}" tabindex="5">
                        </div>

                        {if count($CUSTOM_FIELDS)}
                            {foreach $CUSTOM_FIELDS as $field}
                                <div class="form-group">
                                <label class="float-left">{$field.name}</label>
                                    {if $field.type eq 1}
                                    <input class="form-control form-control-lg" type="text" name="{$field.name}" id="{$field.name}" value="{$field.value}" placeholder="{$field.name}" tabindex="5">
                                    {elseif $field.type eq 2}
                                    <textarea class="form-control form-control-lg" name="{$field.name}" id="{$field.name}" placeholder="{$field.description}" tabindex="5"></textarea>
                                    {elseif $field.type eq 3}
                                    <input class="form-control form-control-lg" type="date" name="{$field.name}" id="{$field.name}" value="{$field.value}" tabindex="5">
                                    {/if}
                                </div>
                            {/foreach}
                        {/if}

                        <div class="row">
                            <div class="col-4 col-md-5 col-lg-4">
                                <span class="button-checkbox">
				                    <button type="button" class="btn float-left" data-color="info" tabindex="7"> {$I_AGREE}</button>
				                    <input type="checkbox" name="t_and_c" id="t_and_c" style="display:none;" value="1">
				                </span>
                            </div>
                            <div class="col-8 col-md-7 col-lg-8">
                                <span class="agree-terms">{$AGREE_TO_TERMS}</span>
                            </div>
                        </div>
                        <br /> {if $CAPTCHA}
                        <div class="form-group" style="width: 100%; justify-content: center; display: flex;">
                            {$CAPTCHA}
                        </div>
                        {/if}
                        <div class="row mb-4">
                            <input type="hidden" name="token" value="{$TOKEN}">
                            <input id="timezone" type="hidden" name="timezone" value=''>
                            <div class="col-6"><button type="submit" class="btn btn-theme btn-block">{$REGISTER}</button></div>
                            <div class="col-6"><a href="{$LOGIN_URL}" class="btn btn-theme btn-block">{$LOG_IN}</a></div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
	{include file='scripts.tpl'}
{if !isset($EXCLUDE_END_BODY)}
  </body>

  </html>
{/if}