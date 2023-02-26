{include file='header.tpl'}
{include file='navbar.tpl'}

<div class="container">
  <div class="card-header header-theme">
  <h2 class="card-body" style="display:inline;"><b>{$STORE} &raquo; {$CHECKOUT}</b></h2>
  </div>
  {include file='store/navbar.tpl'}
  <div class="row">
    <div class="col-md-9">
      <div class="card card-body">
        </br>
        {if isset($SUCCESS)}
          <div class="alert alert-success">
            {$SUCCESS}
          </div>
        {/if}
        {if isset($ERRORS)}
          <div class="alert alert-danger">
            {foreach from=$ERRORS item=error}
              {$error}<br />
            {/foreach}
          </div>
        {/if}
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
            <hr style="margin: 0px;">
      </div>
    </div>

    <div class="col-md-3">
      <div class="card card-body">
            {if $TOTAL_DISCOUNT_VALUE > 0}
              <h4>{$PRICE_TO_PAY} {$TOTAL_PRICE_FORMAT_VALUE}{$TOTAL_DISCOUNT}{$TOTAL_DISCOUNT_FORMAT_VALUE}</h4>
            {/if}
              <h4>{$PRICE_TO_PAY} {$TOTAL_REAL_PRICE_FORMAT_VALUE}</h4>
        <hr>
        <div class="card-title">
          <h4>{$REDEEM_COUPON}</h4>
        </div>
        <form action="{$REDEEM_COUPON_URL}" method="post" id="coupon">
          <div class="form-group">
            <div class="form-inline">
              <input class="form-control input-sm" type="text" name="coupon" id="coupon" value="{$REDEEM_COUPON_VALUE}"
                placeholder="{$REDEEM_COUPON_HERE}" />
              <input type="hidden" name="token" value="{$TOKEN}">
              <button class="btn btn-success">{$REDEEM} &raquo;</button>
            </div>
          </div>
        </form>
        <hr>
        <div class="card-title">
          <h4>{$PAYMENT_METHOD}</h4>
        </div>
        <form action="" method="post" id="forms">
          {foreach from=$PAYMENT_METHODS item=gateway}
            <div class="form-group">
              <div class="form-check">
                <input type="radio" name="payment_method" value="{$gateway.name}" required>
                <label>{$gateway.displayname}</label>
              </div>
            </div>
          {/foreach}
          <hr>
          <div class="form-group">
            <div class="form-check" style="display:inline;">
              <input type="hidden" name="token" value="{$TOKEN}">
              <label>{$AGREE_T_AND_C_PURCHASE} <input type="checkbox" name="t_and_c" value="1" required><button
                  class="btn btn-success">{$PURCHASE} &raquo;</button></label>
            </div>
          </div>
          </br>
        </form>
      </div>
    </div>
  </div>
    {include file='footer.tpl'}