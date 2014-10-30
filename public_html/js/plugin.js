/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
 //launch owl & featherlight
  $('.section-slider, .section-slider-small').owlCarousel({
      items : 1,
      loop: true,
      nav: true,
     //navRewind: true,
     dots : true
     // navText :	["prev","next"]
  });
  $('.gallery, .gallery2').featherlightGallery();
  //hide all the section contents
  $('.section-content').hide();
  
  $('.section-title').click(function(){
      if ($(this).hasClass('.selected')){
          //well, you do nothing and smile! :)
      }
      else {
          $('.selected').next().slideUp('fast');
          $('.selected').removeClass('.selected');
          $(this).addClass('selected');
          $(this).next().slideDown();
    }
  });
 
});
