{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container">
    <div class="card">
        <div class="card-header text-white header-theme">{$VIEWING_ALL_RELEASES}<span class="float-right"><a href="{$BACK_LINK}" class="btn btn-theme btn-sm">{$BACK}</a></span></div>
        <div class="card-body">
            {foreach from=$RELEASES item=release}
            <a class="white-link" href="{$release.url}">{$release.name}</a>
	    <span class="pull-right" data-toggle="tooltip" data-trigger="hover" data-original-title="{$release.date_full}">{$release.date}</span>
            <hr />
            <div class="forum-post">
                {$release.description}
            </div>
            <br /> {$release.downloads} &bull;
            <div class="star-rating view inline-form">
                <span class="far fa-star" data-rating="1"></span>
                <span class="far fa-star" data-rating="2"></span>
                <span class="far fa-star" data-rating="3"></span>
                <span class="far fa-star" data-rating="4"></span>
                <span class="far fa-star" data-rating="5"></span>
                <input type="hidden" name="rating" class="rating-value" value="{$release.rating}">
            </div>
            <hr /> {/foreach} {$PAGINATION}
        </div>
    </div>
</div>
{include file='footer.tpl'}