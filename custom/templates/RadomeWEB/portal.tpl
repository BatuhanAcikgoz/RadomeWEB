{include file='header-top.tpl'}
        <link rel="stylesheet" type="text/css" href="/custom/templates/Skyfall/css/portalv1.css">
        <style>
        body {
            background: url('{$THEME_PORTAL_BG}') no-repeat center;
        }
	    .logo {
     		height: {$PORTAL_LOGO_SIZE};
	    }
	    .p-icons {
     		margin-top: {$PORTAL_IMAGE_MARGIN};
	    }
	    .logo {
     		margin-top: {$PORTAL_LOGO_MARGIN};
	    }
	    @media only screen and (max-width: 767px) {
    		.logo {
         	    height: {$PORTAL_LOGO_SIZE_M};
    		}
		    .p-icons {
         	    margin-top: {$PORTAL_IMAGE_MARGIN_M};
    		}
	    	.logo {
     		    margin-top: {$PORTAL_LOGO_MARGIN_M};
	    	}
	    }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4 offset-md-4">
					<picture>
						{if isset($THEME_LOGO_WEBP) && $THEME_LOGO_WEBP|count_characters > 4}<source srcset="{$THEME_LOGO_WEBP}" type="image/webp">{/if}
						<source srcset="{$THEME_LOGO}"> 
						<img class="logo portal-logo{if isset($THEME_AL) && $THEME_AL|count_characters > 2} animated-logo{/if}" alt="logo" src='{$THEME_LOGO}'>
					</picture>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="p-icons row">
                <div class="col-12 col-md-4">
                    <a href="{$PORTAL_1_LINK}">
                        <center>
                            <img class="p-image" alt="Portal Image 1" src='{$PORTAL_1_ICON}'>
                        </center>
                    </a>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{$PORTAL_2_LINK}">
                        <center>
                            <img class="p-image" alt="Portal Image 2" src='{$PORTAL_2_ICON}'>
                        </center>
                    </a>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{$PORTAL_3_LINK}">
                        <center>
                            <img class="p-image" alt="Portal Image 3" src='{$PORTAL_3_ICON}'>
                        </center>
                    </a>
                </div>
            </div>
        </div>
	{include file='scripts.tpl'}
{if !isset($EXCLUDE_END_BODY)}
  </body>

  </html>
{/if}