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
    CRM_Core_Resources::singleton()->addScriptFile('com.jlacey.salutations', 'salutations.js');
  }
  if ($formName == 'CRM_Contact_Form_Contact' ||
      $formName == 'CRM_Contact_Form_Inline_CommunicationPreferences') {
    CRM_Core_Resources::singleton()->addScriptFile('com.jlacey.salutations', 'hide-core-greetings.js');
  }
}

/**
 * Implements hook_civicrm_pageRun().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_pageRun/
 */
function salutations_civicrm_pageRun( &$page ) {
  if (get_class($page) == 'CRM_Contact_Page_View_Summary') {
    CRM_Core_Resources::singleton()->addScriptFile('com.jlacey.salutations', 'hide-core-greetings.js');
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
      $filterCondition = array('greeting_type' => substr($salutation_field['name'], 11));
      //FIXME need to add contact type to filter condition
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
  $salutation_fields = civicrm_api3('CustomField', 'get', [
    'sequential' => 1,
    'return' => ["name", "label"],
    'custom_group_id' => "salutations",
    'html_type' => "Select",
    'option_group_id' => ['!=' => "salutation_type_options"],
    'is_active' => 1,
  ]);
  foreach($salutation_fields['values'] as $salutation_field) {
    $tokens['contact']['contact.' . strtolower($salutation_field['name'])] = $salutation_field['label'];
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
  $salutation_type_field = civicrm_api3('CustomField', 'getsingle', ['return' => ["id"],'name' => "salutation_type",]);
  $salutation_type_field_id = 'custom_' . $salutation_type_field['id'];

  //Set salutation field id
  $processed_salutation_field = civicrm_api3('CustomField', 'getsingle', ['return' => ["id"],'name' => "salutation",]);
  $processed_salutation_field_id = 'custom_' . $processed_salutation_field['id'];

  //Get salutation field options
  $salutation_fields = civicrm_api3('CustomField', 'get', [
    'sequential' => 1,
    'return' => ["name", "id"],
    'custom_group_id' => "salutations",
    'html_type' => "Select",
    'option_group_id' => ['!=' => "salutation_type_options"],
    'is_active' => 1,
  ]);
  //and set the different type options for look up
  foreach($salutation_fields['values'] as $salutation_field) {
    $salutations[$salutation_field['id']] = strtolower($salutation_field['name']);
  }

  //Processed the different type options for each contact
  foreach($cids as $cid) {
    foreach($salutations as $salutation_id => $salutation_type) {

      $processed_salutation = civicrm_api3('Contact', 'get', [
        'sequential' => 1,
        'return' => ["$processed_salutation_field_id"],
        'id' => $cid,
        "$salutation_type_field_id" => "$salutation_type",
      ]);

      $salutation["contact.$salutation_type"] = $processed_salutation['values'][0]["$processed_salutation_field_id"];
      $values[$cid] = empty($values[$cid]) ? $salutation : $values[$cid] + $salutation;

      //If the salutation fields is a core one, set the core greeting token
      if (in_array(substr($salutation_type,11), $core_greeting_types)) {
        $core_greeting = substr($salutation_type,11);
        $salutation["contact.$core_greeting"] = $processed_salutation['values'][0]["$processed_salutation_field_id"];
        $values[$cid] = empty($values[$cid]) ? $salutation : $values[$cid] + $salutation;
      }
    }
  }
}
