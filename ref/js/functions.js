

$('#sidebar-nav > li > a').click(function(){
	    if ($(this).attr('class') != 'active'){
	      $('#sidebar-nav li ul').slideUp();
	      $(this).next().slideToggle();
	      $('#sidebar-nav li a').removeClass('active');
	      $(this).addClass('active');
		  return false;
	    }
	  });
	





// Sidebar Toggle
var fluid = {
Toggle : function(){
	var default_hide = {"grid": true };
	$.each(
		["pagesnav", "commentsnav", "userssnav", "imagesnav"],
		function() {
			var el = $("#" + (this == 'accordon' ? 'accordion-block' : this) );
			if (default_hide[this]) {
				el.hide();
				$("[id='toggle-"+this+"']").addClass("hidden")
			}
			$("[id='toggle-"+this+"']")
			.bind("click", function(e) {
				if ($(this).hasClass('hidden')){
					$(this).removeClass('hidden').addClass('visible');
					el.slideDown();
				} else {
					$(this).removeClass('visible').addClass('hidden');
					el.slideUp();
				}
				e.preventDefault();
			});
		}
	);
}
}
jQuery(function ($) {
	if($("[id^='toggle']").length){fluid.Toggle();}
});


// Notification Animations
$(function () { 
$('.notification').hide().append('<span class="close" title="Dismiss"></span>').fadeIn('slow');
$('.notification .close').hover(
function() { $(this).addClass('hover'); },
function() { $(this).removeClass('hover'); }
);
$('.notification .close').click(function() {
$(this).parent().fadeOut('slow', function() { $(this).remove(); });
}); 

});



// jQuery UI - Live Search
$(function() {
		var availableTags = ["dashboard", "pages", "manage pages", "edit pages", "delete pages", "users", "manage users", "edit users", "delete users", "settings", "system settings", "server settings", "documentation", "help", "community forums", "contact"];
		$("#livesearch").autocomplete({
			source: availableTags
		});
	});



// jQuery UI - Dialog Box
	$(function() {
		$('#dialog').dialog({
			autoOpen: false,
			modal: true,
			width: 500
		})
		$('#opener').click(function() {
			$('#dialog').dialog('open');
			return false;
		});

	});
	
	



// Sidebar close/open (with cookies)
 function close_sidebar() {
 $("body").addClass('sidebar-off');
 $("#open_sidebar").show();
 $("#close_sidebar").hide();
 $.cookie('sidebar', 'closed');
 if( $('body').hasClass('chart') ) {
    // redraw chart
	drawChart();
 }
 }
 function open_sidebar() {
 $("body").removeClass('sidebar-off');
 $("#open_sidebar").hide();
 $("#close_sidebar").show();
 $.cookie('sidebar', 'open');
 if( $('body').hasClass('chart') ) {
    // redraw chart
	drawChart();
}
}
 $('#close_sidebar').click(function(){
 close_sidebar();
 if($.browser.safari) {
 location.reload();
 }
 });
 $('#open_sidebar').click(function(){
 open_sidebar();
 if($.browser.safari) {
 location.reload();
 }
 });
 var sidebar = $.cookie('sidebar');

 if (sidebar == 'closed') {
 close_sidebar();
 };

 if (sidebar == 'open') {
 open_sidebar();
 };
 
 
 // Filetree Setup
$(".filetree").treeview({
		control: "#treecontrol"
	});
$("#filetree-add").click(function() {
		var branches = $("<li><span class='folder'>New Sublist</span><ul>" + 
			"<li><img alt='' src='./img/icons/16/page.png' /><a href='#'>New Item</a></li>" + 
			"<li><img alt='' src='./img/icons/16/page.png' /><a href='#'>New Item</a></li></ul></li>").appendTo("#browser");
		$("#browser").treeview({
			add: branches
		});
		branches = $("<li class='closed'><span class='folder'>New Sublist</span><ul><li><img alt='' src='./img/icons/16/page.png' /><a href='#'>New Item</a></li><li><img alt='' src='./img/icons/16/page.png' /><a href='#'>New Item</a></li></ul></li>").prependTo("#folder21");
		$("#browser").treeview({
			add: branches
		});
	});
	
// Gallery Setup
$('.gallery a').lightBox({
	fixedNavigation:true,
	overlayOpacity:0.5,
	imageLoading:'img/lightbox/lightbox-ico-loading.gif',
	imageBtnClose:'img/lightbox/lightbox-btn-close.gif',
	imageBtnPrev:'img/lightbox/lightbox-btn-prev.gif',
	imageBtnNext:'img/lightbox/lightbox-btn-next.gif',
	imageBlank:'img/lightbox/lightbox-blank.gif'
});
	
// wysiwyg Setup
$('.wysiwyg').wysiwyg({
    controls: {
	      strikeThrough : { visible : false },
      underline     : { visible : false },
      
      separator00 : { visible : false },
      
      justifyLeft   : { visible : false },
      justifyCenter : { visible : false },
      justifyRight  : { visible : false },
      justifyFull   : { visible : false },
      
      separator01 : { visible : false },
      
      indent  : { visible : false },
      outdent : { visible : false },
      
      separator02 : { visible : false },
      
      subscript   : { visible : false },
      superscript : { visible : false },
      
      separator03 : { visible : false },
      
      undo : { visible : false },
      redo : { visible : false },
      
      separator04 : { visible : false },
      
      insertOrderedList    : { visible : false },
      insertUnorderedList  : { visible : false },
      insertHorizontalRule : { visible : false },
      
      separator07 : { visible : false },
      
      cut   : { visible : false },
      copy  : { visible : false },
      paste : { visible : false }	
 }
});

// Make objects collapsible
$(".collapsible-list span.toggle").click(function(){
$(this).closest('li').toggleClass('collapsed');
return false;
});
$("fieldset legend > a, .fieldset .legend > a").click(function(){
$(this).closest('fieldset, .fieldset').toggleClass('collapsed');
$("fieldset legend a").toggleClass('collapse');
return false;
});			


// Tipsy Tooltips
$('.tooltip').tipsy({fade: true});
$('.tooltip.north').tipsy({fade: true, gravity: 's'});
$('.tooltip.east').tipsy({fade: true, gravity: 'w'});
$('.tooltip.west').tipsy({fade: true, gravity: 'e'});
// Form Tooltips
$('form [title]').tipsy({fade: true, trigger: 'focus', gravity: 'w'});
// wysiwyg Toolbar Tooltips
$('.wysiwyg a').tipsy({fade: true, gravity: 's'});

// Form Switches
  $(".switch-enable").click(function(){
        var parent = $(this).parents('.switch-wrapper');
        $('.switch-disable',parent).removeClass('selected');
        $(this).addClass('selected');
        $('.checkbox',parent).attr('checked', true);
    });
    $(".switch-disable").click(function(){
        var parent = $(this).parents('.switch-wrapper');
        $('.switch-enable',parent).removeClass('selected');
        $(this).addClass('selected');
        $('.checkbox',parent).attr('checked', false);
    });


// Tabs
$("#tabs").tabs();
$("#dashtabs").tabs();
$("#demotabs").tabs();
$("#formtabs").tabs();


 // Check all checkboxes when the one in a table head is checked:

 $(function () { // this line makes sure this code runs on page load
 	$('.check-all').click(function () {
 		$(this).parents('table:eq(0)').find(':checkbox').attr('checked', this.checked);
 	});
 });