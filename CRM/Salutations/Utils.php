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
  public static $greeting_types;
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
        'options' => ['limit' => 0],
      ])['values'];
      foreach ($salutation_types_raw as $salutation_type) {
        self::$salutation_types[$salutation_type['value']] = $salutation_type['label'];
      }
    }

    //Greeting Types
    // Note that this produces the opposite key-value association of salutation types
    // because that's what we need.
    if (empty(self::$greeting_types)) {
      $greeting_types_raw = civicrm_api3('OptionValue', 'get', [
        'return' => "label, value",
        'option_group_id.name' => 'salutation_type_options',
        'options' => ['limit' => 0],
      ])['values'];
      foreach ($greeting_types_raw as $greeting_type) {
        self::$greeting_types[$greeting_type['label']] = $greeting_type['value'];
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

  public static function getContactSalutations($contact_id) {
    $result = civicrm_api3('CustomValue', 'get', [
      'entity_id' => $contact_id,
      'entity_type' => 'Contact',
    ])['values'];
    return $result;
  }

  // Moved to the class so we can call it from sub-extensions.
  public static function createSalutation($type, $type_field_id, $contact_id, $salutation_value) {
    $contact_type = civicrm_api3('Contact', 'getvalue', [
      'return' => "contact_type",
      'id' => $contact_id,
    ]);
    //Token string
    $salutation_string = self::getDefaultSalutation($type, $contact_type, $salutation_value);

    //Process the tokenized string
    $processed_salutation = civicrm_api3('Salutation', 'process', [
      'contactId' => $contact_id,
      'greetingString' => $salutation_string['values'][0]['label']
    ]);

    //Create the saluation
    $salutation_type_value = $salutation_string['values'][0]['value'];
    $salutation = $processed_salutation['values']['greeting'];
    $contactSalutationCreate = civicrm_api3('CustomValue', 'create', [
      'entity_id' => $contact_id,
      "custom_salutations:salutation_type" => $salutation_value,
      "custom_salutations:salutation_$type" => $salutation_type_value,
      "custom_salutations:salutation" => "$salutation",
    ]);
  }

  private static function getDefaultSalutation($type, $contactType, $salutationValue) {
    $params = [
      'sequential' => 1,
      'return' => ["label", "id", "value"],
      'option_group_id.name' => "$type",
    ];
    // Defaults would ideally be a UI setting one day.
    $defaults['Individual'] = [
      'salutation_email_greeting' => 51,
      'salutation_postal_greeting' => 51,
      'salutation_addressee' => 55,
      '1' => 55,
      '2' => 51,
    ];
    $defaults['Organization'] = [
      'salutation_email_greeting' => 153,
      'salutation_postal_greeting' => 153,
      'salutation_addressee' => 152,
      '1' => 152,
      '2' => 153,
    ];
    $defaultSalutation = $defaults[$contactType][$salutationValue];
    if ($defaultSalutation) {
      $params['value'] = $defaultSalutation;
    }
    else {
      // Pull the default from the database.
      $contact_types = array('Individual' => 1, 'Household' => 2, 'Organization' => 3);
      $params['is_default'] = 1;
      $params['filter'] = $contact_types[$contactType];
    }
    $salutation_string = civicrm_api3('OptionValue', 'get', $params);
    return $salutation_string;
  }

  public static function updateSalutation($contact_id) {
    self::setConstantValues();
    // Get the existing salutation types for this contact.
    // This API call adds ~50% overhead, but a direct DAO call is no better.
    $custom_values = self::getContactSalutations($contact_id);

    $contactDetails = civicrm_api3('Contact', 'getsingle', ['id' => "$contact_id",]);
    // Iterate through the contact's salutations, calculate the updated values and update the database if necessary.
    foreach ($custom_values[self::$type_field_id] as $k => $salutation) {
      if (is_numeric($k)) {
        $greetingString = self::$salutation_types[$custom_values[self::$greeting_field_id][$k]];
        // Don't process customized greetings.
        if ($greetingString == 'Customized') {
          continue;
        }
        // We cache calculated greetings indexed by the greeting_field_id because many greetings will be identical.
        // processGreetingTemplate() is by far the most expensive call here, so this cuts processing time almost in
        // half when just two greetings that need more resolution are identical.
        if (isset($new_salutation[$contact_id][$custom_values[self::$greeting_field_id][$k]])) {
          $greetingString = $new_salutation[$contact_id][$custom_values[self::$greeting_field_id][$k]];
        }
        else {
          CRM_Contact_BAO_Contact_Utils::processGreetingTemplate($greetingString, $contactDetails, $contact_id, 'CRM_Salutations');
          $new_salutation[$contact_id][$custom_values[self::$greeting_field_id][$k]] = $greetingString;
        }
        // Update the greeting, but only if it's changed and not blank!
        if ($greetingString && $greetingString != $custom_values[self::$calculated_salutation_id][$k]) {
          $contactSalutationUpdate = civicrm_api3('CustomValue', 'create', [
            'entity_id' => $contact_id,
            "custom_salutations:salutation:$k" => $greetingString,
          ]);
        }
      }
    }
  }

}
