/*
 * 	Easy Slider 1.7 - jQuery plugin
 *	written by Alen Grakalic	
 *	http://cssglobe.com/post/4004/easy-slider-15-the-easiest-jquery-plugin-for-sliding
 *
 *	Copyright (c) 2009 Alen Grakalic (http://cssglobe.com)
 *	Dual licensed under the MIT (MIT-LICENSE.txt)
 *	and GPL (GPL-LICENSE.txt) licenses.
 *
 *	Built for jQuery library
 *	http://jquery.com
 *
 */
 
/*
 *	markup example for $("#slider").easySlider();
 *	
 * 	<div id="slider">
 *		<ul>
 *			<li><img src="images/01.jpg" alt="" /></li>
 *			<li><img src="images/02.jpg" alt="" /></li>
 *			<li><img src="images/03.jpg" alt="" /></li>
 *			<li><img src="images/04.jpg" alt="" /></li>
 *			<li><img src="images/05.jpg" alt="" /></li>
 *		</ul>
 *	</div>
 *
 */

(function($) {

	$.fn.myEasySlider = function(options){
	  
		// default configuration properties
		var defaults = {			
			prevId: 		'prevBtn',
			prevText: 		'Previous',
			nextId: 		'nextBtn',	
			nextText: 		'Next',
			controlsShow:	true,
			controlsBefore:	'',
			controlsAfter:	'',	
			controlsFade:	true,
			firstId: 		'firstBtn',
			firstText: 		'First',
			firstShow:		false,
			lastId: 		'lastBtn',	
			lastText: 		'Last',
			lastShow:		false,				
			vertical:		false,
			speed: 			800,
			auto:			false,
			pause:			2000,
			continuous:		false, 
			numeric: 		false,
			numericId: 		'controls'
		}; 
		
		var options = $.extend(defaults, options);  

		this.each(function() {  
			var obj = $(this); 				
			var s = $("li.slide", obj).length;
			var w = $("li.slide", obj).width(); 

			/* COC SW dynamic calculation of the height */ 
			//	var h = $("li.slide", obj).height(); 
			var h = 0;

			$("li.slide", obj).each(function(index, elem){
				var height = $(elem).height();
				if(height > h ) {
					h = height;
				}
			});
			/* COC End */
			var clickable = true;
			obj.width(w); 
			obj.height(h);
			obj.css("overflow","hidden");
			var ts = s-1;
			var t = 0;
			$("ul.slides", obj).css('width',s*w);			
			
			if(options.continuous){
				$("ul.slides", obj).prepend($("ul li:last-child", obj).clone().css("margin-left","-"+ w +"px"));
				$("ul.slides", obj).append($("ul li:nth-child(2)", obj).clone());
				$("ul.slides", obj).css('width',(s+1)*w);
			};				
			
			if(!options.vertical) {
				$("li.slide", obj).css('float','left');
				
			}
								
			if(options.controlsShow){
				var html = options.controlsBefore;				
				if(options.numeric){
					html += '<ul id="'+ options.numericId +'"></ul>';
					/* COC SW 
					Add next and Prev Buttons too, even when using numeric */
					html += ' <span id="'+ options.prevId +'"><a href=\"javascript:void(0);\">'+ options.prevText +'</a></span>';
					html += ' <span id="'+ options.nextId +'"><a href=\"javascript:void(0);\">'+ options.nextText +'</a></span>';
					/* COC End */
				} else {
					if(options.firstShow) html += '<span id="'+ options.firstId +'"><a href=\"javascript:void(0);\">'+ options.firstText +'</a></span>';
					html += ' <span id="'+ options.prevId +'"><a href=\"javascript:void(0);\">'+ options.prevText +'</a></span>';
					html += ' <span id="'+ options.nextId +'"><a href=\"javascript:void(0);\">'+ options.nextText +'</a></span>';
					if(options.lastShow) html += ' <span id="'+ options.lastId +'"><a href=\"javascript:void(0);\">'+ options.lastText +'</a></span>';				
				};
				
				html += options.controlsAfter;						
				/* COC SW changed container */
					// $(obj).after(html);										
					$("#navigation ul").after(html);										
				/* COC End */					
				
			};
			
			if(options.numeric){									
				for(var i=0;i<s;i++){
					/* COC SW changed Label */
						/* 				
						$(document.createElement("li"))
							.attr('id',options.numericId + (i+1))
							.html('<a rel='+ i +' href=\"javascript:void(0);\">'+ (i+1) +'</a>')
							.appendTo($("#"+ options.numericId))
							.click(function(){							
								animate($("a",$(this)).attr('rel'),true);
							}); 												
						*/
						$(document.createElement("li"))
							.attr('id',options.numericId + (i+1))
							.attr('class','gallery')
							.html('<a rel='+ i +' href=\"javascript:void(0);\">'+ (i+1) +'. Gallery</a>')
							.appendTo($("#"+ options.numericId))
							.click(function(){							
								animate($("a",$(this)).attr('rel'),true);
							});	
							/* COC SW  II
							Add next and Prev Buttons too, even when using numeric */
							
							$("a","#"+options.nextId).click(function(){		
								animate("next",true);
							});
											
							$("a","#"+options.prevId).click(function(){		
								animate("prev",true);				
							});	
							
						
					/* COC End */
				}; 				
			} else {
				$("a","#"+options.nextId).click(function(){		
					animate("next",true);
				});
				$("a","#"+options.prevId).click(function(){		
					animate("prev",true);				
				});	
				$("a","#"+options.firstId).click(function(){		
					animate("first",true);
				});				
				$("a","#"+options.lastId).click(function(){		
					animate("last",true);				
				});				
			};
			
			function setCurrent(i){
				i = parseInt(i)+1;
				$("li", "#" + options.numericId).removeClass("current");
				$("li#" + options.numericId + i).addClass("current");
			};
			
			function adjust(){
				if(t>ts) t=0;		
				if(t<0) t=ts;	
				if(!options.vertical) {
					$("ul.slides",obj).css("margin-left",(t*w*-1));
				} else {
					$("ul.slides",obj).css("margin-left",(t*h*-1));
				}
				clickable = true;
				if(options.numeric) setCurrent(t);
			};
			
			function animate(dir,clicked){
				if (clickable){
					clickable = false;
					var ot = t;		
					switch(dir){
						case "next":
							t = (ot>=ts) ? (options.continuous ? t+1 : ts) : t+1;						
							break; 
						case "prev":
							t = (t<=0) ? (options.continuous ? t-1 : 0) : t-1;
							break; 
						case "first":
							t = 0;
							break; 
						case "last":
							t = ts;
							break; 
						default:
							/* COC SW 
							When using numeric it happens that dir is a String, but the next and prev. buttons need numbers
							*/
							//							t = dir;
							t = parseFloat(dir);
							// COC End
							break; 
					};	
					var diff = Math.abs(ot-t);
					var speed = diff*options.speed;						
					if(!options.vertical) {
						p = (t*w*-1);
						$("ul.slides",obj).animate(
							{ marginLeft: p }, 
							{ queue:false, duration:speed, complete:adjust }
						);				
					} else {
						p = (t*h*-1);
						$("ul.slides",obj).animate(
							{ marginTop: p }, 
							{ queue:false, duration:speed, complete:adjust }
						);					
					};
					
					if(!options.continuous && options.controlsFade){					
						if(t==ts){
							$("a","#"+options.nextId).hide();
							$("a","#"+options.lastId).hide();
						} else {
							$("a","#"+options.nextId).show();
							$("a","#"+options.lastId).show();					
						};
						if(t==0){
							$("a","#"+options.prevId).hide();
							$("a","#"+options.firstId).hide();
						} else {
							$("a","#"+options.prevId).show();
							$("a","#"+options.firstId).show();
						};					
					};				
					
					if(clicked) clearTimeout(timeout);
					if(options.auto && dir=="next" && !clicked){;
						timeout = setTimeout(function(){
							animate("next",false);
						},diff*options.speed+options.pause);
					};
			
				};
				
			};
			// init
			var timeout;
			if(options.auto){;
				timeout = setTimeout(function(){
					animate("next",false);
				},options.pause);
			};		
			

			if(options.numeric) setCurrent(0);

			animate("last",false);
		
			if(!options.continuous && options.controlsFade){					
				$("a","#"+options.prevId).hide();
				$("a","#"+options.firstId).hide();				
			};				
			
		});
	  
	};

})(jQuery);



