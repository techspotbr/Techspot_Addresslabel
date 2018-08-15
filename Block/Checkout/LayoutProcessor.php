<?php
/**
 * Copyright (C) 2018  Tech Spot. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Techspot\Addresslabel\Block\Checkout;

/**
 * Class LayoutProcessor
 */
class LayoutProcessor implements \Magento\Checkout\Block\Checkout\LayoutProcessorInterface
{
    protected $scopeConfig;
    
    protected $addresslabelHelper;
    
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Techspot\Addresslabel\Helper\Data $addresslabelHelper
    ){
        $this->scopeConfig = $scopeConfig;
        $this->addresslabelHelper = $addresslabelHelper;
    }

    public function process($result){

        $addressTypes = array('shipping', 'billing');
        
        $addressTarget = $this->getAddressTarget($result, 'shipping');

        if(isset($addressTarget)){
            $addressLabels =  $addressTarget['children'];
            $addressLines = $this->addresslabelHelper->getStreetLines();

            /*
            if(isset($addressLabels['street']['label'])){
                unset($addressLabels['street']['label']);
                unset($addressLabels['street']['required']);
            }*/

            $addressLabels['street']['config']['additionalClasses'] = $addressLabels['street']['config']['additionalClasses'] . ' experius-address-lines';

            for($i = 0; $i < $addressLines; $i++){
                $addressLabels['street']['children'][$i]['label'] = $this->addresslabelHelper->getAddressLabel($i+1);
                $addressLabels['street']['children'][$i]['additionalClasses'] = 'address-multiline';
                $addressLabels['street']['children'][$i]['required'] = ($this->addresslabelHelper->getAddressInput($i+1)) ? true : false;
            }
            
            $result = $this->setAddressTargetChildren($result, 'shipping', $addressLabels);
        }

        return $result;
    }

    protected function getAddressTarget($result, $addressType)
    {   
        return $result['components']['checkout']['children']['steps']
                    ['children'][$addressType.'-step']['children']
                    [$addressType.'Address']['children'][$addressType.'-address-fieldset'];
    }

    protected function setAddressTargetChildren($result, $addressType, $addressLabels)
    {
        $result['components']['checkout']['children']['steps']['children']
            [$addressType.'-step']['children'][$addressType.'Address']['children']
            [$addressType.'-address-fieldset']['children'] = $addressLabels;

        return $result;
    }

}
