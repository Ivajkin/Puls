/*---------------------------------------------------------------- 
  Copyright:
  (C) 2008 - 2011 IceTheme
  
  License:
  GNU/GPL http://www.gnu.org/copyleft/gpl.html
  
  Author:
  IceTheme - http://wwww.icetheme.com
---------------------------------------------------------------- */

if( typeof imagePreview !== "function"){
	function imagePreview( _icemain){
		var t = new Tips('.iceTip');
		_icemain.getElements('.iceTip').each(function(tip){
			var imgSrc = tip.retrieve('tip:text');
			var imgAlt = tip.retrieve('tip:title');
			var imgWidth = tip.get("width");
			var imgHeight = tip.get("height");
			tip.store('tip:text', new Element('img',{'src':imgSrc,'alt':imgAlt,'width':imgWidth,'height':imgHeight}));
		});
	}
}
if( typeof(IceCarousel) == 'undefined' ){
	var IceCarousel = new Class( {
		initialize:function( _icemain, options ){
			this.setting = $extend({
				autoStart			: true,
				descStyle	    	: 'sliding',
				mainItemSelector    : 'div.ice-main-item',
				navSelector  		: 'li' ,
				navigatorEvent		: 'click',
				interval	  	 	:  2000,
				auto			    : false,
				navItemsDisplay		: 3,
				startItem			: 0,
				navItemHeight		: 100,
				navItemWidth 		: 310,
				maxItemSelector : 0,
				datagroup		: 'k2',
				isAjax			: false,
				url				: '',
				nextButton		: '.ice-next',
				prevButton 		: '.ice-prev'
			}, options || {} );
			
			var eMain 	   = _icemain.getElement('.ice-main-wapper');
			var eNavigator = _icemain.getElement('.ice-navigator-outer .ice-navigator');
			var eNavOuter  = _icemain.getElement('.ice-navigator-outer');
			
			this.slideLength = 0;
			this.eMain = eMain;
			this.loadingBlock = _icemain.getElement('.ice-loading');
			this.nextButton = _icemain.getElement( this.setting.nextButton );
			this.prevButton	= _icemain.getElement( this.setting.prevButton );
			this.currentNo  = 0;
			this.nextNo     = null;
			this.previousNo = null;
			this.fxItems	= [];	
			this.minSize 	= 0;
			this.onClick = false;
			this._icemain = _icemain;
			if( $defined(eMain) ){
				this.slides	   = eMain.getElements( this.setting.mainItemSelector );
				if(this.setting.maxItemSelector > 0){
					this.slides.length = this.setting.maxItemSelector;
				}
				this.slideLength = this.slides.length;
				this.maxWidth  = eMain.getStyle('width').toInt();
				this.maxHeight = eMain.getStyle('height').toInt();
				this.styleMode = this.__getStyleMode();  
				var fx =  $extend({waiting:false, onComplete:function(){ this.onClick=false}.bind(this) }, this.setting.fxObject );
				this.slides.each( function(item, index) {	
					item.setStyles( eval('({"'+this.styleMode[0]+'": index * this.maxSize,"'+this.styleMode[1]+'":Math.abs(this.maxSize),"display" : "block"})') );		
					this.fxItems[index] = new Fx.Morph( item,  fx );
				}.bind(this) );
				if( this.styleMode[0] == 'opacity' || this.styleMode[0] =='z-index' ){
					this.slides[0].setStyle(this.styleMode[0],'1');
				}
				eMain.addEvents( { 'mouseenter' : this.stop.bind(this),
							   	   'mouseleave' :function(e){ 
								    if(  this.setting.auto ){
										this.play( this.setting.interval,'next', true );
									} }.bind(this) } );
			}
			if( $defined(eNavigator) && $defined(eNavOuter) ){
				var modes = {
					horizontal : ['margin-left', 'width', 'height', 'navItemWidth', 'navItemHeight'],
					vertical   : ['top', 'height', 'width', 'navItemHeight', 'navItemWidth']
				}
				var mode = ( this.setting.navPos == 'bottom' || this.setting.navPos == 'top' )?'horizontal' : 'vertical';	
		
				this.navigatorItems = eNavigator.getElements( this.setting.navSelector );
				if( this.setting.navItemsDisplay > this.navigatorItems.length ){
					this.setting.navItemsDisplay = this.navigatorItems.length;	
				}
				
				if( $defined(_icemain.getElement(".ice-bullets"))  ){
					this.setting.navItemHeight = this.navigatorItems[0].offsetHeight;
					this.setting.navItemWidth  = this.navigatorItems[0].offsetWidth;
				}
				
				this.navigatorSlideSize = this.setting[modes[mode][3]];	 
				eNavOuter.setStyle(modes[mode][1], this.setting.navItemsDisplay * this.navigatorSlideSize);
                eNavOuter.setStyle(modes[mode][2], this.setting[modes[mode][4]] );
								
				this.navigatorMode = 	modes[mode][0];		
				this.navigatorFx = new Fx.Tween( eNavigator,{transition:Fx.Transitions.Sine.easeInOut,duration:900} );
					
					
				 if(  this.setting.auto ){
				//	this.registerMousewheelHandler( eNavigator ); // allow to use the srcoll
				 }
				this.navigatorItems.each( function(item,index) {
					item.addEvent( this.setting.navigatorEvent, function(){		
					if( this.onClick ) return ;
						this.jumping( index, true );
						this.setNavActive( index, item );	
					}.bind(this) ); 
	
						item.setStyles( { 'height' : this.setting.navItemHeight,
									  	  'width'  : this.setting.navItemWidth} );		
				}.bind(this) );
				// set default setting
				this.currentNo=this.setting.startItem.toInt()>this.slides.length?this.slides.length:this.setting.startItem.toInt();
				this.setNavActive( this.currentNo );
				if( !this.setting.isAjax ){
					this.slides.setStyle(this.styleMode[0] ,this.maxSize );
				}
				this.slides[this.currentNo].setStyle(this.styleMode[0] ,this.minSize );
			}
			this.onEndJump( this.currentNo );
			imagePreview(this._icemain);
		},
		navivationAnimate:function( currentIndex ) { 
			if (currentIndex <= this.setting.startItem 
				|| currentIndex - this.setting.startItem >= this.setting.navItemsDisplay-1) {
					this.setting.startItem = currentIndex - this.setting.navItemsDisplay+2;
					if (this.setting.startItem < 0) this.setting.startItem = 0;
					if (this.setting.startItem >this.slides.length-this.setting.navItemsDisplay) {
						this.setting.startItem = this.slides.length-this.setting.navItemsDisplay;
					}
			}
			this.navigatorFx.cancel().start( this.navigatorMode,-this.setting.startItem*this.navigatorSlideSize );	
		},
		setNavActive:function( index, item ){
			if( $defined(this.navigatorItems) && $defined( this.navigatorItems[index] ) ){ 
				this.navigatorItems.removeClass('active');
				this.navigatorItems[index].addClass('active');	
				this.navivationAnimate( this.currentNo );	
			}
		},
		__getStyleMode:function(){
			switch( this.setting.direction ){
				case 'opacity': this.maxSize=0; this.minSize=1; return ['opacity','opacity'];
				case 'replace': this.maxSize=0; this.minSize=1; return ['display','display'];
				case 'vrup':    this.maxSize=this.maxHeight;    return ['top','height'];
				case 'vrdown':  this.maxSize=-this.maxHeight;   return ['top','height'];
				case 'hrright': this.maxSize=-this.maxWidth;    return ['left','width'];
				case 'hrleft':
				default: this.maxSize=this.maxWidth; return ['left','width'];
			}
		},
		registerMousewheelHandler:function( element ){
			element.addEvents({
				'wheelup': function(e) {
					
					e = new Event(e).cancel(); 
						this.previous(true);
				}.bind(this),
			 
				'wheeldown': function(e) {
					e = new Event(e).cancel();
				
					this.next(true);
				}.bind(this)
			} );
		},
		registerButtonsControl:function( eventHandler, objects, isHover ){
			if( $defined(objects) && this.slides.length > 1 ){
				for( var action in objects ){ 
					if( $defined(this[action.toString()])  && $defined(objects[action]) ){
						objects[action].addEvent( eventHandler, this[action.toString()].bind(this, true) );
					}
				}
			}
			return this;	
		},
		start:function( isStart, obj ){
			this.setting.auto = isStart;
			// if use the preload image.
			if( obj ) {
				this.preloadImages(  obj );
			} else {
				if( this.setting.auto && this.slides.length > 1 ){
						this.play( this.setting.interval,'next', true );}	
			}
		},
		onComplete:function( obj ){
			(function(){																
				obj.fade('out');		
			}).delay(500);
			if( this.setting.auto && this.slides.length > 1 ){
				this.play( this.setting.interval,'next', true );}	
			
		},
		preloadImages:function( obj ){  
			var loaded=[];
			var counter=0;
			var self = this;
			var _length = this.slides.getElements('img').length;
			this.timer = setInterval( function(){
				if(counter >= _length) {	
						$clear(self.timer);
						self.onComplete( obj );
						return true;
				} 
			}, 200);
			this.slides.getElements('img').each( function(img, index){
				image = new Image();
				image.src=img.src;
				if( !image.complete ){				  
					image.onload =function(){
						counter++;
					}
					image.onerror =function(){ 
						counter++;
					}
				}else {
					counter++;
				}
			} );
		},
		onProcessing:function( manual, start, end ){	
			this.onClick = true;
			this.previousNo = this.currentNo + (this.currentNo>0 ? -1 : this.slides.length-1);
			this.nextNo 	= this.currentNo + (this.currentNo < this.slides.length-1 ? 1 : 1- this.slides.length);
			return this;
		},
		finishFx:function( manual ){
			if( manual ) this.stop();
			if( manual && this.setting.auto ){	
				this.play( this.setting.interval,'next', true );
			}		
			this.setNavActive( this.currentNo );	
		},
		getObjectDirection:function( start, end ){
			return eval("({'"+this.styleMode[0]+"':["+start+", "+end+"]})");	
		},
		fxStart:function( index, obj ){
			this.fxItems[index].cancel(true, false).start( obj );
			return this;
		},
		jumping:function( no, manual ){
			this.stop();
			if( this.currentNo == no ) return;
			this.onEndJump( no );
			if(	this.setting.isAjax && !this.fxItems[ no ] ){
				var dir = "";
				for( i = 0;i<=no;i++){
					if( !this.fxItems[ i ]){
						dir = i == no ?'jump':"";
						this.loadAjax( i, manual, null, dir );
					}
				}
			}
			else{
				var object = this.onProcessing( null, manual, 0, this.maxSize );
				if( this.currentNo < no  ){
					object.fxStart( no, this.getObjectDirection(this.maxSize , this.minSize) );
					object.fxStart( this.currentNo, this.getObjectDirection(this.minSize,  -this.maxSize) );
				} else {
					object.fxStart( no, this.getObjectDirection(-this.maxSize , this.minSize) );
					object.fxStart( this.currentNo, this.getObjectDirection(this.minSize,  this.maxSize) );	
				}
				object.finishFx( manual );
				this.currentNo  = no;
			}
		},
		next:function( manual , item){
			if( this.onClick ) return ;
			
			if( this.onEndItem() && !this.setting.auto){
				return;
			}
			if(this.setting.auto){
				this.currentNo += (this.currentNo < this.slides.length-1) ? 1 : (1 - this.slides.length);	
			}
			else{
				this.currentNo += 1;
			}
			this.onEndJump( this.currentNo );
			if(	this.setting.isAjax && !this.fxItems[this.currentNo] ){
				this.loadAjax( this.currentNo, manual, item, 'next' );
			}
			else{
				//this.currentNo += 1 - this.slides.length;
				this.onProcessing( item, manual, 0, this.maxSize )
					.fxStart( this.currentNo, this.getObjectDirection(this.maxSize ,this.minSize) )
					.fxStart( this.previousNo, this.getObjectDirection(this.minSize, -this.maxSize) )
					.finishFx( manual );
			}
		},
		previous:function( manual, item ){
			if( this.onClick ) return ;
			if( this.onFirstItem() && !this.setting.auto ){
				return;
			}
			if(this.setting.auto){
				this.currentNo += this.currentNo > 0 ? -1 : this.slides.length - 1;
			}
			else{
				this.currentNo += -1 ;
			}
			this.onEndJump( this.currentNo );
			if(	this.setting.isAjax && !this.fxItems[this.currentNo] ){
				this.loadAjax( this.currentNo, manual, item, 'prev' );
			}
			else{
				this.onProcessing( item, manual, -this.maxWidth, this.minSize )
					.fxStart( this.nextNo, this.getObjectDirection(this.minSize, this.maxSize) )
					.fxStart( this.currentNo,  this.getObjectDirection(-this.maxSize, this.minSize) )
					.finishFx( manual );
			}
		},
		onEndJump:function( no ){
			 if( no <= 0){
				if(this.prevButton){
					this.prevButton.addClass("disabled");
				}
				if(this.nextButton){
					this.nextButton.removeClass("disabled");
				}
			}
			else if( no >= this.slides.length -1 ){
				if(this.nextButton){
					this.nextButton.addClass("disabled");
				}
				if(this.prevButton){
					this.prevButton.removeClass("disabled");
				}
			}
			else{
				if(this.prevButton){
					this.prevButton.removeClass("disabled");
				}
				if(this.nextButton){
					this.nextButton.removeClass("disabled");
				}
			}
		},
		onFirstItem: function(){
			if( this.currentNo <=0){
				return true;
			}
			return false;
		},
		onEndItem: function(){
			if( this.currentNo >= this.slides.length -1 ){
				return true;
			}
			return false;
		},
		loadAjax: function( nextpage, manual, item, dir ){
			var self = this;
			this.loadingBlock = this._icemain.getElement('.ice-loading');
			this.loadingBlock.setStyle("display", "block");
			var page = ( parseInt( nextpage ) + 1 ) ;
			var req =new Request({
							  method: 'get',
							  url: this.setting.url,
							  data: { 'p' : page },
							  onComplete: function(response) {
									if(response !=""){
										this.loadingBlock.setStyle("display", "none");
										var eMainHtml = this.eMain.get("html");
										this.eMain.set("html", eMainHtml + response );
										var itemTmp = this.eMain.getElement(".page-"+page);
										var fx =  $extend({waiting:false, onComplete:function(){ this.onClick=false}.bind(this) }, this.setting.fxObject );
										this.slides	   = this.eMain.getElements( this.setting.mainItemSelector );
										this.slides.length = this.slideLength;
										this.slides.each( function(item, index) {	
											this.fxItems[index] = new Fx.Morph( item,  fx );
										}.bind(this) );
										itemTmp.setStyles( eval('({"'+this.styleMode[0]+'": nextpage * this.maxSize,"'+this.styleMode[1]+'":Math.abs(this.maxSize),"display" : "block"})') );
										if( dir =='prev'){
											this.onProcessing( item, manual, -this.maxWidth, this.minSize )
												.fxStart( this.nextNo, this.getObjectDirection(this.minSize, this.maxSize) )
												.fxStart( this.currentNo,  this.getObjectDirection(-this.maxSize, this.minSize) )
												.finishFx( manual	);
										}
										else if( dir == 'next') {
											this.onProcessing( item, manual, 0, this.maxSize )
												.fxStart( this.currentNo, this.getObjectDirection(this.maxSize ,this.minSize) )
												.fxStart( this.previousNo, this.getObjectDirection(this.minSize, -this.maxSize) )
												.finishFx( manual );
										}
										else if( dir == 'jump' ){
											var object = this.onProcessing( null, manual, 0, this.maxSize );
											if( this.currentNo < nextpage  ){
												object.fxStart( nextpage, this.getObjectDirection(this.maxSize , this.minSize) );
												object.fxStart( this.currentNo, this.getObjectDirection(this.minSize,  -this.maxSize) );
											} else {
												object.fxStart( nextpage, this.getObjectDirection(-this.maxSize , this.minSize) );
												object.fxStart( this.currentNo, this.getObjectDirection(this.minSize,  this.maxSize) );	
											}
											this.currentNo  = nextpage;
											object.finishFx( manual );
										}
										imagePreview(this._icemain);
										if(this.setting.datagroup == 'virtuemart'){
											if(typeof Virtuemart != 'undefined'){
												jQuery(".product").each(function(){
														var cart = jQuery(this);
														plus   = cart.find('.quantity-plus');
														minus  = cart.find('.quantity-minus');
														addtocart = cart.find('input.addtocart-button');
														addtocart.unbind("click");
														plus.unbind("click");
														minus.unbind("click");
													});
													Virtuemart.product(jQuery(".product"));
											}
											else if(typeof jQuery != 'undefined')
												jQuery(".product").product();
										}
									}
								}.bind( this) 
							}).send();
		},
		play:function( delay, direction, wait ){
			this.stop(); 
			if(!wait){ this[direction](false); }
			this.isRun = this[direction].periodical(delay,this,true);
		},stop:function(){  $clear(this.isRun ); clearInterval(this.isRun); }
	} );
}
