{$PF_LATEST_POSTS_TITLE}<hr />
{if isset($NO_POSTS)}
    <p>{$NO_POSTS}</p>
{else} {foreach from=$PF_LATEST_POSTS item=post}
    <a class="white-link" href="{$post.link}">{$post.title}</a>
    <span class="float-right">
	<span rel="tooltip" title="{$post.date_full}">{$post.date_friendly}</span>
    </span><br />
{/foreach} {/if}
