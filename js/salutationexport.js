CRM.$(function ($) {
  'use strict';
  insertExportSettings();
  setInitialValue();
  changeSettingListener();
});

function insertExportSettings() {
  var markup = '<div class="crm-accordion-wrapper crm-salutation_export collapsed"><div class="crm-accordion-header">Salutation Export Settings</div>';
  markup += '<div class="crm-accordion-body">';
  markup += '<input id="salutationExportOptions" name="salutation_export_options" placeholder="- select salutations to export -" />';
  markup += "<br /><div id='cidwarning'><strong>Salutations will not be exported unless you select \"Contact ID\" as one of the export fields below.</strong></div>";
  markup += "</div></div>";
  CRM.$(function ($) {
    $('[name=salutation_export_options]').crmEntityRef({
      entity: 'option_value',
      api: {
        params: {
          option_group_id: 'salutation_type_options'
        }
      },
      create: false,
      select: {
        multiple: true
      },
    });
  });
  CRM.$('#wizard-steps').after(markup);
}

function setInitialValue() {
  CRM.api3('Setting', 'getvalue', {
    "name": "salutationExport"
  }).done(function (result) {
    console.log(result.result);
    if (result.result) {
      //Set the checkbox to checked.
      options = result.result.split("\u0001");
      options = options.filter(function (element) {
        return element != '';
      })
      CRM.$("#salutationExportOptions").select2("val", options);
    }
  });
}

function changeSettingListener() {
  CRM.$('#salutationExportOptions').change(
    function () {
      settingValue = CRM.$('#salutationExportOptions').select2("val");
      CRM.api3('Setting', 'create', {"salutationExport": settingValue});
    });
}