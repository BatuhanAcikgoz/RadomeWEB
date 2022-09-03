        <span class="right floated">
          <div class="col-md-12">
            
            {if isset($STORE_PLAYER)}
              <div class="btn btn-theme">
              <form class="ui form" action="" method="post">
                <input type="hidden" name="token" value="{$TOKEN}">
{$STORE_PLAYER}                 <input type="hidden" name="type" value="store_logout">
                <input type="submit" class="" value="{$LOGOUT}">
              </form>
              </div>
            {/if}
            


           {foreach from=$CATEGORIES item=category}
            {if isset($category.subcategories) && count($category.subcategories)}
              <div class="dropdown-item">
                <span class="text">{$category.title}</span>
                <i class="dropdown icon"></i>
                <div class="menu">
                  {if !$category.only_subcategories}
                  <a class="{if $category.active}active {/if}dropdown-item" href="{$category.url}">{$category.title}</a>
                  {/if}
                  {foreach from=$category.subcategories item=subcategory}
                    <a class="{if $subcategory.active}active {/if}dropdown-item" href="{$subcategory.url}">{$subcategory.title}</a>
                  {/foreach}
                </div>
              </div>
            {else}
              <a class="{if $category.active}active {/if}btn btn-theme" href="{$category.url}">
               {$category.title}
              </a>
            {/if}
          {/foreach}

            {if count($SHOPPING_CART_PRODUCTS)}
              <a href="{$CHECKOUT_LINK}" class="btn btn-theme" style="float: right">
                {$X_ITEMS_FOR_Y}
              </a>
            {else}
              <a class="btn btn-theme" style="float: right">
                {$X_ITEMS_FOR_Y}
              </a>
            {/if}

          </div>
        </span>