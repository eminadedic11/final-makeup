var EmployeesService = {
  delete_employee: function(employee_id) {
      if (confirm("Do you want to delete employee with the id " + employee_id + "?")) {
          $.ajax({
              url: '../backend/rest/employee/delete/' + employee_id,
              type: 'DELETE',
              success: function(result) {
                  alert('Employee deleted successfully');
                  $('#employee-' + employee_id).closest('tr').remove();
              },
              error: function(xhr) {
                  alert('Failed to delete the employee: ' + xhr.responseText);
              }
          });
      }
  },
  edit_employee: function(employee_id, first_name, last_name, email) {
      $('#customer_id').val(employee_id);
      $('#first_name').val(first_name);
      $('#last_name').val(last_name);
      $('#email').val(email);


      $('#edit-employee-modal').modal('show');
  }
};
