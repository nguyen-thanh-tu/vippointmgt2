<?php
namespace ViMagento\VipPoint\Observer;

use Magento\Framework\Event\Observer;


class CurrencyOption implements \Magento\Framework\Event\ObserverInterface
{
    private \Magento\Checkout\Model\Cart $cart;
    private \Magento\Quote\Api\CartRepositoryInterface $quoteRepository;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
    ) {
        $this->cart = $cart;
        $this->quoteRepository = $quoteRepository;
        $this->_checkoutSession = $checkoutSession;
    }
    public function execute(Observer $observer)
    {
        $cartQuote = $this->cart->getQuote();
        $CurrencyRate = $cartQuote->getBaseToQuoteRate();
        $base_subtotal_with_vip_point = $cartQuote->getData('base_subtotal_with_vip_point');
        $cartQuote->setData('subtotal_with_vip_point', ($base_subtotal_with_vip_point*$CurrencyRate));
        $this->quoteRepository->save($cartQuote);
        return $this;
    }
}