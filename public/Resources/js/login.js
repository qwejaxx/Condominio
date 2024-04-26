$(document).ready(function() {
    //#region Configuraciones Iniciales
    //#endregion

    //#region Funciones
    function login(event)
    {
        event.preventDefault();
        let loginData = $('#loginForm').serialize();
        let url = $('#loginForm').data('url');
        let error = $('#login-error');

        $.ajax({
            url: url,
            type: 'POST',
            data: loginData,
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
    //#endregion
});
