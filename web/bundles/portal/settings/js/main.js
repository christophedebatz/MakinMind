var settings_block = '<div class="color_picker_wrapper"><div id="color_picker" class="block_color_picker">\
    	<div class="title">\
			<p>Choose your style</p>\
        </div>\
		\
		<p>Choose Layout Style</p>\
		<div id="theme_layout" class="picker_list"></div>\
		\
		<p>Patterns for Boxed Version</p>\
		<div id="theme_layout_bg" class="picker_pics"></div>\
		\
		<p>Choose General Skins</p>\
		<div id="theme" class="picker_list"></div>\
		\
		<p>Choose Color Scheme</p>\
		<div id="theme_color" class="picker_pics"></div>\
		\
		<p>Choose Footer Skins</p>\
		<div id="theme_footer" class="picker_list"></div>\
		\
		<div class="picker_controls">\
			<a href="#" id="reset">Reset styles</a><a href="#" id="apply">Apply</a>\
		</div>\
		\
        <a href="#" id="picker_close"><img src="settings/images/trans.gif" alt="" /></a>\
    </div></div>';
$(function(){$('body').prepend(settings_block);});

var the_settings = {
	'theme_layout' : ['Wide', 'Boxed'],
	'theme_layout_bg' : [
				  'type_1',
				  'type_2',
				  'type_3',
				  'type_4',
				  'type_5',
				  'type_6',
				  'type_7',
				  'type_8',
				  'type_9',
				  'type_10',
				  'type_11',
				  'type_12'
				  ],
	'theme' : ['Light', 'Dark'],
	'theme_color' : [
			 'default',
			 'green',
			 'blue',
			 'peach',
			 'navy',
			 'red',
			 'purple'
			 ],
	'theme_footer' : ['Normal', 'Inverse']
}

function init_selects() {
	$('.picker_list').each(function() {
		var name = $(this).attr('id');
		var info = the_settings[name];
		var html = '<div class="picker_select"><select>';
		for(i = 0; i < info.length; i++) {
			html += '<option value="' + name + '_' + info[i].toLowerCase() + '">' + info[i] + '</option>';
		}
		html += '</select></div>';
		
		$(this).append(html);
	});
	
	$('.picker_list select').live('change', function() {
		var text = $(this).find('option:selected').html();
		var val = $(this).val();
		$(this).next().html(text);
	});
}

function init_links() {
	$('.picker_pics').each(function() {
		var name = $(this).attr('id');
		var info = the_settings[name];
		var html = '';
		for(i = 0; i < info.length; i++) {
			html += '<a href="' + name + '_' + info[i].toLowerCase() + '"><img src="settings/images/' + name + '_' + info[i].toLowerCase() + '.jpg" alt=""></a>';
		}
		
		$(this).append(html);
	});
	
	$('.picker_pics a').live('click', function(e) {
		$(this).parent().find('a').removeClass('current');
		$(this).addClass('current');
		
		e.preventDefault();
	});
}

function init_control() {
	$('#apply').live('click', function(e) {
		settings_apply();
		
		e.preventDefault();
	});
	
	$('#reset').live('click', function(e) {
		settings_reset();
		
		e.preventDefault();
	});
}

function settings_apply() {
	$('.picker_list select').each(function() {
		var key = $(this).parent().parent().attr('id');
		var value = $(this).val();
		var old_value = $.cookies.get(key);
		if(old_value && old_value != '') $('body').removeClass(old_value);
		
		$.cookies.set(key, value);
		$('body').addClass(value);
	});
	
	$('.picker_pics a.current').each(function() {
		var key = $(this).parent().attr('id');
		var value = $(this).attr('href');
		var old_value = $.cookies.get(key);
		if(old_value && old_value != '') $('body').removeClass(old_value);
		
		$.cookies.set(key, value);
		$('body').addClass(value);
	});
	
}

function settings_reset() {
	for(key in the_settings) {
		$('body').removeClass($.cookies.get(key));
		$.cookies.del(key);
	}
}


var flag = true;
var settings = $.cookies.get('settings');
var opened = (settings && settings == 'closed') ? false : true;
$(document).ready(function() {
	var path = $('#logo img').attr('src');
	var file = path.split('.');
	var new_path = file[0] + '_dark.' + file[1];
	var dark_logo = '<img src="' + new_path + '" class="dark" alt="SkyDream" title="SkyDream">';
	$('#logo a').append(dark_logo);
	
	var path = $('.section_slider_1 .static_pic img').attr('src');
	if(path) {
		var file = path.split('.');
		var new_path = file[0] + '_dark.' + file[1];
		var dark_pic = '<img src="' + new_path + '" class="dark" alt="">';
		$('.section_slider_1 .static_pic').append(dark_pic);
	}
	
	$('.block_services_type_1 .icon img').each(function() {
		var file_path = $(this).attr('src');
		var file_name = file_path.split('.');
		var new_file_path = file_name[0] + '_dark.' + file_name[1];
		var dark_icon = '<img src="' + new_file_path + '" class="dark" alt="">';
		$(this).parent().append(dark_icon);
	});
	
	init_selects();
	init_links();
	init_control();
	
	for(key in the_settings) {
		var value = $.cookies.get(key);
		$('body').addClass(value);
		
		$('#' + key).find('a[href=' + value + ']').addClass('current');
		$('#' + key).find('option[value=' + value + ']').attr('selected', 'selected');
	}
	
	$('.picker_list select').each(function() {
		var text = $(this).find('option:selected').html();
		var html = '<span>' + text + '</span>';
		$(this).css({'opacity' : '0'});
		$(this).parent().append(html);
	});
	
	if(opened) {
		var block_right = 0;
		$('#color_picker').removeClass('closed');
	}
	else {
		var block_right = -198;
		$('#color_picker').addClass('closed');
	}
	$('#color_picker').css('right', block_right + 'px');
	
	$('#picker_close').live('click', function(e) {
		if(!opened) {
			var block_right = 0;
			$('#color_picker').removeClass('closed');
		}
		else {
			var block_right = -198;
			$('#color_picker').addClass('closed');
		}
		opened = !opened;
		
		if(!opened) $.cookies.set('settings', 'closed');
		else $.cookies.del('settings');
		
		if(flag) {
			flag = false;
			$('#color_picker').animate(
				{
					right : block_right
				},
				300,
				function() {
					flag = true;
				}
			);
		}
		
		e.preventDefault();
	});
});