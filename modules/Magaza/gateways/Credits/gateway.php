<?php
/**
 * Credits_Gateway class
 *
 * @package Modules\Magaza
 * @author Partydragen
 * @version 2.0.0-pr13
 * @license MIT
 */
class Credits_Gateway extends GatewayBase {

    public function __construct() {
        $name = 'Kredi';
        $author = '<a href="https://radome.web.tr" target="_blank" rel="nofollow noopener">RadomeWEB</a>';
        $gateway_version = '1.4.3';
        $store_version = '1.4.3';
        $settings = ROOT_PATH . '/modules/Magaza/gateways/Credits/gateway_settings/settings.php';

         parent::__construct($name, $author, $gateway_version, $store_version, $settings);
    }

    public function onCheckoutPageLoad(TemplateBase $template, Customer $customer): void {
        if (!$customer->exists()) {
            $this->setEnabled(false);
            return;
        }

        $this->setDisplayname(
            Magaza::getLanguage()->get('general', 'pay_with_credits', [
                'currency_symbol' => Magaza::getCurrencySymbol(),
                'currency' => Magaza::getCurrency(),
                'credits' => $customer->getCredits()
            ])
        );
    }

    public function processOrder(Order $order): void {
        $customer = $order->customer();
        $amount_to_pay = $order->getAmount()->getTotalCents();

        if ($customer->exists() && $customer->data()->cents >= $amount_to_pay) {
            $customer->removeCents($amount_to_pay);

            $payment = new Payment();
            $payment->handlePaymentEvent(Payment::COMPLETED, [
                'order_id' => $order->data()->id,
                'gateway_id' => $this->getId(),
                'amount_cents' => $amount_to_pay,
                'transaction' => 'Credits',
                'currency' => Magaza::getCurrency()
            ]);

            $shopping_cart = new ShoppingCart();
            $shopping_cart->clear();
            Redirect::to(URL::build(Magaza::getMagazaPath() . '/checkout/', 'do=complete'));
        } else {
            $this->addError('Bu siparişi tamamlamak için yeterli Krediniz yok!');
        }
    }

    public function handleReturn(): bool {
        return false;
    }

    public function handleListener(): void {

    }
}

$gateway = new Credits_Gateway();