/**
 * AQPB js
 *
 * contains the core js functionalities to be used
 * inside AQPB
 */

jQuery.noConflict();

/** Fire up jQuery - let's dance! **/
jQuery(document).ready(function($){

	
	/** Variables 
	------------------------------------------------------------------------------------**/
	
	var block_archive, 
		block_number, 
		parent_id, 
		block_id, 
		intervalId,
		resizable_args = {
				grid: 62,
			handles: 'w,e',
			maxWidth: 724,
			minWidth: 104,
			resize: function(event, ui) { 
			    ui.helper.css("height", "inherit");
			},
			stop: function(event, ui) {
				ui.helper.css('left', ui.originalPosition.left);
				ui.helper.removeClass (function (index, css) {
				    return (css.match (/\bspan\S+/g) || []).join(' ');
				}).addClass(block_size( $(ui.helper).css('width') ));
				ui.helper.find('> div > .size').val(block_size( $(ui.helper).css('width') ));
			}
		},
		tabs_width = $('.aqpb-tabs').outerWidth(), 
		mouseStilldown = false,
		max_marginLeft = 720 - Math.abs(tabs_width),
		activeTab_pos = $('.aqpb-tab-active').next().position(),
		act_mleft,
		$parent, 
		$clicked;
	
	
	/** Functions 
	------------------------------------------------------------------------------------**/

	function icon_fonts()
	{
		input_name='';
		$(".font-awesome-icon-select").click( function() { 
			$(this).parent().parent().parent().parent().parent().parent().parent().find('.font-awesome-select').css("display","block");
			input_name=$(this).parent().find('.awesome-fonts-input').attr("name");
		});
		

		$(".font-awesome-select ul li").click( function() { 
			awesome_class=$(this).children("i").attr("class");
			$(".font-awesome-select").css("display","none");
			$("input[name='"+input_name+"']").val(awesome_class);
		});
		
		$(".font-awesome-plus").click( function() { 
			aw_font_size=$(".font-awesome-select ul li").css('font-size');
			$(".font-awesome-select ul li").css('font-size',parseInt(aw_font_size)+1);
		});	

		$(".font-awesome-minus").click( function() { 
			aw_font_size=$(".font-awesome-select ul li").css('font-size');
			$(".font-awesome-select ul li").css('font-size',parseInt(aw_font_size)-1);
		});
		
		$(".font_awesome_close").click( function() { 
			$(".font-awesome-select").css("display","none");
		});	
	}
	
	
		function image_fonts()
	{
		input_name='';
		$(".image-icon-select").click( function() { 
			$(this).parent().parent().parent().parent().parent().parent().parent().find('.select-image-icons').css("display","block");
			input_name=$(this).parent().find('.image-icons-input').attr("name");
		});
		

		$(".select-image-icons ul li").click( function() { 
			image_icons_class=$(this).children("img").attr("src");
			$(".select-image-icons").css("display","none");
			$("input[name='"+input_name+"']").val(image_icons_class);
		});
		
		$(".image_icons_close").click( function() { 
			$(".select-image-icons").css("display","none");
		});	
	}
	
	/** create unique id **/
	function makeid()
	{
	    var text = "";
	    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	
	    for( var i=0; i < 5; i++ )
	        text += possible.charAt(Math.floor(Math.random() * possible.length));
	
	    return text;
	}
	
	/** Get correct class for block size **/
	function block_size(width) {
		var span = "span12";
		
		width = parseInt(width);
		
		if (width > 0 && width < 130){ span = "span2"; }
		else if (width == 166){ span = "span3"; }
		else if (width == 228){ span = "span4"; }
		else if (width == 290){ span = "span5"; }
		else if (width == 352){ span = "span6"; }
		else if (width == 414){ span = "span7"; }
		else if (width == 476){ span = "span8"; }
		else if (width == 538){ span = "span9"; }
		else if (width == 600){ span = "span10"; }
		else if (width == 662){ span = "span11"; }
		else if (width == 724){ span = "span12"; }
		
		return span;
	}
	
	/** Blocks resizable dynamic width **/
	function resizable_dynamic_width(blockID) {
		var blockPar = $('#' + blockID).parent(),
			maxWidth = parseInt($(blockPar).parent().parent().css('width'));
		
		//set maxWidth for blocks inside columns
		if($(blockPar).hasClass('column-blocks')) {
			$('#' + blockID + '.ui-resizable').resizable( "option", "maxWidth", maxWidth );
		}
		
		//set widths when the parent resized
		$('#' + blockID).bind( "resizestop", function(event, ui) {
			if($('#' + blockID).hasClass('block-container')) {
				var $blockColumn = $('#' + blockID),
					new_maxWidth = parseInt($blockColumn.css('width'));
					child_maxWidth = new Array();
					
				//reset maxWidth for child blocks
				$blockColumn.find('ul.blocks > li').each(function() {
					child_blockID = $(this).attr('id');
					$('#' + child_blockID + '.ui-resizable').resizable( "option", "maxWidth", new_maxWidth );
					child_maxWidth.push(parseInt($('#' + child_blockID).css('width')));
				});
				
				//get maxWidth of child blocks, use it to set the minWidth for column
				var minWidth = Math.max.apply( Math, child_maxWidth );
				$('#' + blockID + '.ui-resizable').resizable( "option", "minWidth", minWidth );
			}
		});
		
	}
	
	/** Update block order **/
	function update_block_order() {
		$('ul.blocks').each( function() {
			$(this).children('li.block').each( function(index, el) {
				$(el).find('.order').last().val(index + 1);
				
				if($(el).parent().hasClass('column-blocks')) {
					parent_order = $(el).parent().siblings('.order').val();
					$(el).find('.parent').last().val(parent_order);
				} else {
					$(el).find('.parent').last().val(0);
					if($(el).hasClass('block-container')) {
						block_order = $(el).find('.order').last().val();
						$(el).find('li.block').each(function(index,elem) {
							$(elem).find('.parent').val(block_order);
						});
					}
				}
				
			});
		});
	}
	
	/** Update block number **/
	function update_block_number() {
		$('ul.blocks li.block').each( function(index, el) {
			$(el).find('.number').last().val(index + 1);
		});
	}
	
	function columns_sortable() {
		//$('ul#blocks-to-edit, .block-container ul.blocks').sortable('disable');
		$('#page-builder .column-blocks, .block-container').sortable({
			placeholder: 'placeholder',
			connectWith: '#blocks-to-edit, .column-blocks',
			items: 'li.block'
		});
	}
	
	/** Menu functions **/
	function moveTabsLeft() {
		if(max_marginLeft < $('.aqpb-tabs').css('margin-left').replace("px", "") ) {
			$('.aqpb-tabs').animate({'marginLeft': ($('.aqpb-tabs').css('margin-left').replace("px", "") - 7) + 'px' }, 
			1, 
			function() {
				if(mouseStilldown) {
					moveTabsLeft();
				}
			});
		}
	}
	
	function moveTabsRight() {
		if($('.aqpb-tabs').css('margin-left').replace("px", "") < 0) {
			$('.aqpb-tabs').animate({'marginLeft': Math.abs($('.aqpb-tabs').css('margin-left').replace("px", ""))*(-1) + 7 + 'px' }, 
			1, 
			function() {
				if(mouseStilldown) {
					moveTabsRight();
				}
			});
		}
	}
	
	function centerActiveTab() {
		if($('.aqpb-tab-active').hasClass('aqpb-tab-add')) {
			act_mleft = 690 - $('.aqpb-tab-active').position().left - $('.aqpb-tab-active').width();
			$('.aqpb-tabs').css('margin-left' , act_mleft + 'px');
		} else
		if(720 < activeTab_pos.left) {
			act_mleft = 730 - activeTab_pos.left;
			$('.aqpb-tabs').css('margin-left' , act_mleft + 'px');
		}
	}
	
	/** Actions
	------------------------------------------------------------------------------------**/
	/** Apply CSS float:left to blocks **/
	$('li.block').css('float', 'none');
	
	/** Open/close blocks **/
	$(document).on('click', '#page-builder a.block-edit', function() {
		var blockID = $(this).parents('li').attr('id');
		$('#' + blockID + ' .block-settings').slideToggle('fast');
		
		if( $('#' + blockID).hasClass('block-edit-active') == false ) {
			$('#' + blockID).addClass('block-edit-active');
		} else {
			$('#' + blockID).removeClass('block-edit-active');
		};
		
		return false;
	});
	
	/** Open/close Columns **/
	$(document).on('click', 'button.column_size_edit', function() {
		var li_content=$(this).parent();
		li_content.children().not('h2').not('.column_size_edit').slideToggle('fast');
		if (li_content.children('.column_size_edit').html()=='Colapse') 
		li_content.children('.column_size_edit').html("Expand"); 
		else li_content.children('.column_size_edit').html("Colapse"); 
		//alert(li_content);
	});	
	
	/** Blocks resizable **/
	$('ul.blocks li.block').each(function() {
		var blockID = $(this).attr('id'),
			blockPar = $(this).parent();
			
		//blocks resizing
		$('#' + blockID).resizable(resizable_args);
		
		//set dynamic width for blocks inside columns
		resizable_dynamic_width(blockID);
		
		//trigger resize
		$('#' + blockID).trigger("resize");
		$('#' + blockID).trigger("resizestop");
		
		//disable resizable on .not-resizable blocks
		$(".ui-resizable.not-resizable").resizable("destroy");
		
	});
	
	/** Blocks draggable (archive) **/
	$('#blocks-archive > li.block').each(function() {
		$(this).draggable({
			connectToSortable: "#blocks-to-edit",
			helper: 'clone',
			revert: 'invalid',
			start: function(event, ui) {
				block_archive = $(this).attr('id');
			}
		});
	});
	
	/** Blocks sorting (settings) **/
	$('#blocks-to-edit').sortable({
		placeholder: "placeholder",
		handle: '.block-handle, .block-settings-column',
		connectWith: '#blocks-archive, .column-blocks',
		items: 'li.block'
	});
	
	/** Columns Sortable **/
	columns_sortable();
	
	/** Sortable bindings **/
	$( "ul.blocks" ).bind( "sortstart", function(event, ui) {
		ui.placeholder.css('width', ui.helper.css('width'));
		ui.placeholder.css('height', ( ui.helper.css('height').replace("px", "") - 13 ) + 'px' );
		$('.empty-template').remove();
	});
	
	$( "ul.blocks" ).bind( "sortstop", function(event, ui) {
		
		//if coming from archive
		if (ui.item.hasClass('ui-draggable')) {
		
			//remove draggable class
		    ui.item.removeClass('ui-draggable');
		    
		    //set random block id
		    block_number = makeid();
		    
		    //replace id
		    ui.item.html(ui.item.html().replace(/<[^<>]+>/g, function(obj) {
		        return obj.replace(/__i__|%i%/g, block_number)
		    }));
		    
		    ui.item.attr("id", block_archive.replace("__i__", block_number));
		    
		    //if column, remove handle bar
		    if(ui.item.hasClass('block-container')) {
		    	ui.item.find('.block-bar').remove();
		    	ui.item.find('.block-settings').removeClass('block-settings').addClass('block-settings-column');
		    }
		    
		    //init resize on newly added block
		    ui.item.resizable(resizable_args);
		    
		    //set dynamic width for blocks inside columns
		    resizable_dynamic_width(ui.item.attr('id'));
		    
		    //trigger resize
		    ui.item.trigger("resize");
		    ui.item.trigger("resizestop");
		    
		    //open on drop
		    ui.item.find('a.block-edit').click();
		    
		    //disable resizable on .not-resizable blocks
		    $(".ui-resizable.not-resizable").resizable("destroy");
			icon_fonts();
			image_fonts();
		}
		
		//if moving column inside column, cancel it
		if(ui.item.hasClass('block-container')) {
			$parent = ui.item.parent()
			if( $parent.hasClass('block-container') || $parent.hasClass("column-blocks") ) { 
				$(this).sortable('cancel');
				return false;
			}
			columns_sortable();
		}
		
		//@todo - resize column to maximum width of dropped item
		
		//update order & parent ids
		update_block_order();
		
		//update number
		update_block_number();
	
	});
	
	/** Blocks droppable (removing blocks) **/
	$('#page-builder-archive').droppable({
		accept: "#blocks-to-edit .block",
		tolerance: "pointer",
		over : function(event, ui) {
			$(this).find('#removing-block').fadeIn('fast');
			ui.draggable.parent().find('.placeholder').hide();
		},
		out : function(event, ui) {
			$(this).find('#removing-block').fadeOut('fast');
			ui.draggable.parent().find('.placeholder').show();
		},
		drop: function(ev, ui) {
	        ui.draggable.remove();
	        $(this).find('#removing-block').fadeOut('fast');
		}
	});
	
	/** Delete Block (via "Delete" anchor) **/
	$(document).on('click', '.block-control-actions a', function() {
		$clicked = $(this);
		$parent = $(this.parentNode.parentNode.parentNode);
		
		if($clicked.hasClass('delete')) {
			$parent.find('> .block-bar .block-handle').css('background', 'red');
			$parent.slideUp(function() {
				$(this).remove();
				update_block_order();
				update_block_number();
			}).fadeOut('fast');
		} else if($clicked.hasClass('close')) {
			$parent.find('> .block-bar a.block-edit').click();
		}
		return false;
	});
	
	/** Disable blocks archive if no template **/
	$('#page-builder-column.metabox-holder-disabled').click( function() { return false })
	$('#page-builder-column.metabox-holder-disabled #blocks-archive .block').draggable("destroy");
	
	/** Confirm delete template **/
	$('a.template-delete').click( function() { 
		var agree = confirm('You are about to permanently delete this template. \'Cancel\' to stop, \'OK\' to delete.');
		if(agree) { return } else { return false }
	});
	
	/** Cancel template save/create if no template name **/
	$('#save_template_header, #save_template_footer').click(function() {
		var template_name = $('#template-name').val().trim();
		if(template_name.length === 0) {
			$('.major-publishing-actions .open-label').addClass('form-invalid');
			return false;
		}
	});
	
	/** Nav tabs scrolling **/
	if(720 < tabs_width) {
		$('.aqpb-tabs-arrow').show();
		centerActiveTab();
		$('.aqpb-tabs-arrow-right a').mousedown(function() {
			mouseStilldown = true;
		    moveTabsLeft();
		}).bind('mouseup mouseleave', function() {
		    mouseStilldown = false;
		});
		
		$('.aqpb-tabs-arrow-left a').mousedown(function() {
			mouseStilldown = true;
		    moveTabsRight();
		}).bind('mouseup mouseleave', function() {
		    mouseStilldown = false;
		});
		
	}
	
	/** Sort nav order **/
	$('.aqpb-tabs').sortable({
		items: '.aqpb-tab-sortable',
		axis: 'x',
	});
	
	$('.aqpb-tabs').on('sortstop', function() {
		
		var data = {
			action: 'aq_page_builder_sort_templates',
			security: $('#aqpb-nonce').val(),
			templates: $(this).sortable('serialize')
		};
		
		$.post(ajaxurl, data, function(response) {
		
			if(response == '-1') { // check nonce
				alert('An unknown error has occurred');
			} else {
				// alert(response);
			}
						
		});
	});
	
	/** Apply CSS float:left to blocks **/
	$('li.block').css('float', '');
	
	/** prompt save on page change **
	var aqpb_html = $('#update-page-template').html();
	$(window).bind('beforeunload', function(e) {
		var aqpb_html_new = $('#update-page-template').html();
		if(aqpb_html_new != aqpb_html) { 
			return "The changes you made will be lost if you navigate away from this page.";
		}
	}); */

// what fish?

	//custom code for font-awesone select inside page builder
	icon_fonts();
	image_fonts();
	
	/*Colapsed columns from beginning*/
	$(".block-settings-column ").children().not('h2').not('.column_size_edit').slideToggle('fast');

	/*Block filter*/
	/*
	$( "ul#blocks-archive li" ).each(function( index ) {
	if ($(this).attr('class').indexOf("bf_")>0)
	  alert($(this).attr('class'));
	});	
	
	bf_html='';
	var filter_arr=[];
	$( "#page-builder-archive li[class^='bf_'],#page-builder-archive li[class*=' bf_']" ).each(function( index ) {
		var str_start = $(this).attr('class');
		var n = str_start.indexOf("bf_",5);	  
		var m = str_start.indexOf(" ",n);
	  bf_class=str_start.substring(n, m);
	  bf_class=bf_class.replace('bf_','');
	  if (jQuery.inArray( bf_class, filter_arr )<0)
		{
			filter_arr.push(bf_class);
		}
	  
	  
	});	
	
	$.each(filter_arr, function( index, value ) {
	  //alert( index + ": " + value );
	  bf_html+='<li><a href="#">'+value+'</a></li>';
	});

	 bf_html='<ul><li><a href="#">All</a></li>'+ bf_html+'</ul>';
	 $(".page-builder-filter").html(bf_html);

	$( ".page-builder-filter li a" ).click(function() {
		var bf_class=$(this).text();
		if (bf_class=='All')
			{
				bf_class=".bf_"+bf_class;
				$("ul#blocks-archive li").css("display",'block');
			}
		else {
				bf_class=".bf_"+bf_class;
				//alert(bf_class);
				$("ul#blocks-archive li").not(bf_class).css("display",'none');
			}
	});	*/	
	
	$("#hide").click(function(){
		$(".promo_box").hide();
	});
	





/*!
 * jQuery Cookie Plugin v1.4.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2006, 2014 Klaus Hartl
 * Released under the MIT license
 */
(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD
		define(['jquery'], factory);
	} else if (typeof exports === 'object') {
		// CommonJS
		factory(require('jquery'));
	} else {
		// Browser globals
		factory(jQuery);
	}
}(function ($) {

	var pluses = /\+/g;

	function encode(s) {
		return config.raw ? s : encodeURIComponent(s);
	}

	function decode(s) {
		return config.raw ? s : decodeURIComponent(s);
	}

	function stringifyCookieValue(value) {
		return encode(config.json ? JSON.stringify(value) : String(value));
	}

	function parseCookieValue(s) {
		if (s.indexOf('"') === 0) {
			// This is a quoted cookie as according to RFC2068, unescape...
			s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
		}

		try {
			// Replace server-side written pluses with spaces.
			// If we can't decode the cookie, ignore it, it's unusable.
			// If we can't parse the cookie, ignore it, it's unusable.
			s = decodeURIComponent(s.replace(pluses, ' '));
			return config.json ? JSON.parse(s) : s;
		} catch(e) {}
	}

	function read(s, converter) {
		var value = config.raw ? s : parseCookieValue(s);
		return $.isFunction(converter) ? converter(value) : value;
	}

	var config = $.cookie = function (key, value, options) {

		// Write

		if (arguments.length > 1 && !$.isFunction(value)) {
			options = $.extend({}, config.defaults, options);

			if (typeof options.expires === 'number') {
				var days = options.expires, t = options.expires = new Date();
				t.setTime(+t + days * 864e+5);
			}

			return (document.cookie = [
				encode(key), '=', stringifyCookieValue(value),
				options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path    ? '; path=' + options.path : '',
				options.domain  ? '; domain=' + options.domain : '',
				options.secure  ? '; secure' : ''
			].join(''));
		}

		// Read

		var result = key ? undefined : {};

		// To prevent the for loop in the first place assign an empty array
		// in case there are no cookies at all. Also prevents odd result when
		// calling $.cookie().
		var cookies = document.cookie ? document.cookie.split('; ') : [];

		for (var i = 0, l = cookies.length; i < l; i++) {
			var parts = cookies[i].split('=');
			var name = decode(parts.shift());
			var cookie = parts.join('=');

			if (key && key === name) {
				// If second argument (value) is a function it's a converter...
				result = read(cookie, value);
				break;
			}

			// Prevent storing a cookie that we couldn't decode.
			if (!key && (cookie = read(cookie)) !== undefined) {
				result[name] = cookie;
			}
		}

		return result;
	};

	config.defaults = {};

	$.removeCookie = function (key, options) {
		if ($.cookie(key) === undefined) {
			return false;
		}

		// Must not alter options, thus extending a fresh object...
		$.cookie(key, '', $.extend({}, options, { expires: -1 }));
		return !$.cookie(key);
	};

}));




	// if no cookie
if (!$.cookie('alert')) {
    $( ".promo_box" ).show();
    $("#hide").click(function() {
        $( ".promo_box" ).slideUp( "slow" );
        // set the cookie
        var date = new Date();
		var minutes = 120;
		date.setTime(date.getTime() + (minutes * 60 * 1000));
       // date.setTime(date.getTime() + 24 * 60 * 60 * 1000); 
        $.cookie('alert', true, { expires: date });
    });
}


	
});

	
	