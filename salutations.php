<?php

require_once 'salutations.civix.php';
use CRM_Salutations_ExtensionUtil as E;
use CRM_Salutations_Utils as U;

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
    $customGroupName = civicrm_api3('CustomGroup', 'getvalue', [
      'return' => "name",
      'id' => $form->_groupID,
    ]);
    if ($customGroupName == 'salutations') {
      //Pass in contact id to javascript handling
      CRM_Core_Resources::singleton()->addVars('salutations', ['cid' => $form->_tableID]);
      //Include JS for UI
      CRM_Core_Resources::singleton()->addScriptFile('com.jlacey.salutations', 'js/salutations.js');
      //Include CSS for custom styling
      CRM_Core_Resources::singleton()->addStyleFile('com.jlacey.salutations', 'css/salutations.css');
    }
  }
  if ($formName == 'CRM_Contact_Form_Contact' ||
      $formName == 'CRM_Contact_Form_Inline_CommunicationPreferences') {
    //Hide the core addressee and greetings
    CRM_Core_Resources::singleton()->addScriptFile('com.jlacey.salutations', 'js/hide-core-greetings.js');
  }
  // Inject the Salutation Export JS.
  if ($formName == 'CRM_Export_Form_Map') {
    CRM_Core_Resources::singleton()->addScriptFile('com.jlacey.salutations', 'js/salutationexport.js');
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
  if ($formName == 'CRM_Contact_Form_CustomData') {
    $contact_id = $form->get('entityID');
    $salutation_type = "custom_" . civicrm_api3('CustomField', 'getvalue', [
      'return' => "id",
      'name' => "salutation_type",
    ]);

    // Ensure that we're actually evalutating salutations and not another
    // multi-record custom group.
    $needle = "{$salutation_type}_";
    foreach (array_keys($fields) as $fieldKey) {
      if (strpos($fieldKey, $needle) === 0) {
        $thisIsSalutations = true;
      }
    }
    if (!$thisIsSalutations) {
      return;
    }
    // Make sure this is a new field; updates are allowed to update themselves.
    // Array keys in $fields look like 'custom_6_567', where '567' is the custom
    // value ID, which unfortunately there's no easier way to get.
    $fieldsKeys = array_keys($fields);
    $customValueKey = preg_grep('/' . $salutation_type . '_/', $fieldsKeys);
    preg_match('/' . $salutation_type . '_((\d)+)/', array_pop($customValueKey), $matches);
    $customValueId = $matches[1];
    //Set existing salutations
    $existing_salutations = civicrm_api3('CustomValue', 'get', [
      'sequential' => 1,
      'return' => "$salutation_type",
      'entity_id' => $contact_id,
    ]);
    // Remove the current record's ID from $existingSalutations
    if ($existing_salutations['values'][0][$customValueId]) {
      unset($existing_salutations['values'][0][$customValueId]);
      unset($existing_salutations['values'][0]['latest']);
    }
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
 * Implements hook_civicrm_post().
 *
 * This updates a contact's salutations after record update
 *
 */
function salutations_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  if ($op == 'create' || $op == 'edit') {
    if ($objectName == 'Individual' || $objectName == 'Organization') {
      $action = ($op == 'create' ? 1 : 2);
      salutation_process_helper($objectId, $action);
    }
  }
}

function salutation_process_helper($contact_id, $action) {
  $core_greeting_types = array("salutation_email_greeting", "salutation_postal_greeting", "salutation_addressee");

  \CRM_Salutations_Utils::setConstantValues();
  //If creating a new contact, only create the three core greetings by default
  if ($action == 1) {
    foreach ($core_greeting_types as $core_greeting_type) {
      salutation_create('postal_greeting', U::$greeting_field_id, $contact_id, $core_greeting_type);
    }
  } 
  // Update existing non-customized salutations
  else {
    salutation_update($contact_id);
  }
}

/*
 * Update the salutation value
 *
 * Helper function for salutation updates after contact updates
 */
function salutation_update($contact_id) {
  // Get the existing salutation types for this contact.
  // This API call adds ~50% overhead, but a direct DAO call is no better.
  $custom_values = civicrm_api3('CustomValue', 'get', [
    'entity_id' => $contact_id,
    'entity_type' => 'Contact',
  ])['values'];

  $contactDetails = civicrm_api3('Contact', 'getsingle', ['id' => "$contact_id",]);
  // Iterate through the contact's salutations, calculate the updated values and update the database if necessary.
  foreach ($custom_values[U::$type_field_id] as $k => $salutation) {
    if (is_numeric($k)) {
      $greetingString = U::$salutation_types[$custom_values[U::$greeting_field_id][$k]];
      // We cache calculated greetings indexed by the greeting_field_id because many greetings will be identical.
      // processGreetingTemplate() is by far the most expensive call here, so this cuts processing time almost in
      // half when just two greetings that need more resolution are identical.
      if (!$new_salutation[$contact_id][$custom_values[U::$greeting_field_id][$k]]) {
        CRM_Contact_BAO_Contact_Utils::processGreetingTemplate($greetingString, $contactDetails, $contact_id, 'CRM_Salutations');
        $new_salutation[$contact_id][$custom_values[U::$greeting_field_id][$k]] = $greetingString;
      }
      else {
        $greetingString = $new_salutation[$contact_id][$custom_values[U::$greeting_field_id][$k]];
      }
      // Update the greeting, but only if it's changed!
      if ($greetingString != $custom_values[U::$calculated_salutation_id][$k]) {
        $contactSalutationUpdate = civicrm_api3('CustomValue', 'create', [
          'entity_id' => $contact_id,
          "custom_salutations:salutation:$k" => $greetingString,
        ]);
      }
    }
  }
}

/*
 * Create the salutation value
 *
 * Helper function for salutation updates after contact updates
 */
function salutation_create($type, $type_field_id, $contact_id, $salutation_value) {
  $contact_types = array('Individual' => 1, 'Household' => 2, 'Organization' => 3);
  $contact_type = civicrm_api3('Contact', 'getvalue', [
    'return' => "contact_type",
    'id' => $contact_id,
  ]);
  //Token string
  $salutation_string = civicrm_api3('OptionValue', 'get', [
    'sequential' => 1,
    'return' => ["label","id"],
    'option_group_id.name' => "$type",
    'is_default' => 1, 
    'filter' => $contact_types["$contact_type"],
  ]);

  //Process the tokenized string
  $processed_salutation = civicrm_api3('Salutation', 'process', [
    'contactId' => $contact_id,
    'greetingString' => $salutation_string['values'][0]['label']
  ]);

  //Create the saluation
  $salutation_type_value = civicrm_api3('OptionValue', 'getvalue', [
    'return' => "value",
    'id' => $salutation_string['values'][0]['id'],
  ]);
  $salutation = $processed_salutation['values']['greeting'];
  $contactSalutationCreate = civicrm_api3('CustomValue', 'create', [
    'entity_id' => $contact_id,
    "custom_salutations:salutation_type" => $salutation_value,
    "custom_salutations:salutation_$type" => $salutation_type_value,
    "custom_salutations:salutation" => "$salutation",
  ]);
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
    $core_greeting_types = array("postal_greeting");
    
    //Grab custom salutation fields
    $salutation_fields =  civicrm_api3('CustomField', 'get', [
      'return' => "name",
      'custom_group_id' => "salutations",
    ]);

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
        $filterCondition['contact_type'] = civicrm_api3('Contact', 'getvalue', [
          'return' => "contact_type",
          'id' => $contact_id,
        ]);
      }
      $filterCondition['greeting_type'] = 'postal_greeting';
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
  $salutations = civicrm_api3('OptionValue', 'get', ['return' => ["value", "label"],'option_group_id.name' => "salutation_type_options",]);
  foreach($salutations['values'] as $salutation) {
    $tokens['contact']["contact." . $salutation['value']] = $salutation['label'] . " Salutation";
  }
}

