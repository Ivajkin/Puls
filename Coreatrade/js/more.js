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
			var car = cars[0];
			
			for(var item in car) {
				$('.placeholder-' + item).append(car[item]);
			}
		});
	});
})(jQuery);
