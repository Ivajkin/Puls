window.addEvent('domready', function () {
    jgetData(function (dData) {

	dData.forEach(function(car) {
		
		
		
		window.addEvent('domready', function() {
	dData.forEach(function(car) {
		$j("tbody#list").append('<tr class="explistrow0&#32;top">' +
										'<td width="100px">' +
											'<div class="photo top">' +
												'<a href="more.html">' +
													'<span></span>' +
													'<img src="' + car.image_url + '" />' +
												'</a>' +
											'</div>' +
											'<div class="expimgcount">' +
																	'4&#32;изобр' +
											'</div>' +
										'</td>' +
										'<td>' +
											car.name +
										'</td>' +
										'<td>' +
											car.year +
										'</td>' +
										'<td>' +
											car.price + ' р.' +
										'</td>' +
									'</tr>');
	});
});