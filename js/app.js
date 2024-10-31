$(document).ready(function(){
    $('.carousel').slick({
        slidesToShow: 1, /* Quantidade de slides a serem mostrados */
        slidesToScroll: 1, /* Quantidade de slides a serem rolados */
        autoplay: true,
        autoplaySpeed: 2000,
        dots: true,
        infinite: true
    });
});