/**
 * Implements hook_civicrm_tokenValues().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_tokenValues/
 */
function salutations_civicrm_tokenValues(&$values, $cids, $job = null, $tokens = array(), $context = null) {
  $core_greeting_types = array("email_greeting", "postal_greeting", "addressee");
  if ($context == 'CRM_Salutations' || $context == 'CRM_Reltoken') {
    return;
  }
  U::setConstantValues();

  // This could get moved into CRM_Salutations_Utils at some point.
  static $salutations;
  if (!$salutations) {
    $salutations = civicrm_api3('OptionValue', 'get', [
      'return' => "value",
      'option_group_id.name' => "salutation_type_options",
      'is_active' => 1,
      'limit' => 0,
    ]);
  }

  //Processed the different type options for each contact
  foreach ($cids as $cid) {
    $custom_values = civicrm_api3('CustomValue', 'get', [
      'entity_id' => $cid,
      'entity_type' => 'Contact',
    ])['values'];
    // Remove non-numeric return values, they're not real values and can foul results.
    foreach ($custom_values[U::$type_field_id] as $key => $dontcare) {
      if (!is_numeric($key)) {
        unset($custom_values[U::$type_field_id][$key]);
        unset($custom_values[U::$greeting_field_id][$key]);
        unset($custom_values[U::$calculated_salutation_id][$key]);
      }
    }

    $salutation = [];
    foreach ($salutations['values'] as $salutation_type) {

      $k = array_search($salutation_type['value'], $custom_values[U::$type_field_id]);

      if ($k) {
        $salutation["contact." . $salutation_type['value']] = $custom_values[U::$calculated_salutation_id][$key];
        $values[$cid] = empty($values[$cid]) ? $salutation : $values[$cid] + $salutation;

        //If the salutation fields has a corollary core one, set the core greeting token
        foreach ($core_greeting_types as $core_greeting) {
          if (FALSE !== (stristr($core_greeting, $salutation_type['value']))) {
            $values[$cid]["$core_greeting"] = $custom_values[U::$calculated_salutation_id][$key];
            $values[$cid]["$core_greeting" . "_display"] = $custom_values[U::$calculated_salutation_id][$key];
            if (isset($values[$cid]["$core_greeting" . "_custom"])) {
              $values[$cid]["$core_greeting" . "_custom"] = $custom_values[U::$calculated_salutation_id][$key];
            }
          }
        }
      }
    }
    if (!empty($salutation)) {
      $values[$cid] = empty($values[$cid]) ? $salutation : $values[$cid] + $salutation;
    }
  }
}

