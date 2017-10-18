<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  if (!isset($process)) $process = false;
?>

  <div class="contentText">

<?php
  if (ACCOUNT_GENDER == 'true') {
    if (isset($gender)) {
      $male = ($gender == 'm') ? true : false;
      $female = ($gender == 'f') ? true : false;
    } else {
      $male = false;
      $female = false;
    }
?>

    <div class="form-group">
      <label class="control-label col-sm-3"><?php echo ENTRY_GENDER; ?></label>
      <div class="col-sm-9">
        <label class="radio-inline">
          <?php echo tep_draw_radio_field('gender', 'm', $male, 'aria-describedby="atGender"') . ' ' . MALE; ?>
        </label>
        <label class="radio-inline">
          <?php echo tep_draw_radio_field('gender', 'f', $female) . ' ' . FEMALE; ?>
        </label>
        <?php if (tep_not_null(ENTRY_GENDER_TEXT)) echo '<span id="atGender" class="help-block">' . ENTRY_GENDER_TEXT . '</span>'; ?>
      </div>
    </div>

<?php
  }
?>

    <div class="form-group">
      <label for="inputFirstName" class="control-label col-sm-3"><?php echo ENTRY_FIRST_NAME; ?></label>
      <div class="col-sm-9">
        <?php
        echo tep_draw_input_field('firstname', NULL, 'id="inputFirstName" placeholder="' . ENTRY_FIRST_NAME_TEXT . '"');
        ?>
      </div>
    </div>
    <div class="form-group">
      <label for="inputLastName" class="control-label col-sm-3"><?php echo ENTRY_LAST_NAME; ?></label>
      <div class="col-sm-9">
        <?php
        echo tep_draw_input_field('lastname', NULL, 'id="inputLastName" placeholder="' . ENTRY_LAST_NAME_TEXT . '"');
        ?>
      </div>
    </div>

<?php
  if (ACCOUNT_COMPANY == 'true') {
?>

    <div class="form-group">
      <label for="inputCompany" class="control-label col-sm-3"><?php echo ENTRY_COMPANY; ?></label>
      <div class="col-sm-9">
        <?php
        echo tep_draw_input_field('company', NULL, 'id="inputCompany" placeholder="' . ENTRY_COMPANY_TEXT . '"');
        ?>
      </div>
    </div>

<?php
  }
?>

    <div class="form-group">
      <label for="inputStreet" class="control-label col-sm-3"><?php echo ENTRY_STREET_ADDRESS; ?></label>
      <div class="col-sm-9">
        <?php
        echo tep_draw_input_field('street_address', NULL, 'id="inputStreet" placeholder="' . ENTRY_STREET_ADDRESS_TEXT . '"');
        ?>
      </div>
    </div>

<?php
  if (ACCOUNT_SUBURB == 'true') {
?>

    <div class="form-group">
      <label for="inputSuburb" class="control-label col-sm-3"><?php echo ENTRY_SUBURB; ?></label>
      <div class="col-sm-9">
        <?php
        echo tep_draw_input_field('suburb', NULL, 'id="inputSuburb" placeholder="' . ENTRY_SUBURB_TEXT . '"');
        ?>
      </div>
    </div>

<?php
  }
?>

    <div class="form-group">
      <label for="inputCity" class="control-label col-sm-3"><?php echo ENTRY_CITY; ?></label>
      <div class="col-sm-9">        
        <?php
        echo tep_draw_input_field('city', NULL, 'id="inputCity" placeholder="' . ENTRY_CITY_TEXT . '"');
        ?>
      </div>
    </div>
    <div class="form-group">
      <label for="inputZip" class="control-label col-sm-3"><?php echo ENTRY_POST_CODE; ?></label>
      <div class="col-sm-9">
        <?php
        echo tep_draw_input_field('postcode', NULL, 'id="inputZip" placeholder="' . ENTRY_POST_CODE_TEXT . '"');
        ?>
      </div>
    </div>

<?php
  if (ACCOUNT_STATE == 'true') {
?>

    <div class="form-group">
      <label for="inputState" class="control-label col-sm-3"><?php echo ENTRY_STATE; ?></label>
      <div class="col-sm-9">
        <?php
        if ($process == true) {
          if ($entry_state_has_zones == true) {
            $zones_array = array();
            $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
            while ($zones_values = tep_db_fetch_array($zones_query)) {
              $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
            }
            echo tep_draw_pull_down_menu('state', $zones_array, 0, 'id="inputState" aria-describedby="atState"');
            if (tep_not_null(ENTRY_STATE_TEXT)) echo '<span id="atState" class="help-block">' . ENTRY_STATE_TEXT . '</span>';
          } else {
            echo tep_draw_input_field('state', NULL, 'id="inputState" placeholder="' . ENTRY_STATE_TEXT . '"');
          }
        } else {
          echo tep_draw_input_field('state', NULL, 'id="inputState" placeholder="' . ENTRY_STATE_TEXT . '"');
        }
        ?>
      </div>
    </div>

<?php
  }
?>
    <div class="form-group">
      <label for="inputCountry" class="control-label col-sm-3"><?php echo ENTRY_COUNTRY; ?></label>
      <div class="col-sm-9">
        <?php
        echo tep_get_country_list('country', NULL, 'aria-describedby="atCountry" id="inputCountry"');
        if (tep_not_null(ENTRY_COUNTRY_TEXT)) echo '<span id="atCountry" class="help-block">' . ENTRY_COUNTRY_TEXT . '</span>';
        ?>
      </div>
    </div>
</div>
