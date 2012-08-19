window.addEvent('domready', function () {
    jgetData(function (dData) {

	var carID = 0;
	dData.forEach(function(car) {
		var imageID = Math.floor(Math.random()*car.img.length);
		var link = '?page=more&carID='+carID;
		$j("tbody#list").append('<tr class="explistrow0&#32;top">' +
										'<td width="100px">' +
											'<div class="photo top">' +
												'<a href="' + link + '">' +
													'<span></span>' +
													'<img width="130px" heigth="auto" src="' + car.img[imageID] + '" />' +
												'</a>' +
											'</div>' +
											'<div class="expimgcount">' +
																	'4&#32;изобр' +
											'</div>' +
										'</td>' +
										'<td>' +
											'<a href="' + link + '">' +
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
			++carID;
		});
    
    });
});
