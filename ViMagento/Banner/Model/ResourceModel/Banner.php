<?php

namespace ViMagento\Banner\Model\ResourceModel;

class Banner extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        // Table name + primary key column
        $this->_init('banner', 'id');
    }

    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
//        ghi đè phương thức _afterSave để ảnh đã lưu hiển thị ra bên ngoài
        $oldImage = $object->getOrigData('image');
        $newImage = $object->getData('image');

        if($newImage != null && $newImage != $oldImage){
            $imageUploader = \Magento\Framework\App\ObjectManager::getInstance()->get('\ViMagento\Banner\BannerImageUpload');
            $imageUploader->moveFileFromTmp($newImage);
        }
        //
        return $this;
    }
}
