<?php

namespace Techspot\Addresslabel\Helper;

use Magento\Framework\App\ResourceConnection;
use Magento\Eav\Model\ResourceModel\Entity\Attribute;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    const MODULE_SECTION = 'customer';
    const MODULE_GROUP = 'address';
    const MODULE_FIELD = 'street_address';

    protected $resource;
    protected $eavAttribute;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        ResourceConnection $resourceConnection,
        Attribute $eavAttribute
    ) {
        $this->resource = $resourceConnection;
        $this->eavAttribute = $eavAttribute;
        parent::__construct($context);
    }

    public function getStreetLines()
    {
        return $this->scopeConfig->getValue(self::MODULE_SECTION."/".self::MODULE_GROUP."/street_lines", \Magento\Store\Model\ScopeInterface::SCOPE_STORE);        
    }

    public function getAddressLabel($line)
    {
        $field = $this->getFieldLabel($line);

        return $this->scopeConfig->getValue(self::MODULE_SECTION."/".self::MODULE_GROUP."/{$field}", \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getAddressInput($line)
    {
        $field = $this->getFieldInput($line);

        return $this->scopeConfig->getValue(self::MODULE_SECTION."/".self::MODULE_GROUP."/{$field}", \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    protected function getFieldLabel($line)
    {
        return self::MODULE_FIELD.$line.'_label';
    }

    protected function getFieldInput($line)
    {
        return self::MODULE_FIELD.$line.'_input';
    }
}