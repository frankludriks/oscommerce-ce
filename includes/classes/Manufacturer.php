<?php
/**
 * osCommerce Online Merchant
 * 
 * @copyright Copyright (c) 2011 osCommerce; http://www.oscommerce.com
 * @license BSD License; http://www.oscommerce.com/bsdlicense.txt
 */


  class Manufacturer {
    protected $_data = array();

    public function __construct($id) {

      $Qmanufacturer_query = tep_db_query("select manufacturers_id as id, manufacturers_name as name, manufacturers_image as image from manufacturers where manufacturers_id = '"  . $id . "'");

      $result = tep_db_fetch_array($Qmanufacturer_query);

      if ( $result !== false ) {
        $this->_data = $result;
      }
    }

    function getID() {
      if ( isset($this->_data['id']) ) {
        return $this->_data['id'];
      }

      return false;
    }

    function getTitle() {
      if ( isset($this->_data['name']) ) {
        return $this->_data['name'];
      }

      return false;
    }

    function getImage() {
      if ( isset($this->_data['image']) ) {
        return $this->_data['image'];
      }

      return false;
    }
  }
?>
