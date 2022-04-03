<?php
/**
 * Copyright © Juan Manuel Cinto All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace JMC\CustomContactPage\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Cms\Model\PageFactory;
use Magento\Contact\Helper\Data as Helper;

/**
 * Create Contact Page
 *
 * @author Juan Manuel Cinto <juanmanuelcinto@gmail.com>
 */
class CreateCmsContactPage implements DataPatchInterface
{

    const URL_KEY = 'contact_page';

    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * Constructor
     *
     * @param PageFactory $pageFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        PageFactory $pageFactory,
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->pageFactory = $pageFactory;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * Create the contact page.
     *
     * @return CreateCheckoutSuccessPages|void
     */
    public function apply()
    {
        try {
            $this->moduleDataSetup->startSetup();

            $pageConfig = [
                'is_active' => 1,
                'page_layout' => '1column',
                'layout_update_xml' => '',
                'stores' => [0],
                'title' => 'Contact',
                'identifier' => self::URL_KEY,
                'content' => <<<EOD
<form class="form contact"
      action="{{config path="web/secure/base_url"}}contact/index/post"
      id="contact-form"
      method="post">
    <fieldset class="fieldset">
        <legend class="legend"><span>{{trans "Write Us"}}</span></legend><br />
        <div class="field note no-label">{{trans "Jot us a note and we’ll get back to you as quickly as possible."}}</div>
        <div class="field name required">
            <label class="label" for="name"><span>{{trans "Name"}}</span></label>
            <div class="control">
                <input name="name" id="name" title="{{trans "Name"}}" value="{{widget type="JMC\CustomContactPage\Block\Widget\Contact" value="username"}}" class="input-text" type="text" data-validate="{required:true}"/>
            </div>
        </div>
        <div class="field email required">
            <label class="label" for="email"><span>{{trans "Email"}}</span></label>
            <div class="control">
                <input name="email" id="email" title="{{trans "Email"}}" value="{{widget type="JMC\CustomContactPage\Block\Widget\Contact" value="email"}}" class="input-text" type="email" data-validate="{required:true}"/>
            </div>
        </div>
        <div class="field telephone">
            <label class="label" for="telephone"><span>{{trans "Phone Number"}}</span></label>
            <div class="control">
                <input name="telephone" id="telephone" title="{{trans "Phone Number"}}" value="{{widget type="JMC\CustomContactPage\Block\Widget\Contact" value="phone"}}" class="input-text" type="text" />
            </div>
        </div>
        <div class="field comment required">
            <label class="label" for="comment"><span>{{trans "What’s on your mind?"}}</span></label>
            <div class="control">
                <textarea name="comment" id="comment" title="{{trans "What’s on your mind?"}}" class="input-text" cols="5" rows="3" data-validate="{required:true}">{{widget type="JMC\CustomContactPage\Block\Widget\Contact" value="comment"}}</textarea>
            </div>
        </div>
    </fieldset>
    <div class="actions-toolbar">
        <div class="primary">
            <input type="hidden" name="hideit" id="hideit" value="" />
            <input type="hidden" name="form_key" value="{{block class="Magento\Framework\View\Element\FormKey" output="getFormKey"}}" />
            <button type="submit" title="{{trans "Submit"}}" class="action submit primary">
                <span>{{trans "Submit"}}</span>
            </button>
        </div>
    </div>
</form>
EOD,
            ];

            $this->pageFactory->create()->setData($pageConfig)->save();
            $this->moduleDataSetup->endSetup();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function revert()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [

        ];
    }
}
