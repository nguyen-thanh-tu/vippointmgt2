<?php

namespace ViMagento\Banner\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $bannerFactory;
    protected $session;
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \ViMagento\Banner\Model\BannerFactory $bannerFactory,
        \Magento\Customer\Model\Session $session
    )
    {
        $this->resultFactory = $context->getResultFactory();
        $this->bannerFactory = $bannerFactory;
        $this->session = $session;
        parent::__construct($context);
    }
    public function execute()
    {
        /**
         * Insert random data
         */
//        $extension = ['.png', '.jpg', '.gif'];
//        $url = ['https://www.google.com.vn/', 'http://www.w3schools.com/', 'https://techmaster.vn/'];
//
//        for ($i = 1; $i <= 100; $i++) {
//            // Create new instance before insert
//            $banner = $this->_objectManager->create('ViMagento\Banner\Model\Banner');
//
//            // Insert data
//            $banner->addData([
//                'link' => $url[rand(0, 2)],
//                'image' => 'image' . $i . $extension[rand(0, 2)],
//                'sort_order' => $i,
//                'status' => rand(0, 1)
//            ])->save();
//        }
        /**
         * Select, update, delete
         */
//        // Select record with id = 1
//        $banner = $this->_objectManager->create('ViMagento\Banner\Model\Banner');
//        $data = $banner->load(1)->getData();
//        print_r(json_encode($data));
//
//        // Update selected record
//        $data->setImage('logo.png')->setLink('google.com')->save();
//
//        // Delete selected record
//        $data->delete();

        // Init collection
//        $collection = $this->bannerFactory->create()->getCollection();

        // SELECT * FROM banner
//        $data = $collection->getData();

        // SELECT * FROM banner WHERE id > 50
//        $data = $collection->addFieldToFilter('id', ['gt' => 50])->getData();

        // SELECT id FROM banner WHERE id > 50
//        $data = $collection->addFieldToSelect('id')
//            ->addFieldToFilter('id', ['gt' => 50])->getData();

        // SELECT * FROM banner WHERE image LIKE '%.png'
//        $data = $collection->addFieldToFilter('image', ['like' => '%.png'])->getData();

//        print_r(json_encode($data));

        // SELECT * FROM banner WHERE image LIKE '%.png' AND id > 50
//        $query = $collection->addFieldToFilter('image', ['like' => '%.png'])
//            ->addFieldToFilter('id', ['gt' => 50])
//            ->getSelect();

        // SELECT * FROM banner WHERE (image LIKE '%.png' OR image LIKE '%.jpg') AND id > 50
//        $query = $collection->addFieldToFilter('id', ['gt' => 50])
//            ->addFieldToFilter('image', [
//                ['like' => '%.png'],
//                ['like' => '%.jpg']
//            ])->getSelect();

        // SELECT * FROM banner WHERE id > 50 OR image LIKE '%.jpg'
//        $query = $collection->addFieldToFilter(['id', 'image'], [
//            ['gt' => 50],
//            ['like' => '%.png']
//        ])->getSelect();

//        echo $query;

        var_dump($this->session->getCustomerId());
        if($this->session->getCustomerId() == null){
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setUrl('customer/account/login');
            return $resultRedirect;
        }else if($this->session->getCustomerData()->getId()){
            var_dump($this->session->getCustomerData());
        }
        exit;
    }

}
