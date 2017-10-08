/**
 * Pagination
 */
$(function () {
    // Next page for pagination
    $('#nextpage').click(function () {
        var current = $('.pages').find('.active').attr('id').replace('p_', '');
        var maxPage = $(this).data('maxpage');
        current = parseInt(current) + 1;
        checkLastpage(current);
        checkFirstpage(current);
        if (current <= maxPage) {
            displayPage(current);
        }
    });

    // Previous page for pagination
    $('#prevpage').click(function () {
        var current = $('.pages').find('.active').attr('id').replace('p_', '');
        current = parseInt(current) - 1;
        checkLastpage(current);
        checkFirstpage(current);
        if (current >= 1) {
            displayPage(current);
        }
    });
    $('.gotopage').click(function () {
        var pageno = $(this).data("page");
        // Search active page
        checkFirstpage(pageno);
        checkLastpage(pageno);
        displayPage(pageno);

    });
});


/**
 * Display page
 */
function displayPage(page) {
    // Remove active classes
    $(".pages li").removeClass("active");
    getList(page, 0);
    $('#p_' + page).addClass('active');
}

/**
 * Check page number to active next icon
 *
 * @param pageno
 */
function checkLastpage(pageno) {
    var maxPage = $('#nextpage').data('maxpage');
    if (pageno >= maxPage) {
        $('#chevron_right').addClass('disabled');

    } else {
        $('#chevron_right').removeClass('disabled');
    }
}

/**
 * Check page number to active previous icon
 *
 * @param pageno
 */
function checkFirstpage(pageno) {
    if (pageno <= 1) {
        $('#chevron_left').addClass('disabled');

    } else {
        $('#chevron_left').removeClass('disabled');
    }
}
