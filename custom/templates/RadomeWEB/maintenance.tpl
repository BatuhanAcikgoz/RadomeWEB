{include file='header-top.tpl'}
        <meta name="robots" content="noindex">
        <style>
        body {
            background-color: #F0F0F0;
        }
        .elr-title {
            font-size: 60px;
            font-weight: bold;
        }
        .elr-text {
            font-size: 30px;
        }
        .container {
            margin-top: 100px; 
            text-align:center
        }
        </style>
    </head>
    <body>
        <div class="container-fluid h-100">
            <div class="container">
                <span class="elr-title">{$MAINTENANCE_TITLE}</span>
                <br />
                <span class="elr-text">{$MAINTENANCE_MESSAGE}</span><br /><br /> {if isset($LOGIN)}
                <div class="row">
                    <div class="col-6">
                        <a href="#" class="btn btn-theme btn-lg pull-right" onclick="window.location.reload()">{$RETRY}</a>
                    </div>
                    <div class="col-6">
                        <a href="{$LOGIN_LINK}" class="btn btn-theme btn-lg pull-left">{$LOGIN}</a>
                    </div>
                </div>
                {else}
                <center>
                    <a href="#" class="btn btn-theme btn-lg" onclick="window.location.reload()">{$RETRY}</a>
                </center>
                {/if}
                <br />{$ERROR}
            </div>
        </div>
	{include file='scripts.tpl'}
{if !isset($EXCLUDE_END_BODY)}
  </body>

  </html>
{/if}