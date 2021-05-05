<?php
/**
 * Copyright © Nguyen Thanh Tu, Inc. All rights reserved.
 */
namespace ViMagento\VipPoint\Observer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\State\InputMismatchException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\QuoteFactory;

class CheckoutSuccess implements \Magento\Framework\Event\ObserverInterface
{
    protected $Session;
    protected $CustomerRepositoryInterface;
    protected $messageManager;
    protected $scopeConfig;
    protected $quoteFactory;
    /**
     * Get the configuration value in
     * Store-> Configuration -> Sales -> VipPoint
     */
    const VIP_POINT = "vippoint/vip_point/vip_point_value";

    public function __construct(
        Session $Session,
        CustomerRepositoryInterface  $CustomerRepositoryInterface,
        ManagerInterface $messageManager,
        ScopeConfigInterface $scopeConfig,
        QuoteFactory $quoteFactory
    ) {
        $this->Session = $Session;
        $this->CustomerRepositoryInterface = $CustomerRepositoryInterface;
        $this->messageManager = $messageManager;
        $this->scopeConfig = $scopeConfig;
        $this->quoteFactory = $quoteFactory;
    }

    public function execute(Observer $observer)
    {
        if ($this->Session->isLoggedIn()) {

            // Vippoint refund
            $extracts = $this->scopeConfig->getvalue(self::VIP_POINT);
            /**
             * Get user by id
             */
            $vip_point = $this->CustomerRepositoryInterface->getById($this->Session->getCustomerId());

            /**
             * Get vip_point
            */
            $getVipPoint = $vip_point->getCustomAttribute('vip_point');
            $getVipPointValue = $getVipPoint->getValue();
            $base_grand_total = $observer->getData('order')->getData('base_grand_total');
            $AddVipPoint = $base_grand_total*($extracts/100);

            /**
             * Set vip_point
             */
            $vip_point->setCustomAttribute('vip_point',$getVipPointValue + $AddVipPoint);
            try {
                $this->CustomerRepositoryInterface->save($vip_point);
            } catch (InputException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (InputMismatchException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }
        return $this;
    }
}
