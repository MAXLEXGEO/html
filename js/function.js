/*--------------------------------------
		CUSTOM FUNCTION WRITE HERE		
--------------------------------------*/
"use strict";
jQuery(document).on('ready', function() {
	/*--------------------------------------
			MOBILE MENU						
	--------------------------------------*/
	function collapseMenu(){
		jQuery('.at-navigation ul li.menu-item-has-children, .at-navigation ul li.menu-item-has-mega-menu').prepend('<span class="at-dropdowarrow"><i class="fa fa-angle-down"></i></span>');
		jQuery('.at-navigation ul li.menu-item-has-children span, .at-navigation ul li.menu-item-has-mega-menu span').on('click', function() {
			jQuery(this).parent('li').toggleClass('at-open');
			jQuery(this).next().next().slideToggle(300);
		});
	}
	collapseMenu();
	/*--------------------------------------
			MEGA MENU						
	--------------------------------------*/
	jQuery(function ($) {
		function hoverIn() {
			var a = jQuery(this);
			var nav = a.closest('.at-navigation');
			var mega = a.find('.mega-menu');
			var offset = rightSide(nav) - leftSide(a);
			mega.width(Math.min(rightSide(nav), columns(mega)*230));
			mega.css('left', Math.min(0, offset - mega.width()));
		}
		function hoverOut() {}
		function columns(mega) {
			var columns = 0;
			mega.children('.mega-menu-row').each(function () {
				columns = Math.max(columns, jQuery(this).children('.mega-menu-col').length);
			});
			return columns;
		}
		function leftSide(elem) {
			return elem.offset().left;
		}
		function rightSide(elem) {
			return elem.offset().left + elem.width();
		}
		jQuery('.menu-item-has-mega-menu').hover(hoverIn, hoverOut);
	});
	/*--------------------------------------
			THEME ACCORDION 				
	--------------------------------------*/
	if(jQuery('.at-panelheading').length > 0){
		var _tg_panelheading = jQuery('.at-panelheading');
		_tg_panelheading.on('click',function () {
			jQuery('.panel-heading').removeClass('at-active');
			jQuery(this).parents('.panel-heading').addClass('at-active');
			jQuery('.panel').removeClass('at-active');
			jQuery(this).parent().addClass('at-active');
		});
	}
	jQuery('.panel-collapse').on('show.bs.collapse', function () {
		jQuery(this).siblings('.panel-heading').addClass('at-active');
	});
	jQuery('.panel-collapse').on('hide.bs.collapse', function () {
		jQuery(this).siblings('.panel-heading').removeClass('at-active');
	});
	/* -------------------------------------
			HOME SLIDER V ONE
	-------------------------------------- */
	if(jQuery('#at-homeslider').length > 0){
		jQuery('#at-homeslider').owlCarousel({
			items: 1,
			nav:true,
			loop:false,
			dots: false,
			autoplay: true,
			dotsClass: 'at-sliderdots',
			navClass: ['at-prev', 'at-next'],
			navContainerClass: 'at-slidernav',
		});
	}
	/* -------------------------------------
			FEATURES SLIDER V ONE
	-------------------------------------- */
	if(jQuery('#at-featuresslider').length > 0){
		jQuery('#at-featuresslider').owlCarousel({
			items: 3,
			nav: true,
			margin: 30,
			loop: true,
			dots: false,
			center: true,
			autoplay: true,
			dotsClass: 'at-sliderdots',
			navClass: ['at-prev', 'at-next'],
			navContainerClass: 'at-slidernav',
			responsive: {
				0: {items: 1,},
				640: {items: 2,},
				1441: {items: 3,},
			}
		});
	}
	/* -------------------------------------
			SPECIAL SERVICES SLIDER
	-------------------------------------- */
	if(jQuery('#at-servicesslider').length > 0){
		jQuery('#at-servicesslider').owlCarousel({
			items: 3,
			nav: true,
			margin: 30,
			loop: true,
			dots: false,
			autoplay: true,
			dotsClass: 'at-sliderdots',
			navClass: ['at-prev', 'at-next'],
			navContainerClass: 'at-slidernav',
			responsive: {
				0: {items: 1,},
				768: { items: 2, },
				901: { items: 3, },
			}
		});
	}
	/* -------------------------------------
			WHAT WE DO SLIDER
	-------------------------------------- */
	if(jQuery('#at-whatwedoslider').length > 0){
		jQuery('#at-whatwedoslider').owlCarousel({
			items: 2,
			nav: true,
			margin: 30,
			loop: true,
			dots: true,
			autoplay: true,
			dotsClass: 'at-sliderdots',
			navClass: ['at-prev', 'at-next'],
			navContainerClass: 'at-slidernav',
			responsive: {
				0: { items: 1, },
				768: { items: 2, },
			}
		});
	}
	/* -------------------------------------
			TESTIMONIALS SLIDER
	-------------------------------------- */
	if(jQuery('#at-testimonialslider').length > 0){
		jQuery('#at-testimonialslider').owlCarousel({
			items: 3,
			nav: true,
			margin: 30,
			loop: true,
			dots: true,
			autoplay: true,
			dotsClass: 'at-sliderdots',
			navClass: ['at-prev', 'at-next'],
			navContainerClass: 'at-slidernav',
			responsive: {
				0: { items: 1, },
				400: { items: 2, },
				768: { items: 3, }
			}
		});
	}
	/* -------------------------------------
			ADDRESS SLIDER
	-------------------------------------- */
	if (jQuery('#at-addressslider').length > 0) {
		jQuery('#at-addressslider').owlCarousel({
			items: 1,
			nav: true,
			loop: true,
			dots: true,
			autoplay: true,
			dotsClass: 'at-sliderdots',
			navClass: ['at-prev', 'at-next'],
			navContainerClass: 'at-slidernav',
		});
	}
	/*--------------------------------------
			COUNTER							
	--------------------------------------*/
	if(jQuery('#at-counters').length > 0){
		var _tg_counters = jQuery('#at-counters');
		_tg_counters.appear(function () {
			jQuery('.at-scounter > h3').countTo()
		});
	}
	/*--------------------------------------
			PROGRESS BAR
	--------------------------------------*/
	if(jQuery('#at-ourskill').length > 0){
		var _tg_ourskill = jQuery('#at-ourskill');
		_tg_ourskill.appear(function () {
			jQuery('.skill-holder').each(function () {
				jQuery(this).find('.skill-bar').animate({
					width: jQuery(this).attr('data-percent')
				}, 2500);
			});
		});
	}
	/*--------------------------------------
			Google Map
	--------------------------------------*/
	if(jQuery('#at-locationmap').length > 0){
		var gmapStyles = [
			{"featureType": "administrative", "elementType": "labels.text.fill", "stylers": [{"color": "#444444"}]},
			{"featureType": "landscape", "elementType": "all", "stylers": [{"color": "#f2f2f2"}]},
			{"featureType": "poi", "elementType": "all", "stylers": [{"visibility": "off"}]},
			{"featureType": "road", "elementType": "all", "stylers": [{"saturation": -100}, {"lightness": 45}]},
			{"featureType": "road.highway","elementType": "all","stylers": [{"visibility": "simplified"}]},
			{"featureType": "road.arterial", "elementType": "labels.icon", "stylers": [{"visibility": "off"}]},
			{"featureType": "transit","elementType": "all","stylers": [{"visibility": "off"}]},
			{"featureType": "water","elementType": "all","stylers": [{"color": "#46bcec"},{"visibility": "on"}]},
			{"featureType": "water","elementType": "geometry.fill","stylers": [{"color": "#47bcec"}]}
		];
		var _tg_locationmap = jQuery('#at-locationmap');
		_tg_locationmap.gmap3({
			marker: {
				address: 'Manantial, Santiago Etla, Oaxaca',
				options: {
					title: 'ADMine Technology',
					icon: "images/pin.png",
					animation: google.maps.Animation.BOUNCE,
				}
			},
			map: {
				options: {
					zoom: 12,
					styles: gmapStyles,
					scaleControl: true,
					scrollwheel: false,
					mapTypeControl: false,
					disableDefaultUI: true,
					navigationControl: false,
					streetViewControl: false,
					disableDoubleClickZoom: true,
				}
			}
		});
	}
	/*--------------------------------------
			PRETTY PHOTO GALLERY			
	--------------------------------------*/
	if(jQuery('#at-gallery').length > 0){
		jQuery('a[data-rel]').each(function () {
			jQuery(this).attr('rel', jQuery(this).data('rel'));
		});
		jQuery('a[data-rel^="prettyPhoto"]').prettyPhoto({
			animation_speed: 'normal',
			theme: 'dark_square',
			slideshow: 3000,
			autoplay_slideshow: false,
			social_tools: false
		});
	}
	/*--------------------------------------
			MASONRY GALLERY					
	--------------------------------------*/
	jQuery('#at-gallery').isotope({
		itemSelector: '.at-griditem',
		percentPosition: true,
		masonry: {
			columnWidth: '.at-griditem'
		}
	});
	/*---------------------------------------
			SKILLS PROGRESS BAR				
	---------------------------------------*/
	jQuery('#at-ourskill').appear(function () {
		jQuery('.at-skillholder').each(function () {
			jQuery(this).find('.at-skillbar').animate({
				width: jQuery(this).attr('data-percent')
			}, 2500);
		});
	});
	/*---------------------------------------
			LINE CHART						
	---------------------------------------*/
	if (jQuery('#at-linechart').length > 0) {
		var chartId = document.getElementById("at-linechart");
		var at_linechart = new Chart(chartId, {
			type: 'line',
			data: {
				labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
				datasets: [{
					label: 'One',
					data: [10, 15, 20, 25, 28, 25, 23, 25, 23, 25, 28, 25, 20, 15, 10],
					backgroundColor: [
						'rgba(254, 96, 169, 0.2)',
					],
					borderColor: [
						'#fe60a9',
					],
					borderWidth: 2
				},{
					label: 'Two',
					data: [25, 20, 15, 20, 30, 40, 30, 25, 20, 15, 20, 25],
					backgroundColor: [
						'rgba(84, 181, 211, 0.2)',
					],
					borderColor: [
						'#54b5d3',
					],
					borderWidth: 2
				}]
			},
			options: {
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero:true
						}
					}]
				},
				legend: {
					position: 'bottom',
					fullWidth: 'true'
				}
			}
		});
	}
	/*---------------------------------------
			DOUGHNUT CHART  				
	---------------------------------------*/
	if (jQuery('#at-doughnutchartone').length > 0) {
		var doughnutchartone = document.getElementById("at-doughnutchartone").getContext('2d');
		var at_doughnutchartone = new Chart(doughnutchartone, {
			type: 'doughnut',
			data: {
				labels: ["Red", "Green", "Yellow"],
				datasets: [{
					backgroundColor: [
						"#ffbf66",
						"#53b4d1",
						"#fe60a8"
					],
					data: [33, 33, 34]
				}]
			},
			options:{
				responsive: true,
				cutoutPercentage: 65,
				legend: {
					position: 'bottom',
					fullWidth: 'true'
				}
			}
		});
	}
	/*---------------------------------------
			DOUGHNUT CHART  				
	---------------------------------------*/
	if (jQuery('#at-doughnutchartwo').length > 0) {
		var doughnutcharttwo = document.getElementById("at-doughnutchartwo").getContext('2d');
		var at_doughnutcharttwo = new Chart(doughnutcharttwo, {
			type: 'doughnut',
			data: {
				labels: ["Red", "Green"],
				datasets: [{
					backgroundColor: [
						"#ffbf66",
						"#53b4d1",
					],
					data: [50, 50]
				}]
			},
			options:{
				responsive: true,
				cutoutPercentage: 65,
				legend: {
					position: 'bottom',
					fullWidth: 'true'
				}
			}
		});
	}
});