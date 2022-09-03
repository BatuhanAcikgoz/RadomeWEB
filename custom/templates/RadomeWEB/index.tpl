{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container" style="min-height: calc(-175.133px + 100vh);">
    {if isset($THEME_SLIDER1_TITLE) && $THEME_SLIDER1_TITLE|count_characters > 0}
        <div class="carousel slide" style="padding-bottom: 1rem; overflow: hidden;" id="carousel" data-ride="carousel">
            <ol class="carousel-indicators">
                {if isset($THEME_SLIDER1_TITLE) && $THEME_SLIDER1_TITLE|count_characters > 0}
                    <li data-target="#carousel" data-slide-to="0" class="active"></li>
                    {/if} {if isset($THEME_SLIDER2_TITLE) && $THEME_SLIDER2_TITLE|count_characters > 0}
                    <li data-target="#carousel" data-slide-to="1"></li>
                    {/if} {if isset($THEME_SLIDER3_TITLE) && $THEME_SLIDER3_TITLE|count_characters > 0}
                    <li data-target="#carousel" data-slide-to="2"></li>
                    {/if} {if isset($THEME_SLIDER4_TITLE) && $THEME_SLIDER4_TITLE|count_characters > 0}
                    <li data-target="#carousel" data-slide-to="3"></li>
                    {/if} {if isset($THEME_SLIDER5_TITLE) && $THEME_SLIDER5_TITLE|count_characters > 0}
                    <li data-target="#carousel" data-slide-to="4"></li>
                {/if}
            </ol>
            <div class="carousel-inner">
                {if isset($THEME_SLIDER1_TITLE) && $THEME_SLIDER1_TITLE|count_characters > 0}
                    <div class="carousel-item active">
                        {if isset($THEME_SLIDER1_LINK) && $THEME_SLIDER1_LINK|count_characters > 0}
                            <a href="{$THEME_SLIDER1_LINK}" {if isset($THEME_SLIDER1_LINK_OPEN) && $THEME_SLIDER1_LINK_OPEN eq 'yes'}target="_blank"{/if}>
                            {/if}
                            <img src="{$THEME_SLIDER1_IMAGE}" class="d-block w-100" alt="Slide #1">
                            <div class="carousel-caption d-none d-md-block">
                                <h1 class="slider-title"><b>{$THEME_SLIDER1_TITLE}</b></h1>
                                {if isset($THEME_SLIDER1_DESCRIPTION) && $THEME_SLIDER1_DESCRIPTION|count_characters > 0}
                                    <p class="col-inv slider-desc">{$THEME_SLIDER1_DESCRIPTION}</p>
                                {/if}
                            </div>
                            {if isset($THEME_SLIDER1_LINK) && $THEME_SLIDER1_LINK|count_characters > 0}
                        </a> {/if}
                    </div>
                    {/if} {if isset($THEME_SLIDER2_TITLE) && $THEME_SLIDER2_TITLE|count_characters > 0}
                    <div class="carousel-item">
                        {if isset($THEME_SLIDER2_LINK) && $THEME_SLIDER2_LINK|count_characters > 0}
                            <a href="{$THEME_SLIDER2_LINK}" {if isset($THEME_SLIDER2_LINK_OPEN) && $THEME_SLIDER2_LINK_OPEN eq 'yes'}target="_blank"{/if}>
                            {/if}
                            <img src="{$THEME_SLIDER2_IMAGE}" class="d-block w-100" alt="Slide #2">
                            <div class="carousel-caption d-none d-md-block">
                                <h1 class="slider-title"><b>{$THEME_SLIDER2_TITLE}</b></h1>
                                {if isset($THEME_SLIDER2_DESCRIPTION) && $THEME_SLIDER2_DESCRIPTION|count_characters > 0}
                                    <p class="col-inv slider-desc">{$THEME_SLIDER2_DESCRIPTION}</p>
                                {/if}
                            </div>
                            {if isset($THEME_SLIDER2_LINK) && $THEME_SLIDER2_LINK|count_characters > 0}
                        </a> {/if}
                    </div>
                    {/if} {if isset($THEME_SLIDER3_TITLE) && $THEME_SLIDER3_TITLE|count_characters > 0}
                    <div class="carousel-item">
                        {if isset($THEME_SLIDER3_LINK) && $THEME_SLIDER3_LINK|count_characters > 0}
                            <a href="{$THEME_SLIDER3_LINK}" {if isset($THEME_SLIDER3_LINK_OPEN) && $THEME_SLIDER3_LINK_OPEN eq 'yes'}target="_blank"{/if}>
                            {/if}
                            <img src="{$THEME_SLIDER3_IMAGE}" class="d-block w-100" alt="Slide #3">
                            <div class="carousel-caption d-none d-md-block">
                                <h1 class="slider-title"><b>{$THEME_SLIDER3_TITLE}</b></h1>
                                {if isset($THEME_SLIDER3_DESCRIPTION) && $THEME_SLIDER3_DESCRIPTION|count_characters > 0}
                                    <p class="col-inv slider-desc">{$THEME_SLIDER3_DESCRIPTION}</p>
                                {/if}
                            </div>
                            {if isset($THEME_SLIDER3_LINK) && $THEME_SLIDER3_LINK|count_characters > 0}
                        </a> {/if}
                    </div>
                    {/if} {if isset($THEME_SLIDER4_TITLE) && $THEME_SLIDER4_TITLE|count_characters > 0}
                    <div class="carousel-item">
                        {if isset($THEME_SLIDER4_LINK) && $THEME_SLIDER4_LINK|count_characters > 0}
                            <a href="{$THEME_SLIDER4_LINK}" {if isset($THEME_SLIDER4_LINK_OPEN) && $THEME_SLIDER4_LINK_OPEN eq 'yes'}target="_blank"{/if}>
                            {/if}
                            <img src="{$THEME_SLIDER4_IMAGE}" class="d-block w-100" alt="Slide #4">
                            <div class="carousel-caption d-none d-md-block">
                                <h1 class="slider-title"><b>{$THEME_SLIDER4_TITLE}</b></h1>
                                {if isset($THEME_SLIDER4_DESCRIPTION) && $THEME_SLIDER4_DESCRIPTION|count_characters > 0}
                                    <p class="col-inv slider-desc">{$THEME_SLIDER4_DESCRIPTION}</p>
                                {/if}
                            </div>
                            {if isset($THEME_SLIDER4_LINK) && $THEME_SLIDER4_LINK|count_characters > 0}
                        </a> {/if}
                    </div>
                    {/if} {if isset($THEME_SLIDER5_TITLE) && $THEME_SLIDER5_TITLE|count_characters > 0}
                    <div class="carousel-item">
                        {if isset($THEME_SLIDER5_LINK) && $THEME_SLIDER5_LINK|count_characters > 0}
                            <a href="{$THEME_SLIDER5_LINK}" {if isset($THEME_SLIDER5_LINK_OPEN) && $THEME_SLIDER5_LINK_OPEN eq 'yes'}target="_blank"{/if}>
                            {/if}
                            <img src="{$THEME_SLIDER5_IMAGE}" class="d-block w-100" alt="Slide #5">
                            <div class="carousel-caption d-none d-md-block">
                                <h1 class="slider-title"><b>{$THEME_SLIDER5_TITLE}</b></h1>
                                {if isset($THEME_SLIDER5_DESCRIPTION) && $THEME_SLIDER5_DESCRIPTION|count_characters > 0}
                                    <p class="col-inv slider-desc">{$THEME_SLIDER5_DESCRIPTION}</p>
                                {/if}
                            </div>
                            {if isset($THEME_SLIDER5_LINK) && $THEME_SLIDER5_LINK|count_characters > 0}
                        </a> {/if}
                    </div>
                {/if}
            </div>
        </div>
    {/if}

    {if isset($HOME_SESSION_FLASH)}
        <div class="alert alert-info">
            {$HOME_SESSION_FLASH}
        </div>
        {/if} {if isset($HOME_SESSION_ERROR_FLASH)}
        <div class="alert alert-danger">
            {$HOME_SESSION_ERROR_FLASH}
        </div>
    {/if}
    <div class="row">

        {if count($WIDGETS_LEFT)}
            <div class="col-md-3">
                {foreach from=$WIDGETS_LEFT item=widget}
                    {$widget}
                {/foreach}
            </div>
        {/if}

        <div class="{if count($WIDGETS_LEFT) && count($WIDGETS_RIGHT)}col-md-6{elseif count($WIDGETS_LEFT) || count($WIDGETS_RIGHT)}col-md-9{else}col-md-12{/if}">

            {if $NEWS != null && count($NEWS)}
                {foreach from=$NEWS item=item}
                    <div class="card">
                        <div class="card-header header-theme">
                            <div class="news-flex">
                                <div class="news-avatar">
                                    <a href="{$item.author_url}" data-poload="{$USER_INFO_URL}{$item.author_id}"
                                        data-html="true" data-placement="top">
                                        <img src="{$item.author_avatar}" class="avatar-img" style="width:50px"
                                            alt="{$item.author_name}">
                                    </a>
                                </div>
                                <div class="news-info">
                                    <span class="news-title"><a class="news-title-text"
                                            href="{$item.url}">{$item.title}</a>{if $item.label} <span
                                            class="pull-right">{$item.label}</span>{/if}</span><br />
                                    <span class="news-info"><i class="fas fa-pen-alt"></i>&nbsp;<a
                                            data-poload="{$USER_INFO_URL}{$item.author_id}" data-html="true"
                                            data-placement="top" href="{$item.author_url}"
                                            style="{$item.author_style}"><b>{$item.author_name}</b></a>&nbsp;&bull;&nbsp;
                                        <span data-toggle="tooltip" title="{$item.date}"><i
                                                class="fas fa-user-clock"></i>&nbsp;<b>{$item.time_ago}&nbsp;&bull;&nbsp;</b></span><i
                                            class="fas fa-eye"></i>&nbsp;<b>{$item.views}</b></span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body forum_post">
                            {$item.content}
                            <a href="{$item.url}" class="btn btn-theme btn-older-news float-right">{$READ_FULL_POST} <i
                                    class="fas fa-share"></i></a>
                        </div>
                    </div>
                {/foreach}

            {/if}
        </div>

        {if count($WIDGETS_RIGHT)}
            <div class="col-md-3">
                {foreach from=$WIDGETS_RIGHT item=widget}
                    {$widget}
                {/foreach}
            </div>
        {/if}

    </div>
</div>
<div>
{include file='footer.tpl'}
</div>
