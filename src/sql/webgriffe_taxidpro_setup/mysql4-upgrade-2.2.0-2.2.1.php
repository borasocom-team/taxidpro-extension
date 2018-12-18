<?php
$setup = $this;
$setup->startSetup();

$attributes = array(
    'pec_email_address' => array(
        'label'             => Mage::helper('webgriffe_taxidpro')->__('Pec address'),
        'type'      => 'varchar',
        'input'    => 'text',
        'user_defined'   => 1,
        'visible'        => 1,
        'required'       => 0,
        'sort_order'       => -20
    ),
    'sdi_code' => array(
        'label'             => Mage::helper('webgriffe_taxidpro')->__('SDI code'),
        'type'      => 'varchar',
        'input'    => 'text',
        'user_defined'   => 1,
        'visible'        => 1,
        'required'       => 0,
        'sort_order'       => -10
    )
);

$usedInForms = array(
    'adminhtml_customer_address',
    'customer_address_edit',
    'customer_register_address',
    'customer_account_create'
);


foreach($attributes as $attribute_code => $attribute_data) {

    $setup->addAttribute(
        'customer_address',
        $attribute_code,
        $attribute_data
    );


    $attribute = Mage::getSingleton('eav/config')->getAttribute('customer_address', $attribute_code);
    $attribute->setData('used_in_forms', $usedInForms);
    $attribute->save();

    $setup->run("
        ALTER TABLE {$this->getTable('sales_flat_quote_address')} ADD COLUMN " . $attribute_code . " VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL;
    
        ALTER TABLE {$this->getTable('sales_flat_order_address')} ADD COLUMN " . $attribute_code . " VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL;
    ");
}


$coreConfig = Mage::getModel('core/config');
$coreConfig->saveConfig('customer/address_templates/text', '{{depend prefix}}{{var prefix}} {{/depend}}{{var firstname}} {{depend middlename}}{{var middlename}} {{/depend}}{{var lastname}}{{depend suffix}} {{var suffix}}{{/depend}}
        {{depend company}}{{var company}}{{/depend}}
        {{depend tax_code}}CF: {{var tax_code}}{{/depend}}
        {{depend vat_number}}P.IVA: {{var vat_number}}{{/depend}}
        {{depend pec_email_address}}PEC: {{var pec_email_address}}{{/depend}}
        {{depend sdi_code}}SDI Code: {{var sdi_code}}{{/depend}}
        {{if street1}}{{var street1}}
        {{/if}}
        {{depend street2}}{{var street2}}{{/depend}}
        {{depend street3}}{{var street3}}{{/depend}}
        {{depend street4}}{{var street4}}{{/depend}}
        {{if city}}{{var city}},  {{/if}}{{if region}}{{var region}}, {{/if}}{{if postcode}}{{var postcode}}{{/if}}
        {{var country}}
        T: {{var telephone}}
        {{depend fax}}F: {{var fax}}{{/depend}}', 'default', 0);

$coreConfig->saveConfig('customer/address_templates/html', '{{depend prefix}}{{var prefix}} {{/depend}}{{var firstname}} {{depend middlename}}{{var middlename}} {{/depend}}{{var lastname}}{{depend suffix}} {{var suffix}}{{/depend}}<br/>
        {{depend company}}{{var company}}<br />{{/depend}}
        {{depend tax_code}}CF: {{var tax_code}}<br />{{/depend}}
        {{depend vat_number}}P.IVA: {{var vat_number}}<br />{{/depend}}
        {{depend pec_email_address}}PEC: {{var pec_email_address}}<br />{{/depend}}
        {{depend sdi_code}}SDI Code: {{var sdi_code}}<br />{{/depend}}
        {{if street1}}{{var street1}}<br />{{/if}}
        {{depend street2}}{{var street2}}<br />{{/depend}}
        {{depend street3}}{{var street3}}<br />{{/depend}}
        {{depend street4}}{{var street4}}<br />{{/depend}}
        {{if city}}{{var city}},  {{/if}}{{if region}}{{var region}}, {{/if}}{{if postcode}}{{var postcode}}{{/if}}<br/>
        {{var country}}<br/>
        {{depend telephone}}T: {{var telephone}}{{/depend}}
        {{depend fax}}<br/>F: {{var fax}}{{/depend}}', 'default', 0);

$coreConfig->saveConfig('customer/address_templates/pdf', '{{depend prefix}}{{var prefix}} {{/depend}}{{var firstname}} {{depend middlename}}{{var middlename}} {{/depend}}{{var lastname}}{{depend suffix}} {{var suffix}}{{/depend}}|
        {{depend company}}{{var company}}|{{/depend}}
        {{depend tax_code}}CF: {{var tax_code}}|{{/depend}}
        {{depend vat_number}}P.IVA: {{var vat_number}}|{{/depend}}
        {{depend pec_email_address}}PEC: {{var pec_email_address}}|{{/depend}}
        {{depend sdi_code}}SDI Code: {{var sdi_code}}|{{/depend}}
        {{if street1}}{{var street1}}
        {{/if}}
        {{depend street2}}{{var street2}}|{{/depend}}
        {{depend street3}}{{var street3}}|{{/depend}}
        {{depend street4}}{{var street4}}|{{/depend}}
        {{if city}}{{var city}},  {{/if}}{{if region}}{{var region}}, {{/if}}{{if postcode}}{{var postcode}}{{/if}}|
        {{var country}}|
        {{depend telephone}}T: {{var telephone}}{{/depend}}|
        {{depend fax}}<br/>F: {{var fax}}{{/depend}}|}', 'default', 0);


$setup->endSetup();