/**
 * Implements hook_civicrm_export().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_export
 */
function salutations_civicrm_export(&$exportTempTable, &$headerRows, &$sqlColumns, &$exportMode, &$componentTable, &$ids) {
  // Are we exporting salutations?
  $result = civicrm_api3('Setting', 'getvalue', array(
    'name' => "salutationExport",
  ));
  if (!$result) {
    return;
  }
  // Make sure we actually have a "civicrm_primary_id" field to join on.
  if (!array_key_exists('civicrm_primary_id', $sqlColumns)) {
    return;
  }
  // Get details on the salutations
  $salutationsToExport = civicrm_api3('OptionValue', 'get', [
    'sequential' => 1,
    'option_group_id' => "salutation_type_options",
    'value' => ['IN' => CRM_Utils_Array::explodePadded($result)],
  ])['values'];

  $customTableName = civicrm_api3('CustomGroup', 'getvalue', [
    'return' => "table_name",
    'name' => "salutations",
  ]);

  // Alter the temp table.
  $alterTable = "ALTER TABLE $exportTempTable ";
  foreach ($salutationsToExport as $salutation) {
    $alterTable .= "ADD COLUMN sal_{$salutation['value']} VARCHAR(512),";
  }
  $alterTable = rtrim($alterTable, ',');

  // Update the temp table.
  $sql = "UPDATE " . $exportTempTable . " a ";
  foreach ($salutationsToExport as $salutation) {
    $tableAlias = "table_" . $salutation['value'];
    $sql .= "LEFT JOIN $customTableName $tableAlias ON a.civicrm_primary_id = {$tableAlias}.entity_id AND {$tableAlias}.salutation_type = '{$salutation['value']}' ";
  }
  $sql .= "SET";
  foreach ($salutationsToExport as $salutation) {
    $tableAlias = "table_" . $salutation['value'];
    $sql .= " sal_{$salutation['value']} = $tableAlias.salutation,";
  }
  $sql = rtrim($sql, ',');

  // Update the export arrays.
  foreach ($salutationsToExport as $salutation) {
    $headerRows[] = $salutation['label'];
    $sqlColumns["sal_{$salutation['value']}"] = "sal_{$salutation['value']} varchar(512)";
  }

  CRM_Core_DAO::singleValueQuery($alterTable);
  CRM_Core_DAO::singleValueQuery($sql);
}
