(function ($) {
  $('table.crm-multifield-selector.crm-ajax-table').on('crmLoad', function() {
    //Remove inline editing for salutation fields.
    //Inline editing breaks the workflow.
    $('.crm-editable').each(function(){
      $(this).removeClass('crm-editable');
    });
    //Remove View and Copy actions from rows.
    $(this).find('.action-item').each(function(){
      if ($(this).text() == 'View' ||
        $(this).text() == 'Copy' ) {
        $(this).remove();
      }
    });
  });
})(CRM.$);
