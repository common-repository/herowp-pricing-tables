/**
 * AQPB Fields JS
 *
 * JS functionalities for some of the default fields
 */

jQuery.noConflict();

/** Fire up jQuery - let's dance! 
 */
jQuery(document).ready(function($){

	/** Colorpicker Field
	----------------------------------------------- */
	function aqpb_colorpicker() {
		$('#page-builder .input-color-picker').each(function(){
			var $this	= $(this),
				parent	= $this.parent();
				
			$this.wpColorPicker();
		});
	}
	
	aqpb_colorpicker();
	
	$('ul.blocks').bind('sortstop', function() {
		aqpb_colorpicker();
	});
	
	/** Media Uploader
	----------------------------------------------- */	
	$(document).on('click', '.aq_upload_button', function(event) {
		var $clicked = $(this), frame,
			input_id = $clicked.prev().attr('id'),
			media_type = $clicked.attr('rel');
		event.preventDefault();
		
		// If the media frame already exists, reopen it.
		if ( frame ) {
			frame.open();
			return;
		}
		
		// Create the media frame.
		frame = wp.media.frames.aq_media_uploader = wp.media({
			// Set the media type
			library: {
				type: media_type
			},
			view: {
				
			}
		});
		
		// When an image is selected, run a callback.
		frame.on( 'select', function() {
			// Grab the selected attachment.
			var attachment = frame.state().get('selection').first();
			
			$('#' + input_id).val(attachment.attributes.url);
			
			if(media_type == 'image') $('#' + input_id).parent().parent().parent().find('.screenshot img').attr('src', attachment.attributes.url);
			
		});

		frame.open();
	
	});
			
	/** Sortable Lists
	----------------------------------------------- */
	// AJAX Add New <list-item>
	function aq_sortable_list_add_item(action_id, items) {

		var blockID = items.attr('rel'),
			numArr = items.find('li').map(function(i, e){
				return $(e).attr("rel");
			});
		
		var maxNum = Math.max.apply(Math, numArr);
		if (maxNum < 1 ) { maxNum = 0};
		var newNum = maxNum + 1;

		var data = {
			action: 'aq_block_'+action_id+'_add_new',
			security: $('#aqpb-nonce').val(),
			count: newNum,
			block_id: blockID
		};
		
		$.post(ajaxurl, data, function(response) {
			var check = response.charAt(response.length - 1);
			
			//check nonce
			if(check == '-1') {
				alert('An unknown error has occurred');
			} else {
				items.append(response);
				aqpb_image_fonts();
				aqpb_icon_fonts()
			}
				aqpb_colorpicker();			
		});
		
	};

	function aqpb_image_fonts()
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

	function aqpb_icon_fonts()
	{
		input_name='';
		$(".font-awesome-icon-select").on( 'click', function() { 
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
	
	
	// Initialise sortable list fields
	function aq_sortable_list_init() {
		$('.aq-sortable-list').sortable({
			containment: "parent",
			placeholder: "ui-state-highlight"
		});
	}
	aq_sortable_list_init();
	
	$('ul.blocks').bind('sortstop', function() {
		aq_sortable_list_init();
	});
	
	
	$(document).on('click', 'a.aq-sortable-add-new', function() {
		var action_id = $(this).attr('rel'),
			items = $(this).parent().children('ul.aq-sortable-list');
		
		
		aq_sortable_list_add_item(action_id, items);
		aq_sortable_list_init
		return false;
	});
	
	// Delete Sortable Item
	$(document).on('click', '.aq-sortable-list a.sortable-delete', function() {
		var $parent = $(this.parentNode.parentNode.parentNode);
		$parent.children('.block-tabs-tab-head').css('background', 'red');
		$parent.slideUp(function() {
			$(this).remove();
		}).fadeOut('fast');
		return false;
	});
	
	// Open/Close Sortable Item
	$(document).on('click', '.aq-sortable-list .sortable-handle a', function() {
		var $clicked = $(this);
		
		$clicked.addClass('sortable-clicked');
		
		$clicked.parents('.aq-sortable-list').find('.sortable-body').each(function(i, el) {
			if($(el).is(':visible') && $(el).prev().find('a').hasClass('sortable-clicked') == false) {
				$(el).slideUp();
			}
		});
		$(this.parentNode.parentNode.parentNode).children('.sortable-body').slideToggle();
		
		$clicked.removeClass('sortable-clicked');
		
		return false;
	});
	
	
		
});