$(document).ready(function() {
    //#region Funciones
    function logout(event)
    {
        event.preventDefault();
        let _token = $('meta[name="csrf-token"]').attr('content');
        let url = $('#url-logout').val();

        console.log(url);
        let error = $('#login-error');

        $.ajax({
            url: url,
            type: 'POST',
            data: { _token: _token },
            success: function(response)
            {
                console.log(response);
                if (response.state)
                {
                    window.location.href = response.redirect;
                }
                else
                {
                    error.html(response.message);
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                console.error(xhr.responseText);
            }
        });
    }
    //#endregion

    //#region Interacción DOM
    $("#loginBtn").click(function(e) {
        login(e);
    });

    $("#logoutBtn").click(function(e) {
        logout(e);
    });
    //#endregion
});
