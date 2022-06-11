$(document).ready(function(){


	// owel cursoul rtl option 
	var lang = $('html').attr('lang');
	var rtlValue;
    if(lang == 'en'){
    	rtlValue = Boolean('false');        
    }else{
    	rtlValue = Boolean('true');  
    }

    // slider
    // $('.parteners-carousel').owlCarousel({
    //     rtl:false,
    //     loop:true,
    //     center:false,
    //     nav:true,
    //     dots:false,
    //     autoplay:true,
    //     slideTransition:'linear',
    //     margin:25,
    //     autoplayHoverPause: true,
    //     navText:['<i class="fas fa-chevron-right"></i>','<i class="fas fa-chevron-left"></i>'],
    //     responsive:{
    //         0:{
    //             items:1
    //         },
    //         600:{
    //             items:2
    //         },
    //         1000:{
    //             items:6
    //         }
    //     }
    // });

    // mobile menu
    $('.menu-button').on('click', function () {
        $('.mobile-menu-overlay').addClass('active');
        $('.menu-mobile').addClass('menu-mobile-active');
    });

    $('.close-menu').on('click', function () {
        $('.mobile-menu-overlay').removeClass('active');
        $('.menu-mobile').removeClass('menu-mobile-active');
    });

    $('.add-note').on('click', function () {
        $(this).parent('div').parent('.row').find('.note-div').removeClass('hidden')
    });

    $(document).on('click','.open-filter',function() {
        $('.hide-mob').toggle();
    });

    
    $(document).on('click','.open-filter',function() {
        $('.tab-panel').toggle();
    });

    $(document).on('click','.filter-btn',function() {
        $('.home-map').toggle();
    });
    

});


// search 
function openSearch() {
  document.getElementById("myOverlay").style.display = "block";
}

function closeSearch() {
  document.getElementById("myOverlay").style.display = "none";
}



$(document).ready(function(){

var current_fs, next_fs, previous_fs; //fieldsets
var opacity;

$(".next").click(function(){

current_fs = $(this).parent();
next_fs = $(this).parent().next();

//Add Class Active
$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

//show the next fieldset
next_fs.show();
//hide the current fieldset with style
current_fs.animate({opacity: 0}, {
step: function(now) {
// for making fielset appear animation
opacity = 1 - now;

current_fs.css({
'display': 'none',
'position': 'relative'
});
next_fs.css({'opacity': opacity});
},
duration: 600
});
});

$(".previous").click(function(){

current_fs = $(this).parent();
previous_fs = $(this).parent().prev();

//Remove class active
$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

//show the previous fieldset
previous_fs.show();

//hide the current fieldset with style
current_fs.animate({opacity: 0}, {
step: function(now) {
// for making fielset appear animation
opacity = 1 - now;

current_fs.css({
'display': 'none',
'position': 'relative'
});
previous_fs.css({'opacity': opacity});
},
duration: 600
});
});

$('.radio-group .radio').click(function(){
$(this).parent().find('.radio').removeClass('selected');
$(this).addClass('selected');
});

$(".submit").click(function(){
return false;
})

});












// plus /minus item quantity
// function wcqib_refresh_quantity_increments() {
//     jQuery("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").each(function(a, b) {
//         var c = jQuery(b);
//         c.addClass("buttons_added"), c.children().first().before('<input type="button" value="-" class="minus" />'), c.children().last().after('<input type="button" value="+" class="plus" />')
//     })
// }
// String.prototype.getDecimals || (String.prototype.getDecimals = function() {
//     var a = this,
//         b = ("" + a).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
//     return b ? Math.max(0, (b[1] ? b[1].length : 0) - (b[2] ? +b[2] : 0)) : 0
// }), jQuery(document).ready(function() {
//     wcqib_refresh_quantity_increments()
// }), jQuery(document).on("updated_wc_div", function() {
//     wcqib_refresh_quantity_increments()
// }), jQuery(document).on("click", ".plus, .minus", function() {
//     var a = jQuery(this).closest(".quantity").find(".qty"),
//         b = parseFloat(a.val()),
//         c = parseFloat(a.attr("max")),
//         d = parseFloat(a.attr("min")),
//         e = a.attr("step");
//     b && "" !== b && "NaN" !== b || (b = 0), "" !== c && "NaN" !== c || (c = ""), "" !== d && "NaN" !== d || (d = 0), "any" !== e && "" !== e && void 0 !== e && "NaN" !== parseFloat(e) || (e = 1), jQuery(this).is(".plus") ? c && b >= c ? a.val(c) : a.val((b + parseFloat(e)).toFixed(e.getDecimals())) : d && b <= d ? a.val(d) : b > 0 && a.val((b - parseFloat(e)).toFixed(e.getDecimals())), a.trigger("change")
// });


// rating
$(function (){
    $('.rating').rating({
        rtl: true
    });
});


