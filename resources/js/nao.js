function removePage(page) {
    $('#p_' + page).remove();
}

function addPage(page) {
    html = '<li id="p_' + page + '" class="waves-effect ">' +
        '<a class="gotopage" href="#" data-page="' + page + '">' + page + '</a>' +
        '</li>';
    $(html).insertBefore('.pagination>li:last');
    ;

}

/**
 * Get user list
 *
 * @param pageno
 * @param user_id
 */
/*

function userList(pageno, user_id) {
    var role = $('.tab').find('.active').attr('id');
    var search = $('#form_search').val();
    $.ajax({
        url: "{{ path('api_user_paginate')}}",
        type: "POST",
        dataType: "json",
        data: {
            "page": pageno,
            "role": role,
            "user_id": user_id
        },
        success: function (response) {
            $('#userList').html(response.html);
        }
    });
}
*/
/**
 * Confirm deletion of post
 *
 * @param id
 */
/*
function post_delete_confirm(id) {
    MaterialDialog.dialog(
        "{% trans %}supprimer_article_confirm{% endtrans %}",
        {
            title: "{% trans %}supprimer_article{% endtrans %}",
            modalType: "modal-fixed-footer", // Can be empty, modal-fixed-footer or bottom-sheet
            buttons: {
                // Use by default close and confirm buttons
                confirm: {
                    className: "btn-validate",
                    text: "{% trans %}oui{% endtrans %}",
                    callback: function () {

                        var url = '{{ path('
                        admin_post_delete
                        ',{ '
                        id
                        ':'
                        post_id
                        '}) }}';
                        url = url.replace('post_id', id);
                        window.location.href = url;
                    }
                },
                close: {
                    className: "btn-cancel",
                    text: "{% trans %}non{% endtrans %}",
                    modalClose: true
                }
            }
        }
    )
}
*/