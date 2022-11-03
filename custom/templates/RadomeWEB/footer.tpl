    </div>
  </div>

{if isset($GLOBAL_WARNING_TITLE)}
<div class="modal fade show-punishment" data-keyboard="false" data-backdrop="static" id="acknowledgeModal" tabindex="-1" role="dialog" aria-labelledby="acknowledgeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="acknowledgeModalLabel">{$GLOBAL_WARNING_TITLE}</h4>
            </div>
            <div class="modal-body">
                {$GLOBAL_WARNING_REASON}
            </div>
            <div class="modal-footer">
                <a href="{$GLOBAL_WARNING_ACKNOWLEDGE_LINK}" class="btn btn-warning">{$GLOBAL_WARNING_ACKNOWLEDGE}</a>
            </div>
        </div>
    </div>
</div>
{/if}
<br />
</footer>
<footer class="footer-theme">
    <div class="container">
        <div class="row">
            <div class="col-md-4 footer-col footer-about">
                <span class="footer-text-header">{$ABOUT_TITLE}</span>
                <p>{$THEME_ABOUT}</p>
                <br />
            </div>
            <div class="col-md-1 footer-col"></div>
            <div class="col-md-3 footer-col">
                <span class="footer-text-header">{$FOOTER_LINKS}</span><br />
                <a class="footer-link" href="{$TERMS_LINK}">{$TERMS_TEXT}</a><br />
                <a class="footer-link" href="{$PRIVACY_LINK}">{$PRIVACY_TEXT}</a><br />
                {foreach from=$FOOTER_NAVIGATION key=name item=item} {if isset($item.items)}
                <a class="dropdown-toggle footer-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">{$item.icon} {$item.title}</a>
                <div class="dropdown-menu">
                    {foreach from=$item.items item=dropdown}
                    <a href="{$dropdown.link}" target="{$dropdown.target}">{$dropdown.icon} {$dropdown.title}</a> {/foreach}
                </div>
                {else}
                <a class="footer-link" href="{$item.link}" target="{$item.target}">{$item.icon} {$item.title}</a><br /> {/if} {/foreach}
                <br />
            </div>
            <div class="col-md-4 footer-col">
                <span class="footer-text-header">{$THEME_OTHER_T}</span>
                <p>{$THEME_OTHER_D}</p>
                <a href="{$THEME_OTHER_BL}" class="btn btn-block other-btn">{$THEME_OTHER_BT}</a>
                <br />
            </div>
        </div>
    </div>
</footer>
<footer class="footer-text-bar pull-left">
<div class="footer-m-alt">
    <div class="container">
      <div class="altortala">
        <div class="social-medias">
          <a class="social-media-div facebook" target="_blank" href="{$FACEBOOK_URL_VALUE}">
            <i class="fab fa-facebook"></i>
          </a>
          <a class="social-media-div twitter" target="_blank" href="{$TWITTER_URL_VALUE}">
            <i class="fab fa-twitter"></i>
          </a>
          <a class="social-media-div instagram" target="_blank" href="{$INSTAGRAM_URL_VALUE}">
            <i class="fab fa-instagram"></i>
          </a>
          <a class="social-media-div youtube" target="_blank" href="{$YOUTUBE_URL_VALUE}">
            <i class="fab fa-youtube"></i>
          </a>
          <a class="social-media-div discord" target="_blank" href="{$DISCORD_URL_VALUE}">
            <i class="fab fa-discord"></i>
          </a>
          <a class="social-media-div email" target="_blank" href="mailto:info@verira.com">
            <i class="fas fa-envelope"></i>
          </a>
        </div>
        <div class="copyright-site">
          <copyright>
            <a target="_blank" rel="external">2022 Copyright &copy{$SITE_NAME}. Tüm hakları saklıdır.</a>
          </copyright>
        </div>
        <div class="copyright-radomeweb">
          <copyright data-toggle="tooltip" data-placement="top" title="Yazılım: Reeignn">
            <a class="font-weight-bold text-white" href="https://verira.com" target="_blank" rel="external">RadomeWEB</a>
          </copyright>
        </div>
      </div>
    </div>
  </div>
</footer>
{foreach from=$TEMPLATE_JS item=script}
	{$script}
{/foreach}

{if !isset($EXCLUDE_END_BODY)}
  </body>
  </html>
{/if}