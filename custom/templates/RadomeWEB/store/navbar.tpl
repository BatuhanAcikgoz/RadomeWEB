        <div class="card">
          <div class="col-md-12" style="padding-bottom: 20px;padding-top: 20px;">
            


           {foreach from=$CATEGORIES item=category}
            {if isset($category.subcategories) && count($category.subcategories)}
              <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{$category.title}</button>
                <i class="dropdown icon"></i>
                <div class="menu">
                  {if !$category.only_subcategories}
                  <a class="{if $category.active}active {/if}dropdown-item" href="{$category.url}" style="float: inline-start;">{$category.title}</a>
                  {/if}
                  {foreach from=$category.subcategories item=subcategory}
                    <a class="{if $subcategory.active}active {/if}dropdown-item" href="{$subcategory.url}" style="float: inline-start;">{$subcategory.title}</a>
                  {/foreach}
                </div>
              </div>
            {else}
              <a class="{if $category.active}active {/if}" href="{$category.url}" style="float: inline-start; margin-left: 15px;">
               {$category.title}
              </a>
            {/if}
          {/foreach}

            {if count($SHOPPING_CART_PRODUCTS)}
              <a href="{$CHECKOUT_LINK}" class="btn btn-theme" style="float: inline-end">
                {$X_ITEMS_FOR_Y}
              </a>
            {else}
              <a class="btn btn-theme" style="float: inline-end">
                {$X_ITEMS_FOR_Y}
              </a>
            {/if}

          </div>
        </div>