window.addEvent('domready', function () {
    jgetData(function (dData) {

	dData.forEach(function(car) {
		$j("tbody#list").append('<tr class="explistrow0&#32;top">' +
										'<td width="100px">' +
											'<div class="photo top">' +
												'<a href="?page=more">' +
													'<span></span>' +
													'<img width="130px" heigth="auto" src="' + car.img[0] + '" />' +
												'</a>' +
											'</div>' +
											'<div class="expimgcount">' +
																	'4&#32;изобр' +
											'</div>' +
										'</td>' +
										'<td>' +
											'<a href="?page=more">' +
												car.name +
											'</a>' +
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
});
