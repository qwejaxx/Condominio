$(document).ready(function () {
    // Agregar clase 'active' al elemento con ID 'loginContainer'
    $('#loginContainer').addClass('active');

    // Evento de clic para alternar la visibilidad de los círculos
    $("#toggleButton").click(function () {
        $(".circle").toggleClass("hidden");
    });

    // Función para alternar la visibilidad de la contraseña y cambiar el icono del ojo
    function togglePassword() {
        var passwordInput = $("#password");
        var eyeIcon = $("#eyeIcon");

        if (passwordInput.attr("type") === "password") {
            passwordInput.attr("type", "text");
            eyeIcon.removeClass("fa-eye").addClass("fa-eye-slash");
        } else {
            passwordInput.attr("type", "password");
            eyeIcon.removeClass("fa-eye-slash").addClass("fa-eye");
        }
    }

    // Evento de clic para alternar la visibilidad de la contraseña
    $("#eyeIcon").click(function () {
        togglePassword();
    });
});
