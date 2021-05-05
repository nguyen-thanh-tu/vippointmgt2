<?php
namespace ViMagento\VipPoint\Block\Sales\Order;

use Magento\Sales\Model\Order;

class VippointOrder extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Order
     */
    protected $_order;
    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }
    public function getSource()
    {
        return $this->_source;
    }

    public function displayFullSummary()
    {
        return true;
    }
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->_order = $parent->getOrder();
        $this->_source = $parent->getSource();
        $title = 'VipPoint';
        $vippoint = $this->_order->getData("vip_point");
        $subtotal_with_vip_point = $this->_order->getData("subtotal_with_vip_point");
        $base_subtotal_with_vip_point = $this->_order->getData("base_subtotal_with_vip_point");
        if($vippoint!=0){
            $VipPointAmount = new \Magento\Framework\DataObject(
                [
                    'code' => 'vippoint_order',
                    'strong' => false,
                    'base_value' => -$base_subtotal_with_vip_point,
                    'value' => -$subtotal_with_vip_point,
                    'label' => __($title.' ('.$vippoint.')'),
                ]
            );
            $parent->addTotal($VipPointAmount, 'vippoint_order');
        }
        return $this;
    }
    /**
     * Get order store object
     *
     * @return \Magento\Store\Model\Store
     */
    public function getStore()
    {
        return $this->_order->getStore();
    }
    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->_order;
    }
    /**
     * @return array
     */
    public function getLabelProperties()
    {
        return $this->getParentBlock()->getLabelProperties();
    }
    /**
     * @return array
     */
    public function getValueProperties()
    {
        return $this->getParentBlock()->getValueProperties();
    }
}