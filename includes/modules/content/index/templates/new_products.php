<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2016 osCommerce

  Released under the GNU General Public License
*/

$content = '<div class="col-sm-'.$content_width.' new-products">';
$content .= '	<h3>' . sprintf(MODULE_CONTENT_NEW_PRODUCTS_HEADING, strftime('%B')) . '</h3>';

	foreach ( $data as $product ) {				
		$content .= '<div class="col-sm-'. $product_width.'">';
		$content .= '	<div class="thumbnail equal-height">';			
		$content .= '		Name: <a href="' .  tep_href_link('product_info.php', 'products_id=' . $product['master_id']) . '">' . $product['name'] . '</a><br/> Rating: ' . tep_draw_stars($product['reviews_average_rating']);
		$content .= '		<a href="' .  tep_href_link('product_info.php', 'products_id=' . $product['master_id']) . '">' . $OSCOM_Image->show($product['display_image'], $product['name']) . '</a>';
		$content .= '		<p> Price: ' . $product['display_price'] .  '</p>';
		if( isset($product['model']) && !empty($product['model']) ){
		$content .= '		<p> Model: ' . $product['model'] .  '</p>';
		}
		if( isset($product['gtin']) && !empty($product['gtin']) ){
		$content .= '		<p> gTin: ' . $product['gtin'] .  '</p>';
        }		
		if( $product['manufacturers_id'] > 0 ){
		$content .= '		<p> Manufacturer: ' . $product['display_manufacturer'] .  '</p>';
		}
		if( $product['attributes'] > 0 ){
		 $content .= '<strong>' . $product['products_options_name'] . ':' . '</strong><br />' . tep_draw_pull_down_menu('id[' . $product['products_options_id'] . ']', $product['attributes_options'], $selected_attribute, 'style="width: 200px;"') . '<br />';
		}
		$content .= '	</div>';
		$content .= '</div>';       
	}	
  
$content .= '</div>';
echo  $content;

