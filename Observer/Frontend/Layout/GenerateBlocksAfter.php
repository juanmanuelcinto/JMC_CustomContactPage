<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace JMC\CustomContactPage\Observer\Frontend\Layout;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class GenerateBlocksAfter implements \Magento\Framework\Event\ObserverInterface
{

    const CONTACT_FORM_BLOCK = 'contactForm';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        /** @var \Magento\Framework\View\Layout $layout */
        $layout = $observer->getLayout();
        $block = $layout->getBlock(self::CONTACT_FORM_BLOCK);
        if ($block) {
            $useCustomContactPage = $this->scopeConfig->isSetFlag(
                'contact/contact/cms_page_enabled',
                ScopeInterface::SCOPE_STORE
            );

            if ($useCustomContactPage) {
                $layout->unsetElement(self::CONTACT_FORM_BLOCK);
            }
        }
    }
}
