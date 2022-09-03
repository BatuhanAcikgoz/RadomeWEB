{include file='header-top.tpl'}
        <style>
        @media (min-width: 768px) {
		{if $THEME_L_BG_WEBP !== ""}
			.webp body {
            	background: url('{$THEME_L_BG_WEBP}') no-repeat center;
        	}
        	.no-webp body {
          		background: url('{$THEME_L_BG}') no-repeat center;
        	}
		{else}
        	body {
        	    background: url('{$THEME_L_BG}') no-repeat center;
        	}
		{/if}
		.logo {
			margin-top: {$THEME_ELR_MARGIN} !important;
			height: {$THEME_ELR_LOGO} !important;
		}
        }
		html, body, .container-fluid, .row-h, .col-md-4 {
			height: 100vh;
		}
		.elr-title {
			font-size: 60px;
			font-weight: bold;
		}
        </style>
    </head>
    <body>
        <div class="container-fluid h-100">
            <div class="row row-h">
                <div class="col-md-8 col-inv">
                    <picture>
                        <source srcset="none" media="(max-width: 767px)">
                        {if isset($THEME_LOGO_WEBP) && $THEME_LOGO_WEBP|count_characters > 4}<source srcset="{$THEME_LOGO_WEBP}" type="image/webp">{/if}
                        <source srcset="{$THEME_LOGO}"> 
                        <img class="logo{if isset($THEME_AL) && $THEME_AL|count_characters > 2} animated-logo{/if}" alt="logo" src='{$THEME_LOGO}'>
                    </picture>
                </div>
                <div class="col-md-4 elr-col-right">
                    <div class="container elr-container">
                        <span class="elr-title">{$SIGN_IN}</span>
			            <br /><br />
			            <form role="form" action="" method="post">
                        {if count($ERROR)}
                        <div class="alert alert-danger" role="alert">
                            {foreach from=$ERROR item=error name=err} {$error} {if not $smarty.foreach.err.last}<br />{/if} {/foreach}
                        </div>
                        {/if}
                        {if isset($EMAIL)}
                        <div class="form-group">
                            <input type="email" class="form-control form-control-lg" style="width: 100%" name="email" id="email" value="{$USERNAME_INPUT}" placeholder="{$EMAIL}" tabindex="1">
                        </div>
                        {else}
                        <div class="form-group">
                            <input type="text" class="form-control form-control-lg" style="width:100%;" name="username" id="username" value="{$USERNAME_INPUT}" placeholder="{$USERNAME}" tabindex="1">
                        </div>
                        {/if}
                        <div class="form-group">
                            <input type="password" class="form-control form-control-lg" style="width: 100%;" name="password" id="password" autocomplete="off" placeholder="{$PASSWORD}" tabindex="2">
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <span class="button-checkbox">
                                        <button style="max-width: 100%" type="button" class="btn float-left" data-color="info" tabindex="3"> {$REMEMBER_ME}</button>
                                        <input type="checkbox" name="remember" id="remember" style="display:none;" value="1">
                                    </span>
                                </div>
                                <div class="col-6">
                                    <a class="btn btn-secondary float-right" style="max-width: 100%" href="{$FORGOT_PASSWORD_URL}">{$FORGOT_PASSWORD}</a>
                                </div>
                            </div>
                        </div>

                        {if $CAPTCHA}
                        <div class="form-group" style="width: 100%; justify-content: center; display: flex;">
                            {$CAPTCHA}
                        </div>
                        {/if}
                        <input type="hidden" name="token" value="{$FORM_TOKEN}">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-theme btn-block" tabindex="5">{$SIGN_IN}</button>
                                </div>
                                <div class="col-6">
                                    <a class="btn btn-theme btn-block" href="{$REGISTER_URL}">{$REGISTER}</a>
                                </div>
                            </div>
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