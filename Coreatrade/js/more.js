(function($) {
	$(document).ready(function() {
		
		
		
		$('.placeholder-name').empty();
		$('.placeholder-type').empty();
		$('.placeholder-mark').empty();
		$('.placeholder-model').empty();
		$('.placeholder-price').empty();
		$('.placeholder-color').empty();
		$('.placeholder-year').empty();
		$('.placeholder-vvv').empty();
		$('.placeholder-fuel').empty();
		$('.placeholder-kpp').empty();
		$('.placeholder-drive').empty();
		$('.placeholder-door').empty();
		$('.placeholder-seat').empty();
		$('.placeholder-complect').empty();
		
		jgetData(function(cars) {
			var car = cars[carID];
			
			for(var item in car) {
				$('.placeholder-' + item).append(car[item]);
			}
			
			// галерея картинок
			var img = car['img'];
			$('#largeimage img').attr('src', img[0]);
			
			for(var i = 0; i < img.length; ++i) {
				
				$('#image-preview-container').append(
					'<li class="expautos_detail_li_img">' +
						'<a href="javascript:jooImage(\'largeimage\',\'' + img[i] + '\',\'\');"> <img src="' + img[i] + '" alt="" title="" /> </a>' +
					'</li>'
				);
			}
			
			
			
		});
		
		
		
		
	});
})(jQuery);
