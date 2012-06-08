
/**
 * IceSlideshow Module for Joomla 1.7 By IceTheme
 * 
 * 
 * @copyright	Copyright (C) 2008 - 2012 IceTheme.com. All rights reserved.
 * @license		GNU General Public License version 2
 * 
 * @Website 	http://www.icetheme.com/Joomla-Extensions/IceSlideshow.html
 * @Support 	http://www.icetheme.com/Forums/IceSlideshow/
 *
 */
 
 
if(typeof(IceSlideShow) == 'undefined'){
	var IceSlideShow = new Class({
		initialize:function(eMain, eNavigator,eNavOuter, options){
			this.setting = $extend({
				autoStart			: true,
				descStyle	    	: 'sliding',
				mainItemSelector    : 'div.lof-main-item',
				navSelector  		: 'li' ,
				navigatorEvent		: 'click',
				slideCaption : true,
				interval	  	 	:  2000,
				auto			    :  false,
				navItemsDisplay:3,
				startItem:0,
				navItemHeight:100,
				navItemWidth:310,
				captionHeight: 70,
				captionOpacity: 0.7,
				zoom : 50,
				pan : 50,
				pansize: 30,
				wdirection: "left"
			}, options || {});

			this.currentNo  = 0;
			this.currentZIndex = 0;
			this.nextNo     = null;
			this.previousNo = null;
			this.fxItems	= [];	
			this.fx = [];
			this.fxImages = [];
			this.fxCaptions = [];
			this.captions = [];
			this.minSize 	= 0;
			this.onClick = false;
			if($defined(eMain)){
				this.slides	   = eMain.getElements(this.setting.mainItemSelector);
				this.maxWidth  = eMain.getStyle('width').toInt();
				this.maxHeight = eMain.getStyle('height').toInt();
				
				this.styleMode = this.__getStyleMode();
				var fx =  $extend({waiting:false, onComplete:function(){ this.onClick=false}.bind(this)}, this.setting.fxObject);
				var fx2 =  $extend({waiting:false, onComplete:function(){ this.onClick=false}.bind(this)}, this.setting.fxCaptionObject);
				this.slides.each(function(item, index) {
					//item.setStyles(eval('({"'+this.styleMode[0]+'": index * this.maxSize,"'+this.styleMode[1]+'":Math.abs(this.maxSize),"display" : "block"})'));
					 if( this.setting.direction.test(/pan|zoom|combo/)){
						imgItem = item.getElement("img.image-slide");
						imgItem.setStyles(eval('({"visibility":"visible","display" : "block",position: "absolute","width":'+(this.maxWidth + this.setting.pansize )+',"height":'+(this.maxHeight+this.setting.pansize)+'})'));
						item.setStyles(eval('({"'+this.styleMode[0]+'": index * this.maxSize,"'+this.styleMode[1]+'":Math.abs(this.maxSize),"display" : "block"})'));
						this.fxItems[index] = new Fx.Morph(item,  fx);
					}
					else if( this.setting.direction.test(/wipe/)){
						imgItem = item.getElement("img.image-slide");
						item.setStyles(eval('({"background":"transparent","width": this.maxWidth,"height":this.maxHeight,"position":"absolute","display" : "block","top":0,"left":0})'));
						imgItem.setStyles(eval('({"width": this.maxWidth,"height": this.maxHeight,"position":"absolute","display" : "block","opacity":1,"left":"auto","top":"auto"})'));
						this.fxItems[index] = new Fx.Morph(imgItem,  fx);
					}
					else{
						item.setStyles(eval('({"'+this.styleMode[0]+'": index * this.maxSize,"'+this.styleMode[1]+'":Math.abs(this.maxSize),"display" : "block"})'));
						imgItem = item.getElement("img.image-slide");
						imgItem.setStyles(eval('({"width": this.maxWidth,"height": this.maxHeight,"position":"absolute","display" : "block","opacity":1,"left":"auto","top":"auto"})'));
						this.fxItems[index] = new Fx.Morph(item,  fx);
					}
					if(this.setting.slideCaption){
						if(item.getElement(".ice-description")){
							this.captions[index] = item.getElement(".ice-description");
							this.captions[index].setStyles({"height":0,"opacity":0});
							this.fxCaptions[index] = new Fx.Morph(this.captions[index], fx2);
						}
						else{
							this.captions[index] = null;
							this.fxCaptions[index] = null;
						}
					}
				}.bind(this));
				if(this.styleMode[0] == 'opacity' || this.styleMode[0] =='z-index'){
					this.slides[0].setStyle(this.styleMode[0],'1');
				}
				if(this.setting.direction == 'wipe'){
					this.slides[0].setStyle("z-index",'1');
				}
				if(this.setting.direction.test(/pan|zoom|combo/) && Browser.name == 'ie' && Browser.version == 8) { 
					this.slides[0].setStyle("z-index",'1');
				}
		 
				eMain.addEvents({ 'mouseenter' : this.stop.bind(this),
							   	   'mouseleave' :function(e){
								   if(this.setting.auto ) {
									this.play(this.setting.interval,'next', true); } }.bind(this) });
			}
			this.progressbar =  _lofmain.getElement(".ice-proccessbar");
			if( this.progressbar ){
				this.progressbar.fade('out');
				this.progressbar.set("morph",{
					'duration': this.setting.interval,
					'transition': 'linear'
				});
				this.progressbar.maxWidth = this.progressbar.offsetWidth;
			}
			// if has the navigator
			if($defined(eNavigator)){
				this.navigatorItems = eNavigator.getElements(this.setting.navSelector);
				if(this.setting.navItemsDisplay > this.navigatorItems.length){
					this.setting.navItemsDisplay = this.navigatorItems.length;	
				}
				
				this.navigatorFx = new Fx.Morph(eNavigator,
												{transition:Fx.Transitions.Quad.easeInOut,duration:800});
				
				this.navigatorItems.each(function(item,index) {
					item.addEvent(this.setting.navigatorEvent, function(){													 
						this.jumping(index, true);
						this.setNavActive(index, item);	
					}.bind(this));
					
				}.bind(this));
				this.setNavActive(0);
			}
		},
		navivationAnimate:function(currentIndex) {
			if (currentIndex <= this.setting.startItem 
				|| currentIndex - this.setting.startItem >= this.setting.navItemsDisplay-1) {
					this.setting.startItem = currentIndex - this.setting.navItemsDisplay+2;
					if (this.setting.startItem < 0) this.setting.startItem = 0;
					if (this.setting.startItem >this.slides.length-this.setting.navItemsDisplay) {
						this.setting.startItem = this.slides.length-this.setting.navItemsDisplay;
					}
			}
			//alert(this.setting.navPos);
			if(this.setting.navPos == 'left' || this.setting.navPos == 'right')
				this.navigatorFx.cancel().start({ 'top':-this.setting.startItem*this.setting.navItemHeight});	
			else
				this.navigatorFx.cancel().start({ 'left':-this.setting.startItem*this.setting.navItemWidth});	
		},
		setNavActive:function(index, item){
			if($defined(this.navigatorItems)){ 
				this.navigatorItems.removeClass('active');
				this.navigatorItems[index].addClass('active');	
				this.navivationAnimate(this.currentNo);	
			}
		},
		__getStyleMode:function(){
			switch(this.setting.direction){
				case 'vrup':    this.maxSize=this.maxHeight;    return ['top','height'];
				case 'vrdown':  this.maxSize=-this.maxHeight;   return ['top','height'];
				case 'hrright': this.maxSize=-this.maxWidth;    return ['left','width'];
				case 'wipe': this.maxSize=this.maxWidth;    return [ this.setting.wdirection,'height'];
				case 'opacity':
				case 'combo':
				case 'zoom':
				case 'pan': this.maxSize=0; this.minSize=1; return ['opacity','opacity'];
				case 'hrleft':
				default: this.maxSize=this.maxWidth; return ['left','width'];
			}
		},
		registerMousewheelHandler:function(element){ 
			element.addEvent('mousewheel', function(e){
				e.stop();
				if(e.wheel > 0 ){
					this.previous(true);	
				} else {
					this.next(true);	
				}
			}.bind(this));
		},
		registerButtonsControl:function(eventHandler, objects, isHover){
			if($defined(objects) && this.slides.length > 1){
				for(var action in objects){ 
					if($defined(this[action.toString()])  && $defined(objects[action])){
						objects[action].addEvent(eventHandler, this[action.toString()].bind(this, [true]));
					}
				}
			}
			return this;	
		},
		start:function(isStart, obj){
			this.setting.auto = isStart;
			// if use the preload image.
			if(obj) {
				this.preloadImages(  obj );
			} else {
				if(isStart && this.slides.length > 0){this.play(this.setting.interval,'next', true);}	
			}
		},
		onComplete:function( obj ){
			(function(){																
				obj.fade('out');		
			}).delay(500);
			if( this.setting.auto && this.slides.length > 1 ){
				this.play( this.setting.interval,'next', true );
			}
			this.playCaption( this.currentNo, this.getCaptionDirection([ 0, this.setting.captionHeight, 0, this.setting.captionOpacity ]) );
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
				image.src=img.get("src");
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
		onProcessing:function(manual, start, end){
			this.onClick = true;
			this.previousNo = this.currentNo + (this.currentNo>0 ? -1 : this.slides.length-1);
			this.nextNo 	= this.currentNo + (this.currentNo < this.slides.length-1 ? 1 : 1- this.slides.length);				
			return this;
		},
		processbar:function(){
			this.progressbar.morph({
				'width': [0,this.progressbar.maxWidth]
			});
			this.progressbar.fade('in');
		},
		finishFx:function(manual){
			if(manual) this.stop();
			if(manual && this.setting.auto){
				this.play(this.setting.interval,'next', true);
			}
			this.setNavActive( this.currentNo );
			this.playCaption( this.currentNo, this.getCaptionDirection([ 0, this.setting.captionHeight, 0, this.setting.captionOpacity ]) );
			if(this.setting.direction.test(/zoom|pan|combo/)){
				this[this.setting.direction]( this.previousNo, this.currentNo );
				if(Browser.name == 'ie' && Browser.version == 8) {
					this.slides.each(function(el,index){
						this.slides[index].setStyle("z-index", "0");
					}.bind(this));
					this.slides[this.currentNo].setStyle("z-index",'10');
				}
			}
		},
		getCaptionDirection:function( listvalues ){
			return eval("({'height':["+listvalues[0]+", "+listvalues[1]+"],'opacity':["+listvalues[2]+","+listvalues[3]+"]})");
		},
		playCaption: function(index, obj){
			if(this.fxCaptions[index] != null){
				this.fxCaptions[index].cancel().start(obj);
			}
		},
		stopAllCaption: function(index){
			if(this.captions.length >0){
				this.captions.each(function(el,i){
					if(i != index){
						this.captions[i].setStyles({"height":0,"opacity":0});
					}
				}.bind(this));
			}
		},
		getObjectDirection:function(start, end){
			return eval("({'"+this.styleMode[0]+"':["+start+", "+end+"]})");
		},
		getObjectDirection2:function(prevNo, currentNo, direction){
			var loader = this.slides[ currentNo ].getElement("img");
			if(loader){
				if(direction.test(/zoom/)){
					var n = Math.max(this.maxWidth / loader.width, this.maxHeight / loader.height);
					var z = (this.setting.zoom === 'rand') ? Math.random() + 1 : (this.setting.zoom.toInt() / 100.0) + 1;
					var a = Math.ceil(loader.height * n);
					var b = Math.ceil(loader.width * n);
					var c = (a * z).toInt();
					var d = (b * z).toInt();
					if( this.setting.direction.test(/combo/) ){
						a = a + this.setting.pansize;
						b = b + this.setting.pansize;
					}
					var k =  {
						height: [c, a],
						width: [d, b],
						top: [-c/3,0]
					}
					return k;
				}
				else if(direction.test(/pan/)){
					var a = ['left top', 'right top', 'left bottom', 'right bottom'].getRandom().split(' ');
					var loader = this.slides[ currentNo ].getElement("img.image-slide");
					if(loader){
						loader.setStyles({"top":"auto","left":"auto","right":"auto","bottom":"auto"});
						var b = this.maxWidth - loader.width ,
						ey = this.maxHeight - loader.height ;
						var p = this.setting.pan === 'rand' ? Math.random() : Math.abs((this.setting.pan.toInt() / 100) - 1);
						var c = (b * p).toInt(),
						sy = (ey * p).toInt();
						var x = this.maxWidth / loader.width > this.maxHeight / loader.height;
						var d = {};
						var e = Math.floor( Math.random()*2 );
						d[a[e]] = e ? [sy, ey] : [c, b];
						return d;
					}
				}
			}
			return null;
		},
		combo: function (prevNo, currentNo) {
			var a = ['left top', 'right top', 'left bottom', 'right bottom'].getRandom().split(' ');
			var loader = this.slides[ currentNo ].getElement("img.image-slide");
			if(loader){
				var b = this.setting.direction.test(/zoom|combo/) ? this.getObjectDirection2( prevNo, currentNo, "zoom" ) : {};
				var c = this.setting.direction.test(/pan|combo/) ? this.getObjectDirection2( prevNo, currentNo, "pan" ) : {};
				this.fx.push(new Fx.Morph(loader, {
					transition:this.setting.transition, 
					duration: 2*this.setting.slideDuration.toInt() 
				}).start($merge(b, c)))
			}
		},
		zoom: function (prevNo, currentNo) {
			var k = this.getObjectDirection2( prevNo, currentNo, "zoom");
			var loader = this.slides[ currentNo ].getElement("img.image-slide");
			if(k){
				this.fx.push( new Fx.Morph( loader,{
										transition:this.setting.transition,  
										duration: this.setting.slideDuration
									  }).start( k ) );
			}
		},
		pan: function (prevNo, currentNo) {
			var d = this.getObjectDirection2( prevNo, currentNo, "pan");
			var loader = this.slides[ currentNo ].getElement("img.image-slide");
			if(d){
				this.fx.push( new Fx.Morph( loader,{
										transition:this.setting.transition,  
										duration: this.setting.slideDuration
									  }).start( d ) );
			}
		},
		wipe: function( previousNo, currentNo ){
			this.slides[ currentNo ].setStyle("z-index", 2);
			this.slides.each(function(el, i){
				if(i != currentNo && i != previousNo){
					this.slides[ i ].setStyle("z-index", 0);
				}
				else if( i == previousNo ){
					this.slides[ i ].setStyle("z-index", 1);
				}
			}.bind(this));
		},
		fxStart:function(index, obj){
			this.fxItems[index].cancel().start(obj);
			return this;
		},
		jumping:function(no, manual){
			this.stop();
			if(this.currentNo == no) return;
			var tmpCurrentNo = this.currentNo;
			this.currentNo  = no;
			this.stopAllCaption( no );
			if((no == tmpCurrentNo - 1) && tmpCurrentNo > 0)
			{
				this.onProcessing(null, manual, -this.maxWidth, this.minSize)
					.fxStart(tmpCurrentNo, this.getObjectDirection(this.minSize, this.maxSize))
					.fxStart(no, this.getObjectDirection(-this.maxSize, this.minSize))
					.finishFx(manual);
			}
			else				
			{
				this.onProcessing(null, manual, 0, this.maxSize)
					.fxStart(no, this.getObjectDirection(this.maxSize , this.minSize))
					.fxStart(tmpCurrentNo, this.getObjectDirection(this.minSize,  -this.maxSize))
					.finishFx(manual);
			}
		},
		
		next:function(manual , item){
			if( this.onClick ) return ;
			this.currentNo += (this.currentNo < this.slides.length-1) ? 1 : (1 - this.slides.length);

			this.stopAllCaption( this.currentNo );
			if(this.setting.direction.test(/wipe/)){
				this.onProcessing(item, manual, 0, this.maxSize)
					.fxStart(this.currentNo, this.getObjectDirection(this.maxSize ,this.minSize))
					.finishFx(manual);
				this.wipe( this.currentNo.toInt() - 1, this.currentNo );
			}
			else{
				this.onProcessing(item, manual, 0, this.maxSize)
					.fxStart(this.currentNo, this.getObjectDirection(this.maxSize ,this.minSize))
					.fxStart(this.previousNo, this.getObjectDirection(this.minSize, -this.maxSize))
					.finishFx(manual);
			}
		},
		previous:function(manual, item){
			if( this.onClick ) return ;
			this.currentNo += this.currentNo > 0 ? -1 : this.slides.length - 1;

			this.stopAllCaption( this.currentNo );
			if(this.setting.direction.test(/wipe/)){
				this.wipe( this.nextNo, this.currentNo );
				this.onProcessing(item, manual, 0, this.maxSize)
					.fxStart(this.currentNo,  this.getObjectDirection(-this.maxSize, this.minSize))
					.finishFx(manual);
			}
			else{
				this.onProcessing(item, manual, -this.maxWidth, this.minSize)
						.fxStart(this.nextNo, this.getObjectDirection(this.minSize, this.maxSize))
						.fxStart(this.currentNo,  this.getObjectDirection(-this.maxSize, this.minSize))
						.finishFx(manual	);
			}
		},
		
		play:function(delay, direction, wait){
			this.stop(); 
			 if( this.progressbar ){ this.processbar(); }
			if(!wait){ this[direction](false); }
			this.isRun = this[direction].periodical(delay,this,true);
		},stop:function(){ if( this.progressbar ){ this.progressbar.get("morph").stop(); } $clear(this.isRun); }
	});
}