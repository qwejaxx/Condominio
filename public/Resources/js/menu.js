$(document).ready(function () {
    let body = $("body");
    let sidebar = $("nav");
    let toggle = $(".toggle");
    let modeText = $(".mode-text");
    let menuSwitch = $(".switchMenu");

    toggle.on("click", function () {
        sidebar.toggleClass("close");
    });

    menuSwitch.on("click", function () {
        body.toggleClass("dark");

        if (body.hasClass("dark")) {
            modeText.text("Modo día");
        }
        else {
            modeText.text("Modo noche");
        }
    });

    function cambiarMenu() {
        let ancho = $(window).width();
        let home = $(".home");
        let main = $(".main");
        let submenu = $("#submenu");

        if (ancho < 576) {
            if (!sidebar.hasClass("close"))
                sidebar.addClass("close");
            if (!home.hasClass("home-movil"))
                home.addClass("home-movil");
            if (!main.hasClass("margin-menu-movil"))
                main.addClass("margin-menu-movil");
            if (!submenu.hasClass("d-none"))
                submenu.addClass("d-none");
        }
        else {
            if (home.hasClass("home-movil"))
                home.removeClass("home-movil");
            if (main.hasClass("margin-menu-movil"))
                main.removeClass("margin-menu-movil");
            if (submenu.hasClass("d-none"))
                submenu.removeClass("d-none");
        }
    }

    cambiarMenu();
    $(window).on("resize", function () {
        cambiarMenu();
    })

    const observer = new MutationObserver(function (mutationsList, observer) {
        for (const mutation of mutationsList) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                if ($('.sidebar').hasClass('close')) {
                    $('.modal').css({ 'left': '39px', 'transition': 'all 0.5s ease' });
                    $('#loader').css({ 'margin-left': '78px' });
                    $('#text-loader').css({ 'margin-left': '78px' });
                }
                else {
                    $('.modal').css({ 'left': '125px', 'transition': 'all 0.5s ease' });
                    $('#loader').css({ 'margin-left': '250px' });
                    $('#text-loader').css({ 'margin-left': '250px' });
                }
                if ($('.home').hasClass('home-movil')) {
                    $('.modal').css({ 'left': '0px', 'transition': 'all 0.5s ease' });
                    $('#loader').css({ 'margin-left': '0px' });
                    $('#text-loader').css({ 'margin-left': '0px' });
                }
            }
        }
    });

    observer.observe(document, { attributes: true, childList: true, subtree: true });

    $('.sidebar').on('change', function()
    {
      if ($(this).hasClass('close'))
      {
        $('.modal').css({'left': '39px'});
      }
      else
      {
        $('.modal').css({'left': '125px'});
      }
    });
});
