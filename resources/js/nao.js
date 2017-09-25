// New file

function post_delete_confirm() {
    MaterialDialog.dialog(
        "Text here",
        {
            title: "Dialog Title",
            modalType: "modal-fixed-footer", // Can be empty, modal-fixed-footer or bottom-sheet
            buttons: {
                // Use by default close and confirm buttons
                close: {
                    className: "red",
                    text: "closed",
                    callback: function () {
                        alert("closed!");
                    }
                },
                confirm: {
                    className: "blue",
                    text: "confirmed",
                    modalClose: false,
                    callback: function () {
                        console.log("confirmed");
                    }
                }
            }
        }
    )
}

