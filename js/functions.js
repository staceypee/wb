;(function($, window, document, undefined) {
	var $win = $(window);
	var $doc = $(document);

	$doc.ready(function() {
		$('.nav-trigger').on('click', function(e){
			e.preventDefault()
			$('.header').toggleClass('nav-expanded')
		})
	//add lightbox rel to youtube links.
	$('a[href*="youtube.com"]').attr('data-lity', '');
		
	});
    $win.on('load resize', function () {
        equalize();
    })

    var equalize = function() {
        var $el, currentTallest = 0,
            currentRowStart = 0,
    
        rowDivs = new Array();  
        
        $('.packages .package-body').height('auto');
        $('.packages .package-body').each(function() {
            if ($el = $(this), $($el).height("auto"), topPostion = $el.position().top, currentRowStart != topPostion) {
                for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) rowDivs[currentDiv].height(currentTallest);
                rowDivs.length = 0, currentRowStart = topPostion, currentTallest = $el.height(),
                    rowDivs.push($el);
            } else rowDivs.push($el), currentTallest = currentTallest < $el.height() ? $el.height() : currentTallest;
                for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) rowDivs[currentDiv].height(currentTallest);
        });
    }
        

})(jQuery, window, document);
