<?php

namespace ViMagento\Banner\Block\Adminhtml\Index\Edit;

use Magento\Backend\Block\Widget\Context;
use ViMagento\Banner\Model\BannerFactory;

class GenericButton
{

    protected $context;
    protected $bannerFactory;

    public function __construct(
        Context $context,
        BannerFactory $bannerFactory
    )
    {
        $this->context = $context;
        $this->bannerFactory = $bannerFactory;
    }

    /**
     * Return Banner ID
     */
    public function getBannerId()
    {
        $id = $this->context->getRequest()->getParam('id');
        $banner = $this->bannerFactory->create()->load($id);
        if ($banner->getId())
            return $id;
        return null;
    }

    /**
     * Generate url by route and parameters
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
