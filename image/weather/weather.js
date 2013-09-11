function weather(){
	city = document.getElementById("WeatherCity").value;
	$.ajax({
			url: "image/weather/city/",
			type: 'POST',
			data: 'city='+city,
			dataType: "html",
			success: function(data)
			{$("#weather").html(data);}
	});
}
function weather_current(city){
	$.ajax({
			url: "image/weather/city/",
			type: 'POST',
			data: 'city='+city,
			dataType: "html",
			success: function(data)
				{$("#weather").html(data);}
	});
}