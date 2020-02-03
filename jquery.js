$(function () {

  $('.main_inner').css({
    opacity: "1",
    transform:"translateY(0)"
  });

  $('#seacret').hover(
    function () {
      $('.click').stop().fadeIn();
    },
    function () {
      $('.click').stop().fadeOut();
    }
  );

  $('#seacret').on('click', function () {
    $('.hide').animate({
      top: "100%"
    }, 5000);
  });

  $('.ninth').on('click', function () {
    alert("congratulation!!\nツイートしてみよう！");
  });

});