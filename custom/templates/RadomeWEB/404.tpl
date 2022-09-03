{include file='header-top.tpl'}
        <meta name="robots" content="noindex">
        <style>
        @media (min-width: 768px) {
		{if $THEME_E_BG_WEBP !== ""}
			.webp body {
            	background: url('{$THEME_E_BG_WEBP}') no-repeat center;
        	}
        	.no-webp body {
          		background: url('{$THEME_E_BG}') no-repeat center;
        	}
		{else}
        	body {
        	    background: url('{$THEME_E_BG}') no-repeat center;
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
			font-size: 100px;
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
                <div class="col-md-4 elr-col-right">
                    <div class="container elr-container">
                        <span class="elr-title">404</span>
                        <br />
                        <span class="elr-text">{$404_TITLE} {$CONTENT}</span>
                        <br /><br />
                        <div class="row">
                            <div class="col-6">
                                <button class="btn btn-theme btn-lg pull-right" onclick="javascript:history.go(-1)">{$BACK}</button>
                            </div>
                            <div class="col-6">
                                <a href="{$SITE_HOME}" class="btn btn-theme btn-lg pull-left">{$HOME}</a>
                            </div>
                        </div>
                        <br />{$ERROR}
                    </div>
                </div>
            </div>
        </div>
	{include file='scripts.tpl'}
{if !isset($EXCLUDE_END_BODY)}
  </body>

  </html>
{/if}