jQuery.each(jQuery('textarea[data-autoresize]'), function() {
    var offset = this.offsetHeight - this.clientHeight;
 
    var resizeTextarea = function(el) {
        jQuery(el).css('height', 'auto').css('height', el.scrollHeight + offset);
    };
    jQuery(this).on('keyup input', function() { resizeTextarea(this); }).removeAttr('data-autoresize');
});
// function formatNumber(nStr, decSeperate, groupSeperate) {
//   nStr += '';
//   x = nStr.split(decSeperate);
//   x1 = x[0];
//   x2 = x.length > 1 ? '.' + x[1] : '';
//   var rgx = /(\d+)(\d{3})/;
//   while (rgx.test(x1)) {
//       x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
//   }
//   return '$' + (x1 + x2).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
// }

function formatNumber(nStr, decSeperate, groupSeperate) {
  nStr += '';
  x = nStr.split(decSeperate);
  x1 = x[0];
  x2 = x.length > 1 ? '.' + x[1] : '';
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
      x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
  }
  return x1 + x2;
}


function format_curency(nStr){
  nStr = nStr.replace(/,/g, "")
  document.getElementById('numFormatResult').value="";

  nStr += '';
  x = nStr.split('.');
  x1 = x[0];
  x2 = x.length > 1 ? '.' + x[1] : '';
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1))
  x1 = x1.replace(rgx, '$1' + ',' + '$2');
  document.getElementById('numFormatResult').value = x1 + x2;
  document.getElementById('result').value = nStr;
}

function format_curency_general(nStr,param_1,param_2){
  nStr = nStr.replace(/,/g, "")
  document.getElementById(param_1).value="";

  nStr += '';
  x = nStr.split('.');
  x1 = x[0];
  x2 = x.length > 1 ? '.' + x[1] : '';
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1))
  x1 = x1.replace(rgx, '$1' + ',' + '$2');
  document.getElementById(param_1).value = x1 + x2;
  document.getElementById(param_2).value = nStr;
}

jQuery(document).ready(function(){
	$( ".datepicker" ).datepicker({
    		changeMonth: true,
				changeYear: true,
				yearRange: "1970:2050",
				dateFormat: 'dd/mm/yy'

    	}	
    );

  $( ".datepicker_day" ).datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "1970:2050",
        dateFormat: 'dd'

      } 
    );
});
jQuery(document).ready(function(){
	var currentDate = new Date();
	$( "#datetimepicker" ).datepicker({
    		changeMonth: true,
			changeYear: true,
			yearRange: "1970:2050",
			dateFormat: 'dd/mm/yy',	
    	}	
    ).datepicker('setDate', currentDate);
    //$( "#datetimepicker" ).datepicker("setDate", "+0");
});

jQuery(document).ready(function(){
	var currentDate = new Date();
	$( "#datetimepicker_special" ).datepicker({
			maxDate: '0',
    		changeMonth: true,
			changeYear: true,
			yearRange: "1970:2050",
			dateFormat: 'dd/mm/yy',	
    	}	
    ).datepicker('setDate', currentDate);

});

jQuery(document).ready(function(){
	$( ".datepicker_special" ).datepicker({
    		changeMonth: true,
				changeYear: true,
				yearRange: "1970:2050",
				dateFormat: 'mm/yy'

    	}	
    );

  $("form").submit(function(){
      unsaved = false;
  });

});

function myFunction(txt) {
    var x = document.getElementById("snackbar");
    $('#snackbar').html(txt);
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
}


$(window).bind('beforeunload', function() {
    if(unsaved == true){
        return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
    }
});

// Validate Date
function validationDate(dateString) {
  if(dateString == "") return true;
  if(!/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(dateString))
      return false;

  var parts = dateString.split("/");
  var day = parseInt(parts[0], 10);
  var month = parseInt(parts[1], 10);
  var year = parseInt(parts[2], 10);

  if(year < 1000 || year > 3000 || month == 0 || month > 12)
      return false;

  var monthLength = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];

  if(year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
      monthLength[1] = 29;

  return day > 0 && day <= monthLength[month - 1];   
}

function toDataUrl(url, callback) {
  var xhr = new XMLHttpRequest();
  xhr.onload = function() {
      var reader = new FileReader();
      reader.onloadend = function() {
          callback(reader.result);
      }
      reader.readAsDataURL(xhr.response);
  };
  xhr.open('GET', url);
  xhr.responseType = 'blob';
  xhr.send();
}

function autonumericInteger(id) {
  new AutoNumeric.multiple(id, {
      // currencySymbol : ' €',
      decimalCharacter : '.',
      digitGroupSeparator : ',',
      decimalPlaces : 0,
      // maximumValue : 1000,
  });
}


function formatNumber(nStr, decSeperate, groupSeperate) {
  nStr += '';
  x = nStr.split(decSeperate);
  x1 = x[0];
  x2 = x.length > 1 ? '.' + x[1] : '';
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
      x1 = x1.replace(rgx, '$1' + groupSeperate + '$2');
  }
  return x1 + x2;
}