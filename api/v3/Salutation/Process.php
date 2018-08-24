<?php
use CRM_Salutations_ExtensionUtil as E;

/**
 * Salutation.Process API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_salutation_Process_spec(&$spec) {
  $spec['contactId']['api.required'] = 1;
  $spec['greetingString']['api.required'] = 1;
}

/**
 * Salutation.Process API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_salutation_Process($params) {
  if (array_key_exists('contactId', $params) &&
      array_key_exists('greetingString', $params)) {

    $greetingString = $params['greetingString'];
    $contactID = $params['contactId'];
    $contactDetails = civicrm_api3('Contact', 'getsingle', ['id' => "$contactID",]);

    CRM_Contact_BAO_Contact_Utils::processGreetingTemplate($greetingString, $contactDetails, $contactID, 'CRM_UpdateGreeting');

    if ($greetingString != $params['greetingString']) {
      $returnValues['greeting'] = $greetingString;
      return civicrm_api3_create_success($returnValues, $params, 'Salutation', 'Process');
    }
  }
  else {
    throw new API_Exception(/*errorMessage*/ 'Missing required params', /*errorCode*/ 1234);
  }
}
