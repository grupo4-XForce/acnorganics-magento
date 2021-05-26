<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category  BSS
 * @package   Bss_Limitcartqty
 * @author    Extension Team
 * @copyright Copyright (c) 2018-2019 BSS Commerce Co. ( http://bsscommerce.com )
 * @license   http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\Limitcartqty\Model\System\Config\Backend;

use Bss\Limitcartqty\Helper\ConfigValue;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Value;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

/**
 * Class ConfigModel
 *
 * @package Bss\Limitcartqty\Model\System\Config\Backend
 */
class ConfigModel extends Value
{
    /**
     * @var ConfigValue|null
     */
    protected $configValue = null;

    /**
     * ConfigModel constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param ConfigValue $configValue
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        ConfigValue $configValue,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->configValue = $configValue;
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * _afterLoad
     *
     * @return Value|void
     * @throws LocalizedException
     */
    protected function _afterLoad()
    {
        $value = $this->getValue();
        $value = $this->configValue->makeArrayFieldValue($value);
        $this->setValue($value);
    }

    /**
     * BeforeSave
     *
     * @return Value|void
     * @throws LocalizedException
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        if (!$this->configValue->validateMinMaxQty($value)) {
            throw new \Magento\Framework\Exception\ValidatorException(__('Qty must be integer and greater than zero.'));
        }
        $value = $this->configValue->makeStorableArrayFieldValue($value);
        $this->setValue($value);
    }
}
