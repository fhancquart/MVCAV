let up = 6;

function slice(target){
  $(target).slice(up).css("display", "none");
}

function loadMore(target){
	up += 3;
	$(target).slice(6, up).css("display", "block");

	$('.totop').show();
}

$('p.totop a').click(function () {
		$('body,html').animate({
				scrollTop: 0
		}, 600);
		return false;
});

$(window).scroll(function () {
		if ($(this).scrollTop() > 50) {
				$('.totop a').fadeIn();
		} else {
				$('.totop a').fadeOut();
		}
});
