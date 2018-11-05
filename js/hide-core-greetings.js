(function ($) {
  //TODO We're making a lot of assumptions here.
  //If someone's move these around or 
  //is using the Contact Summary screen editor, 
  //these might not work

  //Contact summary screen
  CRM.$('#crm-container .contact_details #crm-communication-pref-content .crm-inline-block-content .crm-summary-row:nth-child(7)').hide();
  CRM.$('#crm-container .contact_details #crm-communication-pref-content .crm-inline-block-content .crm-summary-row:nth-child(8)').hide();
  CRM.$('#crm-container .contact_details #crm-communication-pref-content .crm-inline-block-content .crm-summary-row:nth-child(9)').hide();

  //Form contact edit form
  CRM.$('.crm-container form#Contact #commPrefs table tbody tr:nth-child(3)').hide();
  CRM.$('.crm-container form#Contact #commPrefs table tbody tr:nth-child(4)').hide();

  //Inline comm prefs edit form
  CRM.$('form#CommunicationPreferences .crm-inline-edit-form .crm-clear .crm-summary-row:nth-child(7)').hide();
  CRM.$('form#CommunicationPreferences .crm-inline-edit-form .crm-clear .crm-summary-row:nth-child(8)').hide();
  CRM.$('form#CommunicationPreferences .crm-inline-edit-form .crm-clear .crm-summary-row:nth-child(9)').hide();

})(CRM.$);
