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

/*
 * 
 * 
 * <tr class="explistrow0&#32;top">
																<td width="100px">
																<div class="photo top">
																	<a href="more.html"><span></span><img src="css/expauto/images/thumbs/3_1314745445.jpg" alt="Used Cars&#45;BMW&#45;X5" /></a>
																</div>
																<div class="expimgcount">
																	4&#32;изобр
																</div></td>
																<td>
																<div class="expautos_list_markmod">
																	<a href="more.html">BMW X5 16.6 l</a>
																</div>
																<div class="expautos_list_markmod_bottom">
																	<span title="Километраж"> 70000&nbsp;км</span>&#32;&#58;&#58; <span title="Тип кузова">hatchback</span>&#32;&#58;&#58; <span title="Привод">задний</span>
																	<div id="expshortlist379" class="expshortlist_lspage">
																		<span title="Add to Shortlist"><a href="javascript:expshortlist(1,379,'Saved')">В блокнот</a></span>
																	</div>
																	<div class="explistuserposition"></div>
																</div><div class="expautos_list_markmod_modules"></div></td>
																<td><img src="images/silver.png" alt="silver" title="Exterior Color: silver" /></td>
																<td>1996</td>
																<td><span title="автомат">A</span></td>
																<td><span title="дизель">D</span></td>
																<td>$ 15,000 USD
																<br />
																&euro; 10,245 EUR
																<br />
																</td>
															</tr>
															<tr class="explistrow1&#32;solid">
																<td width="100px">
																<div class="photo solid">
																	<a href="more.html"> <span></span> <img src="css/expauto/images/thumbs/1_1314736522.jpg" alt="Used Cars&#45;Audi&#45;A3" /></a>
																</div>
																<div class="expimgcount">
																	1&#32;изобр
																</div></td>
																<td>
																<div class="expautos_list_markmod">
																	<a href="more.html">Audi A3 1.9 l ;145 kW</a>
																</div>
																<div class="expautos_list_markmod_bottom">
																	<span title="Километраж">98000&nbsp;км</span>&#32;&#58;&#58; <span title="Тип кузова">hatchback</span>&#32;&#58;&#58; <span title="Привод">передний</span>
																	<div id="expshortlist6" class="expshortlist_lspage">
																		<span title="В корзину"> <a href="javascript:expshortlist(1,6,'Saved')">В блокнот</a> </span>
																	</div>
																	<div class="explistuserposition"></div>
																</div><div class="expautos_list_markmod_modules"></div></td>
																<td><img src="images/black.png" alt="black" title="Exterior Color: black" /></td>
																<td>2006</td>
																<td><span title="automatic">A</span></td>
																<td><span title="petrol">P</span></td>
																<td>$ 14,500 USD
																<br />
																&euro; 9,904 EUR
																<br />
																</td>
															</tr>
															<tr class="explistrow0&#32;">
																<td width="100px">
																<div class="photo ">
																	<a href="more.html"> <span></span> <img src="css/expauto/images/thumbs/2_1337344871.jpg" alt="Used Cars&#45;Mini&#45;Cooper" /> </a>
																</div>
																<div class="expimgcount">
																	4&#32;изобр
																</div></td>
																<td>
																<div class="expautos_list_markmod">
																	<a href="more.html">Mini Cooper 1.6 l 134 kW </a>
																</div>
																<div class="expautos_list_markmod_bottom">
																	<span title="Километраж">78000&nbsp;км</span>&#32;&#58;&#58; <span title="Тип кузова">hatchback</span>&#32;&#58;&#58; <span title="Приврд">передний</span>
																	<div id="expshortlist7" class="expshortlist_lspage">
																		<span title="В корзину"><a href="javascript:expshortlist(1,7,'Saved')">В блокнот</a></span>
																	</div>
																	<div class="explistuserposition"></div>
																</div><div class="expautos_list_markmod_modules"></div></td>
																<td><img src="images/white.png" alt="white" title="Exterior Color: white" /></td>
																<td>2007</td>
																<td><span title="automatic">A</span></td>
																<td><span title="petrol">P</span></td>
																<td>$ 15,700 USD
																<br />
																&euro; 10,723 EUR
																<br />
																</td>
															</tr>
 */