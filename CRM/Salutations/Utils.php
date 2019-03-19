<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utils
 *
 * @author jon
 */
class CRM_Salutations_Utils {
  //Salutation Type Field
  public static $type_field_id;
  public static $salutation_types;
  public static $greeting_field_id;
  public static $calculated_salutation_id;
  public static $custom_group_id;
  public static $custom_group_table;

  /*
   *  set the values of various "pseudoconstants" like the custom field IDs for salutations.
   */
  function setConstantValues() {

    if (!(self::$type_field_id && self::$greeting_field_id && self::$calculated_salutation_id)) {
      $result = civicrm_api3('CustomField', 'get', [
        'sequential' => 1,
        'return' => ["id", "name"],
        'name' => ['IN' => ["salutation_type", "salutation_postal_greeting", "salutation"]],
      ])['values'];
      foreach ($result as $customField) {
        $fieldId[$customField['name']] = $customField['id'];
      }
      self::$type_field_id = $fieldId['salutation_type'];
      self::$greeting_field_id = $fieldId['salutation_postal_greeting'];
      self::$calculated_salutation_id = $fieldId['salutation'];
    }
    //Salutation Types
    if (empty(self::$salutation_types)) {
      $salutation_types_raw = civicrm_api3('OptionValue', 'get', [
        'return' => "label, value",
        'option_group_id.name' => 'postal_greeting',
        'limit' => 0,
      ])['values'];
      foreach ($salutation_types_raw as $salutation_type) {
        self::$salutation_types[$salutation_type['value']] = $salutation_type['label'];
      }
    }

    if (!self::$custom_group_id) {
      self::$custom_group_id = civicrm_api3('CustomGroup', 'getvalue', [
        'return' => "id",
        'name' => "salutations",
      ]);
      self::$custom_group_table = "civicrm_value_salutations_" . self::$custom_group_id;
    }
  }

}
