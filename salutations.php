<?php

require_once 'salutations.civix.php';
use CRM_Salutations_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function salutations_civicrm_config(&$config) {
  _salutations_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function salutations_civicrm_xmlMenu(&$files) {
  _salutations_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function salutations_civicrm_install() {
  _salutations_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function salutations_civicrm_postInstall() {
  _salutations_civix_civicrm_postInstall();

  //Set the salutation table name for greeting migrations
  $salutation_custom_group = civicrm_api3('CustomGroup', 'getsingle', ['return' => ["table_name"],'name' => "Salutations",]);
  $salutation_table = $salutation_custom_group['table_name'];

  //Migrate Email Greetings
  $email_greeting_migration_sql = "INSERT INTO $salutation_table (entity_id, salutation_type, salutation_postal_greeting, salutation)
                                        SELECT id, 'salutation_email_greeting', email_greeting_id, email_greeting_display FROM civicrm_contact";
  $email_greeting_migration = CRM_Core_DAO::executeQuery($email_greeting_migration_sql);

  //Migration Postal Greetings
  $postal_greeting_migration_sql = "INSERT INTO $salutation_table (entity_id, salutation_type, salutation_postal_greeting, salutation)
                                         SELECT id, 'salutation_postal_greeting', postal_greeting_id, postal_greeting_display FROM civicrm_contact";
  $postal_greeting_migration = CRM_Core_DAO::executeQuery($postal_greeting_migration_sql);

  //Migrating Addressees
  $addressee_migration_sql = "INSERT INTO $salutation_table (entity_id, salutation_type, salutation_addressee, salutation)
                                   SELECT id, 'salutation_addressee', addressee_id, addressee_display FROM civicrm_contact";
  $addressee_migration = CRM_Core_DAO::executeQuery($addressee_migration_sql);
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function salutations_civicrm_uninstall() {
  _salutations_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function salutations_civicrm_enable() {
  _salutations_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function salutations_civicrm_disable() {
  _salutations_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function salutations_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _salutations_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function salutations_civicrm_managed(&$entities) {
  _salutations_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function salutations_civicrm_caseTypes(&$caseTypes) {
  _salutations_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function salutations_civicrm_angularModules(&$angularModules) {
  _salutations_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function salutations_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _salutations_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function salutations_civicrm_entityTypes(&$entityTypes) {
  _salutations_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @param string $formName
 * @param CRM_Core_Form $form
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_buildForm/
 */
function salutations_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Contact_Form_CustomData') {
    CRM_Core_Resources::singleton()->addScriptFile('com.jlacey.salutations', 'js/salutations.js');
  }
  if ($formName == 'CRM_Contact_Form_Contact' ||
      $formName == 'CRM_Contact_Form_Inline_CommunicationPreferences') {
    CRM_Core_Resources::singleton()->addScriptFile('com.jlacey.salutations', 'js/hide-core-greetings.js');
  }
}

/**
 * Implements hook_civicrm_validateForm().
 *
 * @param string $formName
 * @param array $fields
 * @param array $files
 * @param CRM_Core_Form $form
 * @param array $errors
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_validateForm/
 */
function salutations_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  if($formName == 'CRM_Contact_Form_CustomData') {
    $contact_id = $form->get('entityID');
    $salutation_type = "custom_" . civicrm_api3('CustomField', 'getvalue', ['return' => "id",'name' => "salutation_type",]);
    $existing_salutations = civicrm_api3('CustomValue', 'get', ['sequential' => 1,'return' => ["$salutation_type"],'entity_id' => $contact_id,]);
    foreach($fields as $fieldKey => $fieldValue) {
      if (stristr($fieldKey, $salutation_type)) {
        if (in_array($fieldValue, $existing_salutations['values'][0])) {
          $errors["$fieldKey"] = 'A salutation of this type already exists.';
        }
      }
    }
  }
}

/**
 * Implements hook_civicrm_postProcess().
 *
 * This updates a contact's salutations after record update
 *
 * @param string $formName
 * @param CRM_Core_Form $form
 */
function salutations_civicrm_postProcess($formName, &$form) {
  if($formName == 'CRM_Contact_Form_Contact' ||
     $formName == 'CRM_Contact_Form_Inline_ContactName') {

    //TODO there's probably a more efficient way to do this
    $contact_id = $form->get('cid');
    $salutations_group_id = civicrm_api3('CustomGroup', 'getvalue', ['return' => "id",'name' => "salutations",]);
    $salutations_group = "civicrm_value_salutations_" . $salutations_group_id ."_id";
    $salutation_type_id = civicrm_api3('CustomField', 'getvalue', ['return' => "id",'name' => "salutation_type",]);
    $salutation_greeting_id = civicrm_api3('CustomField', 'getvalue', ['return' => "id",'name' => "salutation_postal_greeting",]);
    $salutation_addressee_id = civicrm_api3('CustomField', 'getvalue', ['return' => "id",'name' => "salutation_addressee",]);
    $salutation_id = civicrm_api3('CustomField', 'getvalue', ['return' => "id",'name' => "salutation",]);
    $salutations = civicrm_api3('OptionValue', 'get', ['return' => ["name"],'option_group_id.name' => "salutation_type_options",]);

    foreach($salutations['values'] as $salutation) {
      $salutation_name = $salutation['name'];
      if ($salutation_name == "Addressee") {
        $salutation_option_selected = civicrm_api3('Contact', 'get', [
          'sequential' => 1,
          'return' => ["custom_$salutation_addressee_id"],
          'id' => $contact_id,
          "custom_$salutation_type_id" => "$salutation_name",
        ]);
        if ($salutation_option_selected['values'][0]["custom_$salutation_addressee_id"] != 4 ) {
          //TODO these functions aren't correctly normalized
          $greeting_string = salutation_greeting_string('addressee', $salutation_option_selected['values'][0]["custom_$salutation_addressee_id"]);
          $greeting = civicrm_api3('Salutation', 'process', ['contactId' => $contact_id, 'greetingString' => $greeting_string]);
          salutation_greeting_update($contact_id, $salutation_option_selected['values'][0][$salutations_group], $greeting['values']['greeting']);
        }
      } else {
        $salutation_option_selected = civicrm_api3('Contact', 'get', [
          'sequential' => 1,
          'return' => ["custom_$salutation_greeting_id"],
          'id' => $contact_id,
          "custom_$salutation_type_id" => "$salutation_name",
        ]);
        if ($salutation_option_selected['values'][0]["custom_$salutation_greeting_id"] != 4 ) {
          //TODO these functions aren't correctly normalized
          $greeting_string = salutation_greeting_string('postal_greeting', $salutation_option_selected['values'][0]["custom_$salutation_greeting_id"]);
          $greeting = civicrm_api3('Salutation', 'process', ['contactId' => $contact_id, 'greetingString' => $greeting_string]);
          salutation_greeting_update($contact_id, $salutation_option_selected['values'][0][$salutations_group], $greeting['values']['greeting']);
        }
      }
    }
  }
}

/*
 * Retrieve the tokenized greeting string
 *
 * Helper function for salutation updates after contact updates
 */
function salutation_greeting_string($type, $option) {
  $greeting_string = civicrm_api3('OptionValue', 'get', [
    'sequential' => 1,
    'return' => ["label"],
    'option_group_id.name' => "$type",
    'value' => $option,
  ]);

  return $greeting_string['values'][0]['label'];

}

/*
 * Update the salutation value
 *
 * Helper function for salutation updates after contact updates
 */
function salutation_greeting_update($contact_id, $salutation_id, $salutation) {
  $contactSalutationUpdate = civicrm_api3('CustomValue', 'create', array(
    'entity_id' => $contact_id,
    "custom_salutations:Salutation:$salutation_id" => "$salutation",
  ));
}

/**
 * Implements hook_civicrm_pageRun().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_pageRun/
 */
function salutations_civicrm_pageRun( &$page ) {
  if (get_class($page) == 'CRM_Contact_Page_View_Summary') {
    CRM_Core_Resources::singleton()->addScriptFile('com.jlacey.salutations', 'js/hide-core-greetings.js');
  }
  if (get_class($page) == 'CRM_Contact_Page_View_CustomData') {
    if (CRM_Core_BAO_CustomGroup::getTitle($page->_groupId) == 'Salutations') {
      CRM_Core_Resources::singleton()->addScriptFile('com.jlacey.salutations', 'js/ui-clean-up.js');
    }
  }
}

/**
 * Implements hook_civicrm_fieldOptions().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_fieldOptions/
 */
function salutations_civicrm_fieldOptions($entity, $field, &$options, $params) {
  if ($entity == 'Contact') {
    //Declare core greeting type to include options
    $core_greeting_types = array("postal_greeting", "addressee");
    
    //Grab custom salutation fields
    $salutation_fields =  civicrm_api3('CustomField', 'get', ['return' => ["name"], 'custom_group_id' => "salutations",]);

    //Check if custom field
    $field_id = (int) substr($field, 7);

    //If it's a custom field and one of the core salutation fields, set the core greeting options
    if ($field_id > 0 && 
        array_key_exists($field_id, $salutation_fields['values']) &&
        in_array(substr($salutation_fields['values'][$field_id]['name'],11), $core_greeting_types)) {
      $salutation_field = $salutation_fields['values'][$field_id];
      $filterCondition = array();
      if (isset($_GET['entityID'])) {
        $contact_id = (int) $_GET['entityID'];
        $filterCondition['contact_type'] = civicrm_api3('Contact', 'getvalue', ['return' => "contact_type",'id' => $contact_id,]);
      }
      $filterCondition['greeting_type'] = substr($salutation_field['name'], 11);
      $options = CRM_Core_PseudoConstant::greeting($filterCondition);
    }
  }
}

/**
 * Implements hook_civicrm_tokens().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_tokens/
 */
function salutations_civicrm_tokens( &$tokens ) {
  $salutations = civicrm_api3('OptionValue', 'get', ['return' => ["name", "label"],'option_group_id.name' => "salutation_type_options",]);
  foreach($salutations['values'] as $salutation) {
    $tokens['contact']['contact.salutation_' . strtolower($salutation['name'])] = $salutation['label'] . " Salutation";
  }
}

/**
 * Implements hook_civicrm_tokenValues().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_tokenValues/
 */
function salutations_civicrm_tokenValues(&$values, $cids, $job = null, $tokens = array(), $context = null) {
  $core_greeting_types = array("email_greeting", "postal_greeting", "addressee");

  //Set salutation type field id
  $salutation_type_field = civicrm_api3('CustomField', 'getvalue', ['return' => "id",'name' => "salutation_type",]);
  $salutation_type_field_id = 'custom_' . $salutation_type_field;

  //Set salutation field id
  $processed_salutation_field = civicrm_api3('CustomField', 'getvalue', ['return' => "id",'name' => "salutation",]);
  $processed_salutation_field_id = 'custom_' . $processed_salutation_field;

  $salutations = civicrm_api3('OptionValue', 'get', ['return' => ["name"],'option_group_id.name' => "salutation_type_options",]);

  //Processed the different type options for each contact
  foreach($cids as $cid) {
    foreach($salutations['values'] as $salutation_id => $salutation_type) {

      $salutation_name = strtolower($salutation_type['name']);

      $processed_salutation = civicrm_api3('Contact', 'get', [
        'sequential' => 1,
        'return' => ["$processed_salutation_field_id"],
        'id' => $cid,
        "$salutation_type_field_id" => "$salutation_name",
      ]);

      $salutation["contact.salutation_$salutation_name"] = $processed_salutation['values'][0]["$processed_salutation_field_id"];
      $values[$cid] = empty($values[$cid]) ? $salutation : $values[$cid] + $salutation;

      //If the salutation fields has a corollary core one, set the core greeting token
      foreach ($core_greeting_types as $core_greeting) {
        if (FALSE !== (stristr($core_greeting, $salutation_name))) {
          $values[$cid]["$core_greeting"] = $processed_salutation['values'][0]["$processed_salutation_field_id"];
          $values[$cid]["$core_greeting" . "_display"] = $processed_salutation['values'][0]["$processed_salutation_field_id"];
          if (isset($values[$cid]["$core_greeting". "_custom"])) {
            $values[$cid]["$core_greeting" . "_custom"] = $processed_salutation['values'][0]["$processed_salutation_field_id"];
          }
        }
      }
    }
    $values[$cid] = empty($values[$cid]) ? $salutation : $values[$cid] + $salutation;
  }
}
