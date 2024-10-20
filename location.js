window.onload = function() {
    document.getElementById('myCar').src = ImgSrc;
    document.getElementById('myCar').alt = plate;
    document.getElementById('plate').innerText = plate1_line + "\n" + plate2_line;
    var url = 'https://ipapi.co/json/';
    
	if (autoSwitch == '1'){
		$.ajax({
			url: url,
			method: 'GET',
			timeout: 5000, // Timeout set to 5000 milliseconds (5 seconds)
			success: function(data) {
				console.log(data);			
				if (data.country == 'CN') {
					displayCN();
				} else {
					displayHK();       
				}
			},
			error: function(jqXHR, textStatus) {
				if (textStatus === 'timeout') {
					console.log('Request timed out.');
				} else {
					console.log('Failed to retrieve URL');
				}
				// Fallback for when the request fails
				displayCN();
			}
		});
	}else{
		if (region == 'big6'){
			displayCN();			
		}else if (region == 'hk'){
			displayHK();
		}
	}
}

function displayCN(){
	document.getElementById('myPhoneNumber').innerHTML = '<i class="fas fa-phone"></i> 立即致电车主(国内)';
	document.getElementById('myPhoneNumber').href = "tel:+" + areaCode_CN + telNumber_part1_CN + telNumber_part2_CN;
	document.getElementById('sendMessage').innerHTML = '<i class="fa-regular fa-message"></i> 发送信息';
	var smsUrl = 'sms:' + telNumber_part1_CN + telNumber_part2_CN + '?body=' + encodeURIComponent('你好，请挪车');
	document.querySelector("#sendMessage").setAttribute("href", smsUrl);
}

function displayHK(){
	document.getElementById('myPhoneNumber').innerHTML = '<i class="fas fa-phone"></i> 立即致電車主';
	document.getElementById('sendMessage').innerHTML = '<i class="fab fa-whatsapp"></i> 立即WhatsApp車主';            
	document.getElementById('myPhoneNumber').href = "tel:+" + areaCode + telNumber_part1 + telNumber_part2;
	document.querySelector("#sendMessage").setAttribute("onclick", "sendWhatsAppMessage()");
}
