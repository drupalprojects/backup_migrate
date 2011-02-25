
Drupal.backup_migrate = {
  callbackURL : "",  
  autoAttach  : function() {
    if ($("#edit-save-settings").length && !$("#edit-save-settings").attr("checked")) {
      // Disable input and hide its description.
      $("div.backup-migrate-save-options").hide();
    }

    $("#edit-save-settings").bind("click", function() {
      if (!$("#edit-save-settings").attr("checked")) {
        $("div.backup-migrate-save-options").slideUp('slow');
      }
      else {
        // Save unchecked; enable input.
        $("div.backup-migrate-save-options").slideDown('slow');
      }
    });

    $('#edit-exclude-tables').after('<div class="description backup-migrate-checkbox-link"><a href="javascript:Drupal.backup_migrate.selectToCheckboxes(\''+ 'exclude_tables' +'\');">'+ Drupal.settings.backup_migrate.checkboxLinkText +'</a></div>');
    $('#edit-nodata-tables').after('<div class="description backup-migrate-checkbox-link"><a href="javascript:Drupal.backup_migrate.selectToCheckboxes(\''+ 'nodata_tables' +'\');">'+ Drupal.settings.backup_migrate.checkboxLinkText +'</a></div>');
  },

  processCheckboxes : function(ctxt) {
    $("input.backup-migrate-tables-checkbox", ctxt).each(function() {
      this.do_click = function() {
        if (this.checked) {
          $(this).parent().addClass('checked');
        }
        else {
          $(this).parent().removeClass('checked');
        }
      };
      $(this).bind("click", function() { this.do_click() });
      this.do_click();
    });
  },

  selectToCheckboxes : function(field) {
    var field_id = 'edit-'+ field.replace('_', '-');
    var $select = $('#'+ field_id);
    var $checkboxes = $('<div></div>').addClass('backup-migrate-tables-checkboxes');
    $('option', $select).each(function(i) {
      $checkboxes.append('<div class="form-item"><label class="option backup-migrate-table-select"><input type="checkbox" class="backup-migrate-tables-checkbox" id="edit-'+ field_id +'-'+ this.value +'" name="'+ field +'[]"'+ (this.selected ? 'checked="checked"' : '') +' value="'+ this.value +'"/>'+this.value+'</label></div>');
    });
    $select.parent().find('.backup-migrate-checkbox-link').remove();
    $select.before($checkboxes);
    $select.remove();
    Drupal.backup_migrate.processCheckboxes($checkboxes);
  }
}

// Global Killswitch
if (Drupal.jsEnabled) {
  $(document).ready(Drupal.backup_migrate.autoAttach);
}
