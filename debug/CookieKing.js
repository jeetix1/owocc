function showCookieKing() {
    var cookieKing = $('<img id="CookieKing" src="./img/kingfluffy.png">');
    cookieKing.css({position: 'fixed', bottom: '200px', right: '200px'});
    $('body').append(cookieKing);
    cookieKing.fadeIn(500);

    // Click event for CookieKing
    cookieKing.click(function() {
        $(this).fadeOut(500, function() {
            $(this).remove();
            kingBonus();
        });
    });

   // CookieKing disappears after 10 seconds
    setTimeout(function() {
        cookieKing.fadeOut(500, function() {
            $(this).remove();
        });
    }, 10000); 
}


// Then replace the original code block with a call to this new function
setTimeout(function() {
    showCookieKing();
}, randomTime);


//  Now, you can call showCookieKing() whenever you want to manually trigger the CookieKing to appear, for example in your browser's JavaScript console.