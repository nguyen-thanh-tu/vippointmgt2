<?php
Namespace ViMagento\VipPoint\Model\Quote\Address\Total;

class Vippoint extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    /**
     * Custom constructor.
     */
    public function __construct()
    {
        $this->setCode('vippoint');
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        $items = $shippingAssignment->getItems();
        if (!count($items)) {
            return $this;
        }

        //we will add an additional amount of 150 to the order as an example
        $subtotal_with_vip_point = $quote->getData("subtotal_with_vip_point");
        $base_subtotal_with_vip_point = $quote->getData("base_subtotal_with_vip_point");

        $total->setTotalAmount('vip_point', $subtotal_with_vip_point);
        $total->setBaseTotalAmount('vip_point', $base_subtotal_with_vip_point);
        $total->setCustomAmount($subtotal_with_vip_point);
        $total->setBaseCustomAmount($base_subtotal_with_vip_point);
        $total->setGrandTotal($total->getGrandTotal() - $subtotal_with_vip_point);
        $total->setBaseGrandTotal($total->getBaseGrandTotal() - $base_subtotal_with_vip_point);

        return $this;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     */
    protected function clearValues(\Magento\Quote\Model\Quote\Address\Total  $total)
    {
        $total->setTotalAmount('subtotal', 0);
        $total->setBaseTotalAmount('subtotal', 0);
        $total->setTotalAmount('tax', 0);
        $total->setBaseTotalAmount('tax', 0);
        $total->setTotalAmount('discount_tax_compensation', 0);
        $total->setBaseTotalAmount('discount_tax_compensation', 0);
        $total->setTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setSubtotalInclTax(0);
        $total->setBaseSubtotalInclTax(0);
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return array
     */
    public function fetch(
        \Magento\Quote\Model\Quote  $quote,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        $vippoint = $quote->getData("vip_point");
        $value = $quote->getData("subtotal_with_vip_point");
        return [
            'code' => $this->getCode(),
            'title' => __($vippoint),
            'value' => $value
        ];
    }
 
    /**
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('VipPoint');
    }
}