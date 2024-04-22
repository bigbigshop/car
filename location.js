window.onload = function() {
	document.getElementById('myCar').src = ImgSrc;
	document.getElementById('myCar').alt = plate;
	document.getElementById('plate').innerText = plate1_line +"\n"+ plate2_line;
	var url = '';
	//url = 'https://freeipapi.com/api/json/'; 
	//data.countryName
	
	url = 'https://api.vvhan.com/api/ipinfo'; 
	//data.info.prov
	
	//url = 'https://ipapi.co/json/';
	//data.country

	$.get(url, function(data) {
	  console.log(data);
	  if (data.info.prov == '香港') {
		document.getElementById('myPhoneNumber').innerHTML = '<i class="fas fa-phone"></i> 立即致電車主';
		document.getElementById('sendMessage').innerHTML = '<i class="fab fa-whatsapp"></i> 立即WhatsApp車主';			
		document.getElementById('myPhoneNumber').href = "tel:+" + areaCode + telNumber_part1 + telNumber_part2;
		document.querySelector("#sendMessage").setAttribute("onclick", "sendWhatsAppMessage()"); 
	  } else {
		document.getElementById('myPhoneNumber').innerHTML = '<i class="fas fa-phone"></i> 立即致电车主(国内)';
		document.getElementById('myPhoneNumber').href = "tel:+" + areaCode_CN + telNumber_part1_CN + telNumber_part2_CN;
		document.getElementById('sendMessage').innerHTML = '<i class="fa-regular fa-message"></i> 发送信息';
		var smsUrl = 'sms:' + telNumber_part1_CN + telNumber_part2_CN + '?body=' + encodeURIComponent('你好，请挪车');
		document.querySelector("#sendMessage").setAttribute("href", smsUrl);
	  }
	}).fail(function() {
	  console.log('Failed to retrieve URL');
	});
}