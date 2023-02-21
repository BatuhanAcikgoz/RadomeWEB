{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">
  <div class="row">

    {if count($WIDGETS_LEFT)}
      <div class="col-md-3">
        {foreach from=$WIDGETS_LEFT item=widget}
          {$widget}
        {/foreach}
      </div>
    {/if}

    <div
      class="{if count($WIDGETS_LEFT) && count($WIDGETS_RIGHT)}col-md-6{elseif count($WIDGETS_LEFT) || count($WIDGETS_RIGHT)}col-md-9{else}col-md-12{/if}">
      <div class="card-header header-theme">
        <h2 style="display:inline;">{$STORE} &raquo; {$CHECKOUT}</h2>
      </div>
      <div class="card card-body">

        {include file='store/navbar.tpl'}

        </br>

        {if isset($SUCCESS)}
          <div class="alert alert-success" style="margin-top: 30px;">
            {$SUCCESS}
          </div>
        {/if}

        {if isset($ERRORS)}
          <div class="alert alert-danger" style="margin-top: 30px;">
            {foreach from=$ERRORS item=error}
              {$error}<br />
            {/foreach}
          </div>
        {/if}

        <div class="card-title">
          <h3 style="margin-top: 30px;">{$SHOPPING_CART}</h3>
        </div>
        <table class="table table-hover">
          <thead>
            <tr>
              <th>{$NAME}</th>
              <th>{$OPTIONS}</th>
              <th>{$QUANTITY}</th>
              <th>{$PRICE}</th>
              <th>{$REMOVE_PRODUCT}</th>
            </tr>
          </thead>
          <tbody>
            {foreach from=$SHOPPING_CART_LIST item=item}
              <tr>
                <td>{$item.name}</td>
                <td>{if count($item.fields)}
                    {foreach from=$item.fields item=field name=fields}<strong>{$field.description}</strong>:
                      {$field.value}{if not $smarty.foreach.fields.last}</br>{/if}{/foreach} {/if}</td>
                    <td>{$item.quantity}</td>
                    <td>{if $item.sale_active}<span
                        style="color: #dc3545;text-decoration:line-through;">{$item.price_format}</span>{/if}
                      {$item.real_price_format}</td>
                    <td><a href="{$item.remove_link}" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></a></td>
                  </tr>
                {/foreach}
              </tbody>
            </table>

            <table class="table">
              <tbody>
                {if $TOTAL_DISCOUNT_VALUE > 0}
                  <tr>
                    <td>{$PRICE_TO_PAY}</td>
                    <td>{$TOTAL_REAL_PRICE_FORMAT_VALUE}</td>
                  </tr>
                  <tr>
                    <td>{$TOTAL_DISCOUNT}</td>
                    <td>{$TOTAL_DISCOUNT_FORMAT_VALUE}</td>
                  </tr>
                {/if}
                <tr>
                  <td>{$TOTAL_PRICE}</td>
                  <td>{$TOTAL_PRICE_FORMAT_VALUE}</td>
                </tr>
              </tbody>
            </table>

            <div class="card-title">
              <h3>{$REDEEM_COUPON}</h3>
            </div>
            <hr>
            <form action="{$REDEEM_COUPON_URL}" method="post" id="coupon">
              <div class="form-group">
                <div class="form-inline">
                  <input class="form-control input-sm" type="text" name="coupon" id="coupon" value="{$REDEEM_COUPON_VALUE}"
                    placeholder="{$REDEEM_COUPON_HERE}" />
                  <input type="hidden" name="token" value="{$TOKEN}">
                  <button class="btn btn-success" style="margin-left: 10px;">{$REDEEM} &raquo;</button>
                </div>
              </div>
            </form>

            <div class="card-title">
              <h3>{$PAYMENT_METHOD}</h3>
            </div>
            <hr>
            <form action="" method="post" id="forms">
              {foreach from=$PAYMENT_METHODS item=gateway}
                <div class="form-group">
                  <div class="form-check">
                    <input type="radio" name="payment_method" value="{$gateway.name}" required>
                    <label>{$gateway.displayname}</label>
                  </div>
                </div>
              {/foreach}

              <div class="card-title">
                <h3>{$PURCHASE}</h3>
              </div>
              <hr>
              <div class="form-group">
                <div class="form-check" style="display:inline;">
                  <input type="hidden" name="token" value="{$TOKEN}">
                  <input type="checkbox" name="t_and_c" value="1" required> <label>{$AGREE_T_AND_C_PURCHASE}<button class="btn btn-success" style="margin-left: 10px;">{$PURCHASE} &raquo;</button></label>
                </div>
              </div>
              </br>
            </form>

          </div>
        </div>

        {if count($WIDGETS_RIGHT)}
          <div class="col-md-3">
            {if isset($LOGGED_IN_USER) && isset($SHOW_CREDITS_AMOUNT)}
              <div class="card" id="widget-store-account">
                <div class="card-body">
                  <h4 class="card-title">{$ACCOUNT}</h4>
                  <div class="description">
                    <ul class="list-group">
                      <li class="list-group-item">
                        <span class="text">{$CREDITS}</span>
                        <div class="float-right"><b>{$CREDITS_FORMAT_VALUE}</b></div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            {/if}

            {foreach from=$WIDGETS_RIGHT item=widget}
              {$widget}
            {/foreach}
          </div>
        {/if}

      </div>
    </div>

    {include file='footer.tpl'}