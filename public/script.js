$(document).ready(function() {
    var isJumping = false;
    var cookieCount = parseInt($('#cookieCount').text());

    $('#cookieBtn').click(function() {
        $.ajax({
            url: 'addCookie.php',
            success: function(data) {
                $('#cookieCount').text(data);
                
                if (!isJumping) {
                    isJumping = true;
                    $('#cookieBtn').addClass('jump');
                    
                    setTimeout(function() {
                        $('#cookieBtn').removeClass('jump');
                        isJumping = false;
                    }, 500);
                }
                
                var smallCookie = $('<img class="small-cookie" src="cookie-small.png">');
                var x = Math.floor(Math.random() * ($(window).width() - smallCookie.width()));
                var y = Math.floor(Math.random() * ($(window).height() - smallCookie.height()));
                smallCookie.css({top: y, left: x});
                $('body').append(smallCookie);
                
                setTimeout(function() {
                    smallCookie.fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 3000);

                // Click event for smol cookies
                smallCookie.click(function() {
                    $(this).fadeOut(500, function() {
                        $(this).remove();
                        cookieCount += 1;
                        $('#cookieCount').text(cookieCount);
                    });

                    // Trigger 10 more click events if the user clicks a smol cookie
                    for (var i = 0; i < 10; i++) {
                        setTimeout(function() {
                            $('#cookieBtn').trigger('click');
                        }, 100 * i);
                    }
                });
            }
        });
    });
});
