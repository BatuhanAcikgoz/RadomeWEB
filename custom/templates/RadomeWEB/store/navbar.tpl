<div class="card" style="margin-top: 20px;">
  <div class="col-md-12" style="padding-bottom: 10px;padding-top: 10px;">
    <div class="btn" style="float: inline-end">
      {if count($SHOPPING_CART_PRODUCTS)}
        <a href="{$CHECKOUT_LINK}" class="btn btn-primary">
          {$X_ITEMS_FOR_Y}
        </a>
    </div>
    {else}
      <a class="btn btn-primary ">
        {$X_ITEMS_FOR_Y}
      </a>
    {/if}
  </div>
</div>