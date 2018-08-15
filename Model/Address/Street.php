<?php

namespace Techspot\Addresslabel\Model\Address;

/**
 * Line count config model for customer address street attribute
 *
 * @method string getWebsiteCode()
 */
class Street extends \Magento\Customer\Model\Config\Backend\Address\Street
{    

    const CONFIG_ADDRESS_STREET_LINES = "customer/address/street_lines";

    /**
     * Actions after save
     *
     * @return $this
     */
    public function afterSave()
    {
        $addressLines = $this->_config->getValue(self::CONFIG_ADDRESS_STREET_LINES, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $value = $this->getValue();
        if($value > 0 && $value != $addressLines){
            $attribute = $this->_eavConfig->getAttribute('customer_address', 'street');
            $website = $this->_storeManager->getWebsite($this->getScopeCode());
            $attribute->setWebsite($website);
            $attribute->load($attribute->getId());
            $attribute->setData('multiline_count', $addressLines);
            $attribute->save();
        }

        return $this;
    }
}