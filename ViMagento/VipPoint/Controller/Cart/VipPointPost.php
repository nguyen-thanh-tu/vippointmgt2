<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ViMagento\VipPoint\Controller\Cart;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class VipPointPost extends \Magento\Checkout\Controller\Cart implements HttpPostActionInterface
{
    /**
     * Sales quote repository
     *
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;
    private $scopeConfig;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
    ) {
        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart
        );
        $this->quoteRepository = $quoteRepository;
        $this->_checkoutSession = $checkoutSession;
        $this->scopeConfig = $scopeConfig;
    }

    const exchange_rate = "vippoint/vip_point/exchange_rate";
    const limited_use = "vippoint/vip_point/limited_use";
    /**
     * Initialize coupon
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $extracts = $this->scopeConfig->getValue(self::exchange_rate);
        $limit_import = $this->scopeConfig->getValue(self::limited_use);

        $VipPoint = $this->getRequest()->getParam('remove') == 1
            ? ''
            : trim($this->getRequest()->getParam('vip_point'));

        $cartQuote = $this->cart->getQuote();
        $CustomerVipPoint = $cartQuote->getCustomer()->getCustomAttribute('vip_point')->getValue();
        $CurrencyRate = $cartQuote->getBaseToQuoteRate();
        if (strlen($VipPoint)) {
            if(!is_numeric($VipPoint)){
                $this->messageManager->addErrorMessage(__('VipPoint must be 1 number.'));

                return $this->_goBack();
            }else if($VipPoint <= 0 || $VipPoint >= $CustomerVipPoint || $VipPoint > $limit_import){
                $this->messageManager->addErrorMessage(__('VipPoint must be greater than 0 and less than your VipPoint and '.$limit_import.'. Your VipPoint: '.$CustomerVipPoint));

                return $this->_goBack();
            }
        }
        try {
            $itemsCount = $cartQuote->getItemsCount();
            if ($itemsCount) {
                $cartQuote->getShippingAddress()->setCollectShippingRates(true);
                $cartQuote->setData('vip_point', $VipPoint);

                if($this->getRequest()->getParam('vip_point') == null){
                    $cartQuote->setData('subtotal_with_vip_point', null);
                    $cartQuote->setData('base_subtotal_with_vip_point', null);
                }else{
                    $cartQuote->setData('subtotal_with_vip_point', ($VipPoint*$extracts*$CurrencyRate));
                    $cartQuote->setData('base_subtotal_with_vip_point', ($VipPoint*$extracts));
                }
                $this->quoteRepository->save($cartQuote);
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            if($this->getRequest()->getParam('vip_point') != null){
                $this->messageManager->addErrorMessage(__('We cannot apply the VipPoint code.'));
                $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
            }
        }

        return $this->_goBack();
    }
}
