$(document).ready(function() {
    var isJumping = false;
    var cookieCount = parseInt($('#cookieCount').text());
    var clickCount = 0; // Track consecutive clicks

    $('#cookieBtn').click(function() {
        $.ajax({
            url: 'addCookie.php',
            success: function(data) {
                $('#cookieCount').text(data);

                clickCount++; // Increment click count

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

                // Click event for small cookies
                smallCookie.click(function() {
                    $(this).fadeOut(500, function() {
                        $(this).remove();
                        cookieCount += 1;
                        $('#cookieCount').text(cookieCount);
                    });

                    // Trigger 10 more click events if the user clicks a small cookie
                    for (var i = 0; i < 10; i++) {
                        setTimeout(function() {
                            $('#cookieBtn').trigger('click');
                        }, 100 * i);
                    }
                });

                // Check if the main cookie has been clicked 20 times in a row
                if (clickCount === 20) {
                    var gifContainer = $('<div id="gifContainer"><img src="ollister.gif"></div>');
                    $('body').append(gifContainer);

                    // Increase the amount of cookies the user gets for 20 seconds
                    cookieCount += 10;
                    $('#cookieCount').text(cookieCount);

                    // Remove the gif container after 20 seconds
                    setTimeout(function() {
                        gifContainer.remove();
                    }, 20000);

                    // Reset click count
                    clickCount = 0;
                }
            }
        });
    });
});

setInterval(function(){
    var cookieMonster = $('<img class="cookie-monster" src="cookie-monster.png">');
    var x = Math.floor(Math.random() * ($(window).width() - cookieMonster.width()));
    var y = Math.floor(Math.random() * ($(window).height() - cookieMonster.height()));
    cookieMonster.css({top: y, left: x});
    $('body').append(cookieMonster);

    // Click event for cookie monster
    cookieMonster.click(function() {
        $(this).fadeOut(500, function() {
            $(this).remove();
        });
    });

    // Cookie monster steals cookies every second until chased away
    var monsterInterval = setInterval(function() {
        if ($('#cookieCount').text() > 0) {
            $.ajax({
                url: 'removeCookie.php',
                success: function(data) {
                    $('#cookieCount').text(data);
                }
            });
        }
    }, 1000);

    // When cookie monster is removed, stop stealing cookies
    cookieMonster.on('remove', function() {
        clearInterval(monsterInterval);
    });

}, 300000); // Cookie monster appears every 5 minutes (300 seconds)

setInterval(function(){
    // Calculate a random time (1 min average for 10 times per hour)
    var randomTime = Math.random() * (2 - 0.5) + 0.5; // Random number between 0.5 and 2 minutes
    randomTime = randomTime * 60 * 1000; // Convert to milliseconds

    setTimeout(function() {
        var cookieKing = $('<img id="CookieKing" src="./img/kingfluffy.png">');
        cookieKing.css({position: 'fixed', bottom: '200px', right: '200px'}); // Position in the corner
        $('body').append(cookieKing);
        cookieKing.fadeIn(500);

        // Click event for CookieKing
        cookieKing.click(function() {
            $(this).fadeOut(500, function() {
                $(this).remove();
                kingBonus();
            });
        });

        setTimeout(function() {
            cookieKing.fadeOut(500, function() {
                $(this).remove();
            });
        }, 10000); // CookieKing disappears after 10 seconds (10000 milliseconds)

    }, randomTime);

}, 60000); // Runs every hour

function kingBonus() {
    for (var i = 0; i < 621; i++) {
        $.ajax({
            url: 'addCookie.php',
            success: function(data) {
                $('#cookieCount').text(data);
            }
        });
    }
}

