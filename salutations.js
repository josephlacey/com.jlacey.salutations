(function ($) {
  //Initial salutation processing
  CRM.api3('CustomField', 'getsingle', {
    "return": ["id"],
    "name": "salutation_type"
  }).done(function(salutationTypeField) {
    //Page load
    salutation_type(salutationTypeField.id);
    //When the salutation type changes
    CRM.$("select[id*='custom_" + salutationTypeField.id + "']").change(function() {
      salutation_type(salutationTypeField.id);
    });
  });
})(CRM.$);

/*
 * Salutation Type handling
 */
function salutation_type (fieldId){
  //Process the selected option
  //This requires the that value in the salutation type options 
  //match the custom field value of the corresponding option list
  if (selectedGreeting = CRM.$("select[id*='custom_" + fieldId + "'] option:selected").val()) {
    CRM.api3('CustomField', 'getsingle', {
      "return": ["id"],
      "name": selectedGreeting
    }).done(function(selectedGreetingField) {
      //Show salutation options
      CRM.$("tr[class*='custom_" + selectedGreetingField.id + "']").show();

      //Generate the selected option
      //Page Load
      process_salutation(CRM.$(this).children('option:selected').text());
      //When the salutation option changes
      CRM.$("select[id*='custom_" + selectedGreetingField.id + "']").change(function() {
        process_salutation(CRM.$(this).children('option:selected').text());
      });
    });
  }
  //Hide the salutation options for non-selected types
  CRM.$("select[id*='custom_" + fieldId + "'] option:not(:selected)").each(function() {
    if (CRM.$(this).val().length > 0) {
      CRM.api3('CustomField', 'getsingle', {
        "return": ["id"],
        "name": CRM.$(this).val()
      }).done(function(unselectedGreetingField) {
        CRM.$("tr[class*='custom_" + unselectedGreetingField.id + "']").hide();
      });
    }
  });
}

/*
 * Process the salutation option
 */
function process_salutation(greetingToken){
  //Selected the salutation field
  CRM.api3('CustomField', 'getsingle', {
    "return": ["id"],
    "name": "salutation"
  }).done(function(salutationField) {
    //For non-customized options,
    if (greetingToken != 'Customized') {
      //Set the salutation field read only
      CRM.$("input[id*='custom_" + salutationField.id + "']").prop('readonly', 'readonly');
      //Process the salutation tokens
      CRM.api3('Salutation', 'process', {
        "contactId": get_url_vars()['cid'],
        "greetingString": greetingToken,
      }).done(function(result) {
        //And the set the value
        CRM.$("input[id*='custom_" + salutationField.id + "']").val(result.values.greeting);
      });
    } else {
      //If customized is selected, remove the read only restriction from field
      CRM.$("input[id*='custom_" + salutationField.id + "']").removeProp('readonly');
    }
  });
}

/*
 * Helper function to get URL variables
 *
 * Needed to get the contact id for proper token processing
 */
function get_url_vars() {
  var vars = {};
  var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
    vars[key] = value;
  });
  return vars;
}
