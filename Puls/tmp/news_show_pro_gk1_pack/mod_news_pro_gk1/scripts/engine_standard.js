window.addEvent("load",function(){	
	$$('.gk_npro_mainwrap').each(function(el,i){
		var TID = el.getProperty('id');
		var main = $(TID);
		//
		if($E('.gk_npro_full_interface', main) && $E('.gk_npro_full_scroll1', main)){
			var offset = $E('.gk_npro_full_scroll1', main).getSize().size.x;
			var scroller_main = new Fx.Scroll($E('.gk_npro_full_scroll1', main));	
			var blocks = $ES('.gk_npro_full_tablewrap', main);
			var actual_main_page = 0;
			//
			$E('.gk_npro_full_interface li', main).setProperty("class", "active");
			//
			$ES('.gk_npro_full_interface li', main).each(function(elm, j){
				elm.addEvent("click", function(){
					scroller_main.scrollTo(j*offset,0);
					$ES('.gk_npro_full_interface li', main).each(function(elmt, k){
						if(k != j) elmt.removeProperty("class");
						else elmt.setProperty("class", "active");
						actual_main_page = j;
					});
				});	
			});
			//
			$E('.gk_npro_full_prev', main).addEvent("click", function(){
				if(actual_main_page == 0){
					scroller_main.scrollTo((blocks.length - 1 )*offset,0);
					$ES('.gk_npro_full_interface li', main).each(function(elmt, k){
						if(k != blocks.length - 1) elmt.removeProperty("class");
						else elmt.setProperty("class", "active");
					});
					actual_main_page = blocks.length - 1;
				}else{
					actual_main_page--;	
					scroller_main.scrollTo(actual_main_page*offset,0);
					$ES('.gk_npro_full_interface li', main).each(function(elmt, k){
						if(k != actual_main_page) elmt.removeProperty("class");
						else elmt.setProperty("class", "active");
					});				
				}
			});
			//
			$E('.gk_npro_full_next', main).addEvent("click", function(){
				if(actual_main_page == blocks.length - 1){
					scroller_main.scrollTo(0,0);
					$ES('.gk_npro_full_interface li', main).each(function(elmt, k){
						if(k != 0) elmt.removeProperty("class");
						else elmt.setProperty("class", "active");
					});
					actual_main_page = 0;
				}else{
					actual_main_page++;	
					scroller_main.scrollTo(actual_main_page*offset,0);
					$ES('.gk_npro_full_interface li', main).each(function(elmt, k){
						if(k != actual_main_page) elmt.removeProperty("class");
						else elmt.setProperty("class", "active");
					});				
				}				
			});
		}
		//
		if($E('.gk_npro_short_interface', main)){
			var scroller_list = new Fx.Scroll($E('.gk_npro_short_scroll1', main));
			var blocks_list = $ES('.gk_npro_short_ulwrap', main);
			var offset_list = $E('.gk_npro_short_scroll1', main).getSize().size.x;
			var actual_list_page = 0;
			//
			if($E('.gk_npro_short_prev', main)){
				$E('.gk_npro_short_prev', main).addEvent("click", function(){
					if(actual_list_page == 0){
						scroller_list.scrollTo((blocks_list.length - 1 ) * offset_list, 0);
						actual_list_page = blocks_list.length - 1;
					}else{
						actual_list_page--;	
						scroller_list.scrollTo(actual_list_page*offset_list, 0);				
					}
				});
				//
				$E('.gk_npro_short_next', main).addEvent("click", function(){
					if(actual_list_page == blocks_list.length - 1){
						scroller_list.scrollTo(0, 0);
						actual_list_page = 0;
					}else{
						actual_list_page++;	
						scroller_list.scrollTo(actual_list_page * offset_list, 0);
					}				
				});
			}
		}	
	});
});