<?php

use PrestaShop\PrestaShop\Core\Admin\Tab;

if (!defined('_PS_VERSION_')) {
    exit;
}

class ProductRegistrations extends Module
{
    public function __construct()
    {
        $this->name = 'productregistrations';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Sangam Kumar';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Product Registrations Module');
        $this->description = $this->l('Allow customers to register product information.');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => '8.9.99');
    }

    public function install()
    {
        // Create necessary database tables
        $this->createTables();
        $this->registerAdminController();

        // Implement hooks and other installation logic
        // ...

        return parent::install() &&
            $this->registerHook('displayForm') &&
            $this->registerHook('actionFrontControllerSetMedia');
    }

    private function createTables()
    {
        $sql = array();

        // Table for product registrations
        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'product_registrations` (
            `id_product_registration` INT AUTO_INCREMENT,
            `product_name` VARCHAR(255),
            `product_id` INT,
            `purchase_date` DATE,
            `attachments` TEXT,
            `warranty_status` VARCHAR(255),
            `warranty_claimed_at` DATETIME,
            `warranty_expires_at` DATE,
            `invoice_number` VARCHAR(255),
            `customer_name` VARCHAR(255),
            `customer_email` VARCHAR(255),
            `customer_phone_number` VARCHAR(20),
            PRIMARY KEY (`id_product_registration`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        foreach ($sql as $query) {
            if (Db::getInstance()->execute($query) == false) {
                return false;
            }
        }

        return true;
    }

    private function registerAdminController()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminProductRegistrations';
        $tab->module = $this->name;
        $tab->id_parent = 0;
        $tab->name = array();
        foreach (Language::getLanguages() as $lang) {
            $tab->name[$lang['id_lang']] = $this->l('Product Registrations');
        }

        if (!$tab->save()) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        // Remove database tables on uninstallation
        $this->dropTables();
        $this->unregisterAdminController();

        // Implement uninstallation logic
        // ...

        return parent::uninstall();
    }

    private function dropTables()
    {
        $sql = array();

        // Drop the table for product registrations
        $sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'product_registrations`;';

        foreach ($sql as $query) {
            Db::getInstance()->execute($query);
        }
    }

    private function unregisterAdminController()
    {
        $idTab = Tab::getIdFromClassName('AdminProductRegistrations');
        if ($idTab) {
            $tab = new Tab($idTab);
            $tab->delete();
        }
    }

    public function hookDisplayForm($params)
    {
        $this->context->smarty->assign(array(
            'logged' => $this->context->customer->isLogged(),
            'customer_firstname' => $this->context->customer->firstname,
            'customer_lastname' => $this->context->customer->lastname,
            'fields' => array(
                array('name' => 'product_name', 'label' => 'Product Name', 'type' => 'text'),
                array('name' => 'product_id', 'label' => 'Product ID', 'type' => 'text'),
                array('name' => 'purchase_date', 'label' => 'Purchase Date', 'type' => 'date'),
                array('name' => 'attachments', 'label' => 'Attachments', 'type' => 'file'),
                array('name' => 'warranty_status', 'label' => 'Warranty Status', 'type' => 'text'),
                array('name' => 'warranty_claimed_at', 'label' => 'Warranty Claimed At', 'type' => 'date'),
                array('name' => 'warranty_expires_at', 'label' => 'Warranty Expires At', 'type' => 'date'),
                array('name' => 'invoice_number', 'label' => 'Invoice Number', 'type' => 'text'),
                array('name' => 'customer_name', 'label' => 'Customer Name', 'type' => 'text'),
                array('name' => 'customer_email', 'label' => 'Customer Email', 'type' => 'text'),
                array('name' => 'customer_phone_number', 'label' => 'Customer Phone Number', 'type' => 'text'),
            ),
        ));

        return $this->display(__FILE__, 'views/templates/hook/displayForm.tpl');
    }

    public function hookActionFrontControllerSetMedia()
    {
        // Include necessary CSS and JavaScript for your form
        // ...

        // Example:
        $this->context->controller->addJS($this->_path . 'views/js/script.js');
        $this->context->controller->addCSS($this->_path . 'views/css/style.css');
    }

    // Add the following methods to the ProductRegistrations class in productregistrations.php

    public function processFormSubmission()
    {
        // Handle form submission logic
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process submitted form data
            $formData = $_POST;
            $fileData = $_FILES;

            // Validate and sanitize data
            // ...

            // Save product registration entry to the database
            $this->saveProductRegistration($formData, $fileData);

            // Redirect or display a success message
            // ...
        }
    }

    private function saveProductRegistration($formData, $fileData)
    {
        // Implement logic to save product registration entry to the database
        // ...

        // Example: Insert data into the product_registrations table
        Db::getInstance()->insert('product_registrations', array(
            'product_name' => pSQL($formData['product_name']),
            'product_id' => (int)$formData['product_id'],
            'purchase_date' => pSQL($formData['purchase_date']),
            'attachments' => pSQL(json_encode($fileData['attachments'])),
            'warranty_status' => pSQL($formData['warranty_status']),
            'warranty_claimed_at' => pSQL($formData['warranty_claimed_at']),
            'warranty_expires_at' => pSQL($formData['warranty_expires_at']),
            'invoice_number' => pSQL($formData['invoice_number']),
            'customer_name' => pSQL($formData['customer_name']),
            'customer_email' => pSQL($formData['customer_email']),
            'customer_phone_number' => pSQL($formData['customer_phone_number']),
        ));

        // Get the entry ID
        $entryId = Db::getInstance()->Insert_ID();

        // Save uploaded files
        $this->saveFileAttachments($entryId, $fileData);
    }

    private function saveFileAttachments($entryId, $fileData)
    {
        // Implement logic to save file attachments
        // ...

        // Example: Move uploaded files to a designated folder
        foreach ($fileData['attachments']['tmp_name'] as $index => $tmpName) {
            $targetPath = _PS_UPLOAD_DIR_ . $entryId . '_attachment_' . $index . '.' . pathinfo($fileData['attachments']['name'][$index], PATHINFO_EXTENSION);
            move_uploaded_file($tmpName, $targetPath);
        }
    }
}
