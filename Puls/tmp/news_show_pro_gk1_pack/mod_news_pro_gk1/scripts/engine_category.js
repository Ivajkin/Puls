window.addEvent("load",function(){	
	$$('.gk_npro_mainwrap').each(function(el,i){
		var TID = el.getProperty('id');
		var main = $(TID);
		//
		if($E('.gk_npro_full_interface', main) && $E('.gk_npro_wrap1', main)){
			var offset = $E('.gk_npro_wrap1', main).getSize().size.x;
			var scroller = new Fx.Scroll($E('.gk_npro_wrap1', main));	
			var blocks = $ES('.gk_npro_block_wrap', main);
			var actual_page = 0;
			//
			$E('.gk_npro_full_interface li', main).setProperty("class", "active");
			//
			$ES('.gk_npro_full_interface li', main).each(function(elm, j){
				elm.addEvent("click", function(){
					scroller.scrollTo(j*offset,0);
					$ES('.gk_npro_full_interface li', main).each(function(elmt, k){
						if(k != j) elmt.removeProperty("class");
						else elmt.setProperty("class", "active");
						actual_page = j;
					});
				});	
			});
			//
			$E('.gk_npro_full_prev', main).addEvent("click", function(){
				if(actual_page == 0){
					scroller.scrollTo((blocks.length - 1 )*offset,0);
					$ES('.gk_npro_full_interface li', main).each(function(elmt, k){
						if(k != blocks.length - 1) elmt.removeProperty("class");
						else elmt.setProperty("class", "active");
					});
					actual_page = blocks.length - 1;
				}else{
					actual_page--;	
					scroller.scrollTo(actual_page*offset,0);
					$ES('.gk_npro_full_interface li', main).each(function(elmt, k){
						if(k != actual_page) elmt.removeProperty("class");
						else elmt.setProperty("class", "active");
					});				
				}
			});
			//
			$E('.gk_npro_full_next', main).addEvent("click", function(){
				if(actual_page == blocks.length - 1){
					scroller.scrollTo(0,0);
					$ES('.gk_npro_full_interface li', main).each(function(elmt, k){
						if(k != 0) elmt.removeProperty("class");
						else elmt.setProperty("class", "active");
					});
					actual_page = 0;
				}else{
					actual_page++;	
					scroller.scrollTo(actual_page*offset,0);
					$ES('.gk_npro_full_interface li', main).each(function(elmt, k){
						if(k != actual_page) elmt.removeProperty("class");
						else elmt.setProperty("class", "active");
					});				
				}				
			});
		}
		//
		if($E('.gk_npro_short_interface', main)){
			//
			if($E('.gk_npro_short_prev', main)){
				$E('.gk_npro_short_prev', main).addEvent("click", function(){
					if(actual_list_page == 0){
						scroller.scrollTo((blocks.length - 1 ) * offset_list, 0);
						actual_page = blocks.length - 1;
					}else{
						actual_page--;	
						scroller.scrollTo(actual_page*offset_list, 0);				
					}
				});
				//
				$E('.gk_npro_short_next', main).addEvent("click", function(){
					if(actual_page == blocks.length - 1){
						scroller.scrollTo(0, 0);
						actual_page = 0;
					}else{
						actual_page++;	
						scroller.scrollTo(actual_page * offset, 0);
					}				
				});
			}
		}	
	});
});