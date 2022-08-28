/*var barcode = document.getElementById('barcode');
barcode.onkeyup = function(){
  var http = null;
  if(window.XMLHttpRequest){
    //if user using IE > 8 or chrome or firefox, safari, opera
    http = new XMLHttpRequest();
  }else{
    // if user using IE < 8!
    http = new ActiveXobject('Microsoft.XMLHTTP');
  }

    http.onreadystatechange = function(){
        if(http.readyState == 4 && http.status == 200){
          document.getElementById('result').innerHTML = http.responseText;
        }
    };

  http.open('POST', 'barcode_scan.php', true);
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  http.send('scan='+barcode.value);
};
*/
