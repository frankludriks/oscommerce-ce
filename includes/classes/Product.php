<?php


class Product {


protected $_data = array();

    function __construct($id) {
      
	  global $languages_id;
	    
		if ( tep_not_null($id) ){
          $product_data_query = tep_db_query("select products_id as id, products_quantity as quantity, products_price as price, products_model as model, products_gtin as gtin, products_tax_class_id as tax_class_id, products_weight as weight, products_date_added as date_added, manufacturers_id from products where products_id = '" . (int)$id . "' and products_status = '1' ");
			
			$product_data = tep_db_fetch_array($product_data_query);
			$this->_data = $product_data;
            $this->_data['master_id'] = $product_data['id'];
            
			if ( $product_data !== false && !empty($this->_data) ) {
              
				$product_info_query = tep_db_query("select products_name as name, products_description as description, products_url as url from products_description where products_id = '" . $this->_data['master_id'] . "' and language_id = '" . (int)$languages_id . "'");
				$product_info = tep_db_fetch_array($product_info_query);
				
				$this->_data = array_merge($this->_data, $product_info);			  
		    }
			
			if ( !empty($this->_data) ) {
				
				$product_images_query = tep_db_query("select id, image, default_flag from products_images where products_id = '" . $this->_data['master_id'] . "' order by sort_order");
				$product_images = tep_db_fetch_array($product_images_query);
				
				$this->_data['images'] = $product_images;						
			}

			if ( !empty($this->_data) ) {
				
				$product_category_query = tep_db_query("select categories_id from products_to_categories where products_id = '" . $this->_data['master_id'] . "' limit 1");
				$product_category = tep_db_fetch_array($product_images_query);
				
				$this->_data['category_id'] = $product_category;						
			
			//under developement
				$products_attributes_query = tep_db_query("select count(*) as total from products_options popt, products_attributes patrib where patrib.products_id='" . $this->_data['master_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
				$products_attributes = tep_db_fetch_array($products_attributes_query);
				if ($products_attributes['total'] > 0) {
					$this->_data['attributes'] = $products_attributes['total'];
				
					$OSCOM_Currencies = new currencies();
					$OSCOM_ShoppingCart = new shoppingCart();

					$products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from products_options popt, products_attributes patrib where patrib.products_id='" . $this->_data['master_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");
					while ($products_options_data = tep_db_fetch_array($products_options_name_query)) {
						$this->_data['products_options_id'] = $products_options_data['products_options_id'];
						$this->_data['products_options_name'] = $products_options_data['products_options_name'];
						
						$products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from products_attributes pa, products_options_values pov where pa.products_id = '" . $this->_data['master_id'] . "' and pa.options_id = '" . (int)$products_options_data['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "'");
						while ($products_options = tep_db_fetch_array($products_options_query)) {
							$this->_data['attributes_options'][] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
							if ($products_options['options_values_price'] != '0') {
								$this->_data['attributes_options'][sizeof($this->_data['attributes_options'])-1]['text'] .= ' (' . $products_options['price_prefix'] . $OSCOM_Currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($this->_data['tax_class_id'])) .') ';
							}
						}
					}
					if (is_string($this->_data['master_id']) && isset($OSCOM_ShoppingCart->contents[$this->_data['master_id']]['attributes'][$this->_data['products_options_id']])) {
					  $selected_attribute = $OSCOM_ShoppingCart->contents[$this->_data['master_id']]['attributes'][$this->_data['products_options_id']];
					} else {
					  $selected_attribute = false;
					}
				}				
			//under developement				
				
				$product_reviews_query = tep_db_query("select avg(reviews_rating) as rating from reviews where products_id = '" . $this->_data['master_id'] . "' and reviews_status = '1'");
				$product_reviews = tep_db_fetch_array($product_reviews_query);
				
                $this->_data['reviews_average_rating'] = round($product_reviews['rating']);
			}			
		}
	}

    public function getData($key = null) {
		
		if ( isset($this->_data[$key]) ) {
			
			return $this->_data[$key];
		}
		
		return $this->_data;
    }

    public function getID() {
		return $this->_data['id'];
    }
    public function getTitle() {
		return $this->_data['name'];
    }

    public function getDescription() {
		return $this->_data['description'];
    }

    public function hasModel() {
		return (isset($this->_data['model']) && !empty($this->_data['model']));
    }

    public function getModel() {
		return $this->_data['model'];
    }
    public function hasGtin() {
		return (isset($this->_data['gtin']) && !empty($this->_data['gtin']));
    }

    public function getGtin() {
		return $this->_data['gtin'];
    }	
    public function getPrice($with_special = false) {
		$OSCOM_Specials = new Specials();
		$OSCOM_Currencies = new currencies();
		if ( ($with_special === true) && ($new_price = $OSCOM_Specials->getPrice($this->_data['id'])) ) {
			$price = $OSCOM_Currencies->display_raw($new_price, tep_get_tax_rate($this->_data['tax_class_id']));
		} else {

			$price = $OSCOM_Currencies->display_raw($this->_data['price'], tep_get_tax_rate($this->_data['tax_class_id']));
        }
		return $price;
    }
	
    public function getPriceFormated($with_special = false) {
		$OSCOM_Specials = new Specials();
		$OSCOM_Currencies = new currencies();
		if ( ($with_special === true) && ($new_price = $OSCOM_Specials->getPrice($this->_data['id'])) ) {
			$price = '<s>' . $OSCOM_Currencies->display_price($this->_data['price'], tep_get_tax_rate($this->_data['tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $OSCOM_Currencies->display_price($new_price, tep_get_tax_rate($this->_data['tax_class_id'])) . '</span>';
		} else {

			$price = $OSCOM_Currencies->display_price($this->_data['price'], $this->_data['tax_class_id']);
        }
		
		return $price;
    }
	
    public function getQuantity() {
		return $this->_data['quantity'];
    }
	
	public function getWeight() {      
		return $this->_data['weight'];
	}	
	
	
    public function hasManufacturer() {
		return ( $this->_data['manufacturers_id'] > 0 );
    }

    public function getManufacturer() {
		
		$OSCOM_Manufacturer = new Manufacturer($this->_data['manufacturers_id']);

		return $OSCOM_Manufacturer->getTitle();
    }

    public function getManufacturerID() {
		return $this->_data['manufacturers_id'];
    }

    public function getCategoryID() {
		return $this->_data['category_id'];
    }

    public function getImages() {
		foreach ( $this->_data['images'] as $image ) {
		    return $this->_data['images'];
		}
    }

    public function hasImage() {
		$images = $this->_data['images'];
		foreach ( $images as $image ) {
			if ( $image['default_flag'] == '1' ) {
				return true;
			}
		}
    }

    public function getImage() {
      
		//$images = $this->_data['images'];
		//foreach ( $images as $image ) {
			if ( $this->_data['images']['default_flag'] == '1' ) {
				return $this->_data['images']['image'];
			}
		//}
    }

    public function getDateAvailable() {
		// HPDL
		return false; //$this->_data['date_available'];
    }

    public function getDateAdded() {
		return $this->_data['date_added'];
    }
	
    public function hasAttribute($code) {
		return isset($this->_data['attributes'][$code]);
    }

    public function getAttribute($code) {
		if ( !class_exists('osC_ProductAttributes_' . $code) ) {
			if ( file_exists(DIR_FS_CATALOG . 'includes/modules/product_attributes/' . basename($code) . '.php') ) {
				include(DIR_FS_CATALOG . 'includes/modules/product_attributes/' . basename($code) . '.php');
			}
		}

		if ( class_exists('osC_ProductAttributes_' . $code) ) {
			return call_user_func(array('osC_ProductAttributes_' . $code, 'getValue'), $this->_data['attributes'][$code]);
		}
    }	
	
	public function incrementCounter() {
		tep_db_query("update products_description set products_viewed = products_viewed+1 where products_id = '" . Products::getProductID($this->_data['id']) . "' and language_id = '" . (int)$languages_id . "'");
	}
    public function numberOfImages() {
		return count($this->_data['images']);
    }
}
?>