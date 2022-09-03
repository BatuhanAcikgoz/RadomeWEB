{include file='header.tpl'} {include file='navbar.tpl'}
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header header-theme">{$CATEGORY_NAME}
                    <span class="float-right">
                        <a href="{$BACK_LINK}" class="btn btn-theme btn-sm">{$BACK}</a>
                        {if isset($NEW_RESOURCE)}
                            <a href="{$NEW_RESOURCE_LINK}" class="btn btn-theme btn-sm">{$NEW_RESOURCE}</a>
                        {/if}
                        <div class="dropdown float-right">
                            <button class="btn btn-theme btn-sm float-right dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" style="margin-top: 2px; margin-left: 5px;">
                                {$SORT_BY} {$SORT_BY_VALUE}
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                {foreach from=$SORT_TYPES item=item}
                                    <a rel="noopener nofollow" class="dropdown-item" href="{$item.link}">{$item.sort}</a>
                                {/foreach}
                            </div>
                        </div>
                    </span>
                </div>
                <div class="card-body">
                    {if $LATEST_RESOURCES}
                        <table class="table table-striped">
                            <colgroup>
                                <col span="1" style="width: 50%;">
                                <col span="1" style="width: 20%;" class="col-inv">
                                <col span="1" style="width: 30%;" class="col-inv">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>{$RESOURCE}</th>
                                    <th class="col-inv">{$STATS}</th>
                                    <th class="col-inv">{$AUTHOR}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$LATEST_RESOURCES item=resource}
                                    <tr>
                                        <td>
                                            <a class="white-link" href="{$resource.link}">{$resource.name}</a>
                                            <small>{$resource.version}</small>{if isset($resource.price)}<span
                                                    class="badge badge-dark float-right"
                                                    style="background-color: rgb(0,0,0,0.2)">{$resource.price}
                                                {$CURRENCY}</span>{/if}<br /> {$resource.description}
                                            <br />
                                            <small>{$resource.category}, <span data-toggle="tooltip" data-trigger="hover"
                                                    data-original-title="{$resource.updated_full}">{$resource.updated}</span></small>
                                        </td>
                                        <td>
                                            <div class="star-rating view">
                                                <span class="far fa-star" data-rating="1"></span>
                                                <span class="far fa-star" data-rating="2"></span>
                                                <span class="far fa-star" data-rating="3"></span>
                                                <span class="far fa-star" data-rating="4"></span>
                                                <span class="far fa-star" data-rating="5"></span>
                                                <input type="hidden" name="rating" class="rating-value"
                                                    value="{$resource.rating}">
                                            </div>
                                            {$resource.views}<br /> {$resource.downloads}
                                        </td>
                                        <td>
                                            <a href="{$resource.author_profile}"><img class="avatar-img"
                                                    style="max-height:20px; max-width:20px;" src="{$resource.author_avatar}"
                                                    alt="{$resource.author}" /></a> <a style="{$resource.author_style}"
                                                href="{$resource.author_profile}">{$resource.author}</a>
                                        </td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    {$PAGINATION} {else} {$NO_RESOURCES} 
                    {/if}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            {include file='resources/categories.tpl'}

            {if count($WIDGETS_RIGHT)}
                {foreach from=$WIDGETS_RIGHT item=widget}
                    {$widget}
                {/foreach}
            {/if}
        </div>
    </div>
</div>
{include file='footer.tpl'}