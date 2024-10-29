jQuery(document).ready( function(jQuery) {

	jQuery("#arrive").datepicker({
		dateFormat: 'dd/mm/yy',
		minDate: new Date(),
		maxDate: "+12m",
		onSelect: function(dateText, dateObj){
			var minDate = new Date(dateObj.selectedYear, dateObj.selectedMonth, dateObj.selectedDay ),
			year = dateObj.selectedYear - new Date().getFullYear(),
			$d=jQuery('#check_in_day'),
			$m=jQuery('#check_in_month');
			$d.val(dateObj.selectedDay.toString());
			$m.val((dateObj.selectedMonth+1+12*year).toString());

			minDate.setDate(minDate.getDate()+1);
			jQuery("#depart" ).datepicker("option", "minDate", minDate );
		}
	});

	jQuery("#depart").datepicker({
		dateFormat: 'dd/mm/yy',
		minDate: new Date(),
		maxDate: "+12m",
		onSelect: function(dateText, dateObj){
			var minDate = new Date(dateObj.selectedYear, dateObj.selectedMonth, dateObj.selectedDay ),
			year = dateObj.selectedYear - new Date().getFullYear(),
			$d=jQuery('#check_out_day'),
			$m=jQuery('#check_out_month');
			$d.val(dateObj.selectedDay.toString());
			$m.val((dateObj.selectedMonth+1+12*year).toString());

			minDate.setDate(minDate.getDate()-1);
			jQuery("#arrive" ).datepicker("option", "maxDate", minDate );

		}
	});

});