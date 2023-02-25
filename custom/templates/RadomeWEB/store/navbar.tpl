          <div class="col-md-12" style="padding-bottom: 30px;padding-top: 15px;">
            


           {foreach from=$CATEGORIES item=category}
            {if isset($category.subcategories) && count($category.subcategories)}
              <div class="dropdown-item">
                <span class="text">{$category.title}</span>
                <i class="dropdown icon"></i>
                <div class="menu">
                  {if !$category.only_subcategories}
                  <a class="{if $category.active}active {/if}dropdown-item" href="{$category.url}" style="float: inline-end;">{$category.title}</a>
                  {/if}
                  {foreach from=$category.subcategories item=subcategory}
                    <a class="{if $subcategory.active}active {/if}dropdown-item" href="{$subcategory.url}" style="float: inline-end;">{$subcategory.title}</a>
                  {/foreach}
                </div>
              </div>
            {else}
              <a class="{if $category.active}active {/if}btn btn-theme" href="{$category.url}" style="float: inline-end; margin-left: 15px;">
               {$category.title}
              </a>
            {/if}
          {/foreach}

            {if count($SHOPPING_CART_PRODUCTS)}
              <a href="{$CHECKOUT_LINK}" class="btn btn-theme" style="float: inline-start">
                {$X_ITEMS_FOR_Y}
              </a>
            {else}
              <a class="btn btn-theme" style="float: inline-start">
                {$X_ITEMS_FOR_Y}
              </a>
            {/if}

          </div>