<?php

class Webgriffe_TaxIdPro_Helper_Data extends Mage_Core_Helper_Abstract
{
    const IS_TAX_CODE_REQUIRED_CONFIG_PATH = 'customer/wg_taxidpro/is_tax_code_required';
    const IS_VAT_NUMBER_REQUIRED_CONFIG_PATH = 'customer/wg_taxidpro/is_vat_number_required';
    const IS_ELECTRONIC_INVOICE_ENABLE = 'customer/wg_taxidpro/is_electronic_invoice_enable';

    public function isTaxCodeRequired()
    {
        return Mage::getStoreConfigFlag(self::IS_TAX_CODE_REQUIRED_CONFIG_PATH);
    }

    public function isVatNumberRequired()
    {
        return Mage::getStoreConfigFlag(self::IS_VAT_NUMBER_REQUIRED_CONFIG_PATH);
    }
    public function getIsElectronicInvoiceEnable()
    {
        return Mage::getStoreConfig(self::IS_ELECTRONIC_INVOICE_ENABLE);
    }

    /**
     * @param $vatNumber
     * @return bool
     */
    public function validateVatNumberCheckDigit($vatNumber)
    {
        $s = 0;
        for ($i = 0; $i <= 9; $i += 2) {
            $s += ord($vatNumber[$i]) - ord('0');
        }

        for ($i = 1; $i <= 9; $i += 2) {
            $c = 2 * (ord($vatNumber[$i]) - ord('0'));
            if ($c > 9) {
                $c = $c - 9;
            }
            $s += $c;
        }

        if ((10 - $s % 10) % 10 != ord($vatNumber[10]) - ord('0')) {
            return false;
        }
        return true;
    }
}
