<div class="card">
    <div class="card-header header-theme">{$LATEST_POSTS}</div>
    <div class="card-body">
        {foreach from=$LATEST_POSTS_ARRAY item=post name=latest_posts}
            <a class="white-link" href="{$post.last_reply_link}">{$post.topic_title}</a><br />
            <a href="{$post.last_reply_profile_link}" style="{$post.last_reply_style}" data-poload="{$USER_INFO_URL}{$post.last_reply_user_id}" data-html="true" data-placement="top">{$post.last_reply_username}</a><br />
            <span data-toggle="tooltip" data-trigger="hover" data-original-title="{$post.last_reply}">{$post.last_reply_rough}</span>
            {if not $smarty.foreach.latest_posts.last}<br /><br />{/if}
        {/foreach}
    </div>
</div>