$(function () {
    var th_count = $($("#trnstable thead tr:first").find('th')).length;
	/*var thcount = $("#trnstable thead tr th").length;
	console.log(thcount);*/
    var pageTable = $('#trnstable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": true,
	  "columnDefs":[{"orderable":false,"targets":(th_count-1)}],
	  "scrollX":false,
      "order": [[ 0, "desc" ]]
    });
  });