$(document).ready(function() {
    //#region Configuraciones Iniciales
    //#endregion

    //#region Funciones
    function MostrarNotificacion(clase, texto, segundos)
    {
        let aux = $("#notificacion");
        if (aux.html() != undefined)
        {
            aux.remove();
        }
        let html = `
        <div id="notificacion" class="alerta show fade alert p-0 alert-dismissible alert-${clase}">
            <div class="d-flex justify-content-between align-items-center py-1 px-2 gap-2">
                <div class="text-justify" style="font-size: 12.6px">Notificación.</div>
                <button class="btn btn-sm p-0 text-${clase}" id="btnCerrarAlerta" type="button" data-bs-dismiss="alert" data-bs-target="#notificacion">
                    <i class="fa-solid fa-xmark text-dark"></i>
                </button>
            </div>
            <hr class="m-0">
            <div class="py-1 px-2">
                <div class="text-justify alerta-text">` + texto + `</div>
            </div>
        </div>`;
        $("#cont").append(html);
        let alerta = $("#notificacion");
        setTimeout(function()
        {
            alerta.alert("close");
        }, segundos * 1000);
    }

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
                    MostrarNotificacion('danger', response.message, 5);
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                MostrarNotificacion('danger', xhr.responseText, 5);
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
