/**
 * Google SignOut
 */
$(function() {
    $('#logout').click(function () {
        g_signOut();
    });
});


function g_signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
    });
}


function onLoad() {
    gapi.load('auth2', function() {
        gapi.auth2.init();
    });
}