<?php
namespace ViMagento\VipPoint\Observer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\State\InputMismatchException;
use Magento\Framework\Message\ManagerInterface;

class SaveOrderBeforeSalesModelQuoteObserver implements ObserverInterface
{
    protected $CustomerRepositoryInterface;
    /**
     * @var \Magento\Framework\DataObject\Copy
     */
    protected $objectCopyService;
    protected $messageManager;
    /**
     * @param \Magento\Framework\DataObject\Copy $objectCopyService
     * ...
     */
    public function __construct(
        CustomerRepositoryInterface  $CustomerRepositoryInterface,
        ManagerInterface $messageManager,
        \Magento\Framework\DataObject\Copy $objectCopyService
    ) {
        $this->CustomerRepositoryInterface = $CustomerRepositoryInterface;
        $this->objectCopyService = $objectCopyService;
        $this->messageManager = $messageManager;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /* @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getData('order');
        /* @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getData('quote');

        /** get VipPoint */
        $customer = $this->CustomerRepositoryInterface->getById($quote->getCustomerId());
        $getVipPointValue = $customer->getCustomAttribute('vip_point')->getValue();
        $getVipPointQuote = $quote->getData("vip_point");

        /**
         *  set VipPoint
         */
        $customer->setCustomAttribute('vip_point',$getVipPointValue - $getVipPointQuote);

        try {
            /**
             *  save setting
             */
            $this->CustomerRepositoryInterface->save($customer);
        } catch (InputException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (InputMismatchException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $this->objectCopyService->copyFieldsetToTarget('sales_convert_quote', 'to_order', $quote, $order);
        return $this;
    }
}