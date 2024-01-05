<?php
use PrestaShop\PrestaShop\Core\Admin\Tab;

class AdminProductRegistrationsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'product_registrations';
        $this->className = 'ProductRegistration';
        $this->identifier = 'id_product_registration';
        $this->lang = false;

        parent::__construct();

        $this->fields_list = array(
            'id_product_registration' => array('title' => $this->l('ID'), 'align' => 'center', 'width' => 25),
            'product_name' => array('title' => $this->l('Product Name')),
            'product_id' => array('title' => $this->l('Product ID')),
            'purchase_date' => array('title' => $this->l('Purchase Date')),
            'warranty_status' => array('title' => $this->l('Warranty Status')),
            'customer_name' => array('title' => $this->l('Customer Name')),
            'customer_email' => array('title' => $this->l('Customer Email')),
            'customer_phone_number' => array('title' => $this->l('Customer Phone Number')),
            'date_add' => array('title' => $this->l('Date Added'), 'type' => 'datetime'),
        );

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
            ),
        );
    }
}
