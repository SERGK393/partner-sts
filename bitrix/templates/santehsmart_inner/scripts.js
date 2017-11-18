//discountMetr Script

// Init Vars
var  maxScale = 20000,
    currCartSize = $('#bx_cart_block1 a:last-child').text().replace(/[^0-9$.,]/g, ''),

    scale = $('.discountMetr__scale_inner');
console.log(currCartSize);
// Get Percentage on change

var percent =  (currCartSize / maxScale) * 100 ;
percent = ( percent > 100 )? '100%': percent + '%';
scale.css('width',percent);

// Activ Anim ,toggle css