jQuery(document).ready(function () {
  
        jQuery('[data-toggle="tooltip"]').tooltip();
     

    jQuery('.min-max-range').on('input', function() {
        var value = parseInt(jQuery(this).val());
        var min = 0;
        var max = 10;
        
        if (value < min) {
          jQuery(this).val(min);
        } else if (value > max) {
          jQuery(this).val(max);
        }
      });

    jQuery('.week-value-field').on('input', function() {
        var value = parseInt(jQuery(this).val());
        var min = 27;
        var max = 52;
        
        if (value < min) {
            jQuery(this).val(min);
        } else if (value > max) {
            jQuery(this).val(max);
        }
        });


    jQuery('#team-members-table').DataTable({
        columnDefs: [
            {
                target: 7,
                visible: false,
                searchable: true,
            },
        ],
        info: false,
        "lengthChange": false,
        "ordering": false,
        "paging": false,
    });

    var lb_currentDate = moment();
    var lb_currentWeek = lb_currentDate.isoWeek();
    var lb_currentMonth = lb_currentDate.month() + 1;
    var lb_prevWeek = lb_currentWeek - 1;
    var leaderboard = jQuery('#team-members-leaderboard').DataTable({
    columnDefs: [
        {
            target: 3,
            visible: false,
            searchable: true,
        },
        {
            searchable: false,
            orderable: false,
            targets: 0,
        },
       {
       targets: 6,
       orderable: true,
       render: function (data, type, row) {
        if (type === "type" || type === 'sort') {
          var value = jQuery('<div>').html(data).find('span').text();
          if (value === 'NA') {
            return 0; 
          } else {
            return parseFloat(value.replace(/%/, ""));
          }
        } else {
          return data;
        }
      }
       },
       {
        targets: 5,
        orderable: true,
        render: function (data, type, row) {
         if (type === "type" || type === 'sort') {
           var value = jQuery('<div>').html(data).find('span').text();
           if (value === 'NA') {
             return 0; 
           } else {
             return parseFloat(value.replace(/%/, ""));
           }
         } else {
           return data;
         }
       }
        },
            
        ],
     order: [[3, 'desc'], [6, 'desc'], [5, 'desc'], [1, 'asc']],
    info: false,
    pageLength: 100,
    lengthChange: false,
    });
    jQuery('#team-members-leaderboard thead th').off('click');
    leaderboard.on('order.dt search.dt', function () {
        let i = 1;

        leaderboard.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
        this.data(i++);
        });
    }).draw();
    leaderboard.column(3).search(lb_prevWeek).draw();


   
    var pr_lb_currentDate = moment();
    var pr_lb_currentMonth = pr_lb_currentDate.month() + 1;
    var personality_leaderboard = jQuery('#team-members-personality-leaderboard').DataTable({
        columnDefs: [
            {
                target: 3,
                visible: false,
                searchable: true,
            },
            {
                searchable: false,
                orderable: false,
                targets: 0,
            },
           {
           targets: 6,
           orderable: true,
           render: function (data, type, row) {
            if (type === "type" || type === 'sort') {
              var value = jQuery('<div>').html(data).find('span').text();
              if (value === 'NA') {
                return 0; 
              } else {
                return parseFloat(value.replace(/%/, ""));
              }
            } else {
              return data;
            }
          }
           },
           {
            targets: 7,
            orderable: true,
            render: function (data, type, row) {
             if (type === "type" || type === 'sort') {
               var value = jQuery('<div>').html(data).find('span').text();
               if (value === 'NA') {
                 return 0; 
               } else {
                 return parseFloat(value.replace(/%/, ""));
               }
             } else {
               return data;
             }
           }
            },
                
            ],
        order: [[7, 'desc'], [6, 'desc'], [1, 'asc']],
        info: false,
        pageLength: 100,
        lengthChange: false,
        });
        jQuery('#team-members-personality-leaderboard thead th').off('click');
        personality_leaderboard.on('order.dt search.dt', function () {
            let i = 1;
    
            personality_leaderboard.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
            this.data(i++);
            });
        }).draw();
        personality_leaderboard.column(3).search(pr_lb_currentMonth).draw();



    var evaluate_tab = jQuery('#team-members-evaluate-table').DataTable({
        "columnDefs": [
            { 
                width: "60%", 
                targets: 2
             }
        ],        
        info: false,
        "paging": false,
        "lengthChange": false,
        "ordering": false,
    });
        evaluate_tab.column(1).search(lb_prevWeek).draw();



    var personality_evaluate_tab = jQuery('#team-members-personality-evaluate-table').DataTable({
        info: false,
        "paging": false,
        "lengthChange": false,
        "ordering": false,
    });
        personality_evaluate_tab.column(1).search(lb_currentMonth).draw();


    // Add dropdown filter for Status column
  var statusFilter = jQuery('<select id="status-filter"><option value="">All</option><option value="Active">Active</option><option value="Inactive">Inactive</option></select>');
  jQuery('#team-members-table_filter').prepend(statusFilter);

  // Apply filter on change
  var team_table = jQuery('#team-members-table').DataTable();
  statusFilter.on('change', function() {
    
      var status = jQuery(this).val();
      if (status == 'Active') {
        
        team_table.column(7).search('^Active$', true, false).draw();
        } else if (status == 'Inactive') {
            team_table.column(7).search('Inactive').draw();
        } else {
            team_table.column(7).search('').draw();
        }
        console.log(status);
  });


  var weekFilter = jQuery('<label>Week<select id="week-filter"><option value="">Select Week</option></select></label>');
  jQuery('.table-custom-filters').append(weekFilter);
  jQuery('.evaluate-table-filters').append(weekFilter);


    // Add dropdown filter for weeks column in leaderboard
    var currentDate = moment();
    var currentWeek = currentDate.isoWeek();
    var previousWeeks = Array.from({ length: currentWeek - 26}, (_, i) => i + 27);
    previousWeeks.forEach(function (week) {
    var weekLabel = "Week " + week;

            var listItem = jQuery("<option>").text(weekLabel).val(week);
                if (week === currentWeek - 1) {
                    listItem.attr("selected", "");
                }
            jQuery("#week-filter").append(listItem);
            
    });

    // Add dropdown filter for months column in personality evaluate
    var monthFilter = jQuery('<label>Month<select id="month-filter"><option value="">Select Month</option></select></label>');
    jQuery('.personality-evaluate-table-filters').append(monthFilter);

    var currentMonth = currentDate.month() + 1; 
    var previousMonths = Array.from({ length: currentMonth - 1 }, (_, i) => i + 2);
    
    previousMonths.forEach(function (month) {
        var monthName = moment().month(month - 1).format('MMMM'); 
        var listItem = jQuery("<option>").text(monthName).val(month);
    
        if (month === currentMonth) {
            listItem.attr("selected", "");
        }
    
        jQuery("#month-filter").append(listItem);
    });
    
  
    // Apply filter on change week and role category
    var table = jQuery('#team-members-leaderboard').DataTable();
    var evaluate_table = jQuery('#team-members-evaluate-table').DataTable();
    weekFilter.find("#week-filter").on('change', function() {
        var week_status = jQuery(this).val();
        table.column(3).search('\\b' + week_status + '\\b', true, false).draw();
        evaluate_table.column(1).search('\\b' + week_status + '\\b', true, false).draw();

        var weekLabel = "Week " + week_status;
        var startOfWeek = moment().isoWeek(week_status).startOf('isoWeek');
        var endOfWeek = moment().isoWeek(week_status).endOf('isoWeek');
     
        var startDate = startOfWeek.format('DD-MM-YYYY');
        var endDate = endOfWeek.format('DD-MM-YYYY');
        
        var weekRangeLabel =  '(' + startDate + ' - ' + endDate + ')';
        var weekRangeText = jQuery('<p class="week-range-text">').text(weekRangeLabel);
      
        jQuery('.table-custom-filters').find('.week-range-text').remove();
        jQuery('.table-custom-filters').append(weekRangeText);

        jQuery('.evaluate-table-filters').find('.week-range-text').remove();
        jQuery('.evaluate-table-filters').append(weekRangeText);
    });

      // Apply filter on change months
      var monthFilter = jQuery('#month-filter');
      var personality_evaluate_table = jQuery('#team-members-personality-evaluate-table').DataTable();
      var personality_leaderboard = jQuery('#team-members-personality-leaderboard').DataTable();
      monthFilter.on('change', function() {
          var month_status = jQuery(this).val();
          personality_evaluate_table.column(1).search('\\b' + month_status + '\\b', true, false).draw();
          personality_leaderboard.column(3).search('\\b' + month_status + '\\b', true, false).draw();

          var startOfMonth = moment().month(month_status - 1).startOf('month');
          var endOfMonth = moment().month(month_status - 1).endOf('month');
          var startDate = startOfMonth.format('DD-MM-YYYY');
          var endDate = endOfMonth.format('DD-MM-YYYY');
          var monthRangeLabel = '(' + startDate + ' - ' + endDate + ')';
          var monthRangeText = jQuery('<p class="month-range-text">').text(monthRangeLabel);
          jQuery('.personality-evaluate-table-filters').find('.month-range-text').remove();
          jQuery('.personality-evaluate-table-filters').append(monthRangeText);
      });


    var roleFilter = jQuery('<label>Role Category<select id="role-filter"><option><option value="" selected>All</option>Unit Head</option><option>QA</option><option>QA Automation</option><option>Development</option><option>Dev Ops</option><option>GIS</option>s<option>Project Management</option><option>Implementation</option><option>SAP</option><option>Design</option><option>Product Management</option></select></label>');
    //jQuery('.personality-evaluate-table-filters').append(roleFilter);
    jQuery('.table-custom-filters').append(roleFilter);

    var m_roleFilter = jQuery('<label>Role Category<select id="m-role-filter"><option><option value="" selected>All</option>Unit Head</option><option>QA</option><option>QA Automation</option><option>Development</option><option>Dev Ops</option><option>GIS</option>s<option>Project Management</option><option>Implementation</option><option>SAP</option><option>Design</option><option>Product Management</option></select></label>');
    jQuery('.personality-evaluate-table-filters').append(m_roleFilter);

    roleFilter.find("#role-filter").on('change', function() {
        var role_status = jQuery(this).val();
        if(role_status == ""){
            table.column(2).search('').draw();
        }
        else{
            table.column(2).search('^' + role_status + '$', true, false).draw();
        }
        
    });

    m_roleFilter.find("#m-role-filter").on('change', function() {
        var role_status = jQuery(this).val();
        if(role_status == ""){
            
            personality_leaderboard.column(2).search('').draw();
        }
        else{
          
            personality_leaderboard.column(2).search('^' + role_status + '$', true, false).draw();
        }
        
    });


// reset table filter on View team members
    var resetFilterBtn = jQuery('<button id="reset-filter-btn">Reset Filter</button>');
    jQuery('#team-members-table_filter').prepend(resetFilterBtn);
    jQuery("#reset-filter-btn").click(function(){
        jQuery("#team-members-table").DataTable().search("").draw();
        document.getElementById('status-filter').value="";
        table.column(7).search('').draw();
    });

    function toggleEvaluateButton() {
        const rowCount = evaluate_table.rows({ search: 'applied' }).count();
        
        const evaluateButton = jQuery('.edit-evaluation');
    
        if (rowCount > 0) {
            evaluateButton.prop('disabled', false);
        } else {
            evaluateButton.prop('disabled', true);
        }
    }

    evaluate_table.on('draw', function () {
        toggleEvaluateButton();
    });

    //message for evaluation enable on friday
        var currentWeek = currentDate.isoWeek();
        var startOfWeek = moment().isoWeek(currentWeek).day(1);
        var endOfWeek = moment().isoWeek(currentWeek).day(5); 
        var startDate = startOfWeek.format('DD-MM-YYYY');
        var endDate = endOfWeek.format('DD-MM-YYYY');
        // Create the week range label
        var weekRangeLabel =  '(' + startDate + ' - ' + endDate + ')';
        jQuery('.evaluation-message').text("Evaluation for Week " + currentWeek + " "+ weekRangeLabel +" will open on Friday ("+ endDate +")");

        //show prev week range of working days with filters on page load
        var prevWeek = currentDate.isoWeek() - 1;
        var prevstartOfWeek = moment().isoWeek(prevWeek).day(1);
        var prevendOfWeek = moment().isoWeek(prevWeek).day(5); 
        var prevstartDate = prevstartOfWeek.format('DD-MM-YYYY');
        var prevendDate = prevendOfWeek.format('DD-MM-YYYY');
        var prevweekRangeLabel =  '(' + prevstartDate + ' - ' + prevendDate + ')';
        var prevweekRangeText = jQuery('<p class="week-range-text">').text(prevweekRangeLabel);
        jQuery('.table-custom-filters').append(prevweekRangeText);
        jQuery('.evaluate-table-filters').append(prevweekRangeText);

    // Function to show/hide fields based on Team Lead selection
    function toggleFieldsVisibility() {
        var isLead = jQuery( '#team_member_islead' ).val();
        var usernameField = jQuery( '#username_field' );
        var passwordField = jQuery( '#password_field' );
        var leadingPercentageField = jQuery( '#leading_percentage_field' );
        var leadingEmailField = jQuery( '#leading_email_field' );

        if ( isLead === 'Yes' ) {
            usernameField.show();
            passwordField.show();
            leadingPercentageField.show();
            leadingEmailField.show();
        } else {
            usernameField.hide();
            passwordField.hide();
            leadingPercentageField.hide();
            leadingEmailField.hide();
        }
    }


        toggleFieldsVisibility();
        jQuery('#team_member_islead' ).on( 'change', function() {
            toggleFieldsVisibility();
        });

        jQuery('#team_member_reporting').select2({
            val: null,
            placeholder: 'Select an employee',
            width: '30%'
        });

});



jQuery('#week-filter').prop('disabled', true);
jQuery('#week-filter').prop('disabled', false);

jQuery('body').on('click', '.edit-evaluation', function(e) {

    jQuery('.work-rating-value').toggle();
    jQuery('.work-rating-value-field').toggle();

    jQuery('.work-rating-comment').toggle();
    jQuery('.work-rating-comment-field').toggle();
    
    jQuery('.team-management-value').toggle();
    jQuery('.team-management-value-field').toggle();
   
    jQuery('.work-management-comment').toggle();
    jQuery('.work-management-comment-field').toggle();

    jQuery('.integrity-value').toggle();
    jQuery('.integrity-value-field').toggle();
    jQuery('.integrity-comment').toggle();
    jQuery('.integrity-comment-field').toggle();

    jQuery('.respect-value').toggle();
    jQuery('.respect-value-field').toggle();
    jQuery('.respect-comment').toggle();
    jQuery('.respect-comment-field').toggle();

    jQuery('.reliability-value').toggle();
    jQuery('.reliability-value-field').toggle();
    jQuery('.reliability-comment').toggle();
    jQuery('.reliability-comment-field').toggle();

    jQuery('.innovation-value').toggle();
    jQuery('.innovation-value-field').toggle();
    jQuery('.innovation-comment').toggle();
    jQuery('.innovation-comment-field').toggle();

    jQuery('.drive-value').toggle();
    jQuery('.drive-value-field').toggle();
    jQuery('.drive-comment').toggle();
    jQuery('.drive-comment-field').toggle();

    // jQuery('.week-value').toggle();
    // jQuery('.week-value-field').toggle();
    // jQuery('.week_col').toggle();


    jQuery('.save-evaluation').toggle();
    jQuery(this).text(function(i, v){
        return v === 'Cancel' ? 'Evaluate' : 'Cancel'
     });

     jQuery('#week-filter').prop('disabled', !jQuery('#week-filter').prop('disabled'));
    
e.preventDefault();
});
  
//updating personality scores on runtime
function updatePersonalityScore(rowId) {
    var integrityValue = parseInt(jQuery('#team_member_integrity_' + rowId).val());
    var respectValue = parseInt(jQuery('#team_member_respect_' + rowId).val());
    var reliabilityValue = parseInt(jQuery('#team_member_reliability_' + rowId).val());
    var innovationValue = parseInt(jQuery('#team_member_innovation_' + rowId).val());
    var driveValue = parseInt(jQuery('#team_member_drive_' + rowId).val());

    var total = integrityValue + respectValue + reliabilityValue + innovationValue + driveValue;
    var personalityScore = (total / 50);


    jQuery('.personality-score[data-row="' + rowId + '"]').text(personalityScore);
    jQuery('.personality_overall-field[data-row="' + rowId + '"]').val(personalityScore);
  }

  // Call the updatePersonalityScore function on keydown for each input field
  jQuery('.min-max-range').on('input', function() {
    var rowId = jQuery(this).data('row');
    updatePersonalityScore(rowId);
  });
  
  

 // Button click event
jQuery('#team-member-name-option').change(function() {

        jQuery('#team-member-name-field').toggle();
        

        jQuery.ajax({
            url: '/teams/wp-admin/admin-ajax.php', 
            type: 'POST',
            data: {
                action: 'fetch_webhr_api_data' 
            },
            success: function(response) {
                var selectOptions = '';
         // Access and use FullName, Email, and WebHR ID separately
         response.forEach(function(item) {
            var fullName = item[0]; 
            var designation = item[3]
             // Append the FullName as an option
             selectOptions += '<option value="' + fullName + '">' + fullName + ' - (' + designation +')</option>';
            
        });
         
        jQuery('#team-member-name').html(selectOptions);
     
        jQuery('#team-member-name').change(function() {
            var selectedOption = jQuery(this).val(); // Get the selected value
           

          // Find the selected option in the response array
                var selectedItem = response.find(function(item) {
                    return item[0] === selectedOption;
                });

                if (selectedItem) {
                    var selectedName = selectedItem[0];
                    var selectedEmail = selectedItem[1]; // Get the email of the selected option
                    var selectedWebHRID = selectedItem[2]; // Get the WebHR ID of the selected option
                    
                    // Populate the email and WebHR ID into separate text fields
                    jQuery('#title').val(selectedName);
                    jQuery('#team-member-name-input').val(selectedName);
                    jQuery('#title-prompt-text').html('');
                    jQuery('#team_member_email').val(selectedEmail);
                    jQuery('#team_member_webhr_id').val(selectedWebHRID);
                   
                }
        });

            },
            error: function(xhr, status, error) {

                console.log(error);
            }
        });
             jQuery('#team-member-name').select2({
                val: null,
                placeholder: 'Select an employee',
                width: '50%'
            });
            
        
  });


  jQuery('body').on('click', '.webhr_leaves', function(e) {
  
  
    jQuery.ajax({
        url: '/teams/wp-admin/admin-ajax.php', 
        type: 'POST',
        data: {
            action: 'fetch_webhr_api_leaves_data' 
        },
        success: function(response) {
            console.log(response);
            var dialog = jQuery('<div>').html("Leaves data has been updated successfully.");

            // Open the dialog with slide-up animation
            dialog.dialog({
            modal: true,
            resizable: false,
            width: 500,
            show: {
                effect: 'fade',
                duration: 500
            },
            hide: {
                effect: "fade",
             duration: 1000
            },
            close: function() {
                jQuery(this).dialog('destroy').remove();
                location.reload();
              }
            });

            // Automatically close the dialog after 3 seconds
            setTimeout(function() {
                dialog.dialog('close');
              }, 3000);
             
        },
        error: function(xhr, status, error) {

            console.log(error);
        },

    });
    
    
  });

jQuery('body').on('click', '.jira_logs', function(e) {
    e.preventDefault();
    jQuery.ajax({
        url: '/teams/wp-admin/admin-ajax.php', 
        type: 'POST',
        data: {
            action: 'update_new_jira' 
        },
        success: function(response) {
            console.log(response);
             // Display success message popup
             var dialog = jQuery('<div>').html("Jira Logs has been updated successfully.");
                // Open the dialog with slide-up animation
                dialog.dialog({
                modal: true,
                resizable: false,
                width: 500,
                show: {
                    effect: 'fade',
                    duration: 500
                },
                hide: {
                    effect: "fade",
                 duration: 1000
                },
                close: function() {
                    jQuery(this).dialog('destroy').remove();
                  }
                });
 
             // Automatically close the dialog after 3 seconds
             setTimeout(function() {
                dialog.dialog('close');
              }, 3000);
        },
        error: function(xhr, status, error) {

            console.log(error);
        },
    });
  
});

jQuery('#public_holidays').datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true,
    multidate: true ,
    autoclose: false
});
jQuery('#clear_dates').on('click', function() {
    jQuery('#public_holidays').datepicker('setDate', null);
});

jQuery('body').on('click', '.delete-holiday', function(e) {
    e.preventDefault();
    var holiday_week = jQuery(this).attr("data-holiday-id");
    console.log(holiday_week);
    jQuery.ajax({
        url: '/teams/wp-admin/admin-ajax.php', 
        type: 'POST',
        data: {
            action: 'delete_holiday_callback',
            holiday_id: holiday_week 
        },
        success: function(response) {
        jQuery('#successModal').modal('show');
       console.log(response);
       setTimeout(function() {
     
            window.location.reload();
    }, 2000);
        },
    });
  
});