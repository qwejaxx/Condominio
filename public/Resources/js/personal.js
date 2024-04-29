$(document).ready(function () {
    //#region Configuraciones Iniciales
    let modalMain = $('#modalMain');
    let tituloModal = $('#modal-titulo');
    let mensajeModal = $('#modal-mensaje');
    let searchInput = $('#search');
    let btnCrud = $('#btnCrud');
    let tableIndex = $('#tabla');
    let urlIndex = $('#url-index').val();
    let urlStore = $('#url-store').val();
    let urlShow = $('#url-show').val() + '/';
    let urlUpdate = urlShow;
    let urlDelete = urlShow;
    //#endregion

    //#region Funciones Extras
    function getRolesOnSelect() {
        let _token = $('meta[name="csrf-token"]').attr('content');
        let select = $("#rol");
        let url = select.data('url');

        $.ajax(
            {
                url: url,
                type: 'GET',
                data: { _token: _token },
                dataType: 'json',
                success: function (response) {
                    let roles = response.data;

                    if (roles.length > 0) {
                        select.empty();
                        roles.forEach(rol => {
                            select.append($("<option>",
                                {
                                    value: rol.name, text: rol.name
                                }));
                        });
                    }
                    else {
                        console.log("No hay resultados");
                    }
                },
                error: function (xhr, status, error) {
                    console.log("Error en la solicitud AJAX: " + error);
                }
            });
    }

    function resetAllOnModal()
    {
        $('#rsdtForm')[0].reset(); // Reiniciar Formulario
        $('#rsdtForm').find(':input').prop('disabled', false);
        $('#botonesModal').removeClass('opacity-0');
        $('#campos_representante').addClass('show');
        $('#campos_usuario').removeClass('show');
        $('#rep_fam_id_rsdt').val($('#rep_fam_id_rsdt option:first').val()).trigger('change');
        tituloModal.text('Nuevo Personal');
        mensajeModal.html('');
        btnCrud.attr('name', 'store');
        btnCrud.text('Agregar');
    }
    //#endregion

    //#region Funciones Prepare
    function prepareSelect(id)
    {
        tituloModal.text('Detalles sobre el Personal');
        $('#rsdtForm').find(':input').prop('disabled', true);
        $('#password').attr('type', 'password');
        $('#botonesModal').addClass('opacity-0');
        show(id);
    }

    function prepareEdit(id)
    {
        tituloModal.text('Editar Personal');
        $('#rsdtForm').find(':input').prop('disabled', false);
        $('#password').attr('type', 'password');
        show(id);
    }

    function prepareDelete(id)
    {
        tituloModal.text('Eliminar Personal');
        mensajeModal.html('El siguiente personal se eliminará a continuación,<br>¿Está seguro?');
        $('#rsdtForm').find(':input:not(button)').prop('disabled', true);

        $('#password').attr('type', 'password');
        show(id);
    }
    //#endregion

    //#region Paginación
    function generatePaginationButtons(currentPage, lastPage, url, search) {
        let paginationContainer = $('#pagination-container');
        paginationContainer.empty();

        let maxVisibleButtons = 3; // Número máximo de botones visibles en la paginación

        // Verificar si hay menos de 4 páginas en total para mostrar solo los numeros de paginación
        if (lastPage <= maxVisibleButtons) {
            for (let i = 1; i <= lastPage; i++) {
                let pageButton = $('<li class="page-item"><button class="page-link border-secondary pag-item">' + i + '</button></li>');
                if (i === currentPage) {
                    pageButton.addClass('active');
                }
                pageButton.click(function () {
                    if (currentPage !== i) {
                        index(search, url + '&page=' + i);
                    }
                });
                paginationContainer.append(pageButton);
            }
            return; // Salir de la función después de generar los botones de número de página
        }

        // Botón para ir a la primera página
        let firstPageButton = $('<li class="page-item ' + (currentPage === 1 ? 'disabled' : '') + '"><button class="page-link border-secondary pag-item">&laquo;</button></li>');
        firstPageButton.click(function () {
            if (currentPage !== 1) {
                index(search, url + '&page=1');
            }
        });
        paginationContainer.append(firstPageButton);

        // Botón para ir a la página anterior
        let previousPageButton = $('<li class="page-item ' + (currentPage === 1 ? 'disabled' : '') + '"><button class="page-link border-secondary pag-item">&lt;</button></li>');
        previousPageButton.click(function () {
            if (currentPage > 1) {
                let prevPage = currentPage - 1;
                index(search, url + '&page=' + prevPage);
            }
        });
        paginationContainer.append(previousPageButton);

        // Botones para las páginas intermedias
        let startPage = Math.max(1, currentPage - Math.floor(maxVisibleButtons / 2));
        let endPage = Math.min(lastPage, startPage + maxVisibleButtons - 1);

        if (endPage - startPage < maxVisibleButtons - 1) {
            startPage = Math.max(1, endPage - maxVisibleButtons + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            let pageButton = $('<li class="page-item ' + (i === currentPage ? 'active' : '') + '"><button class="page-link border-secondary pag-item">' + i + '</button></li>');
            pageButton.click(function () {
                if (currentPage !== i) {
                    index(search, url + '&page=' + i);
                }
            });
            paginationContainer.append(pageButton);
        }

        // Botón para ir a la página siguiente
        let nextPageButton = $('<li class="page-item ' + (currentPage === lastPage ? 'disabled' : '') + '"><button class="page-link border-secondary pag-item">&gt;</button></li>');
        nextPageButton.click(function () {
            if (currentPage < lastPage) {
                let nextPage = currentPage + 1;
                index(search, url + '&page=' + nextPage);
            }
        });
        paginationContainer.append(nextPageButton);

        // Botón para ir a la última página
        let lastPageButton = $('<li class="page-item ' + (currentPage === lastPage ? 'disabled' : '') + '"><button class="page-link border-secondary pag-item">&raquo;</button></li>');
        lastPageButton.click(function () {
            if (currentPage !== lastPage) {
                index(search, url + '&page=' + lastPage);
            }
        });
        paginationContainer.append(lastPageButton);
    }
    //#endregion

    //#region Funciones CRUD
    function index(search = "", url = urlIndex + '?page=1') {
        let _token = $('meta[name="csrf-token"]').attr('content');
        let totalResultados = $('#totalResultados').val();
        let error = $('#index-error');
        let seccionTotalResultados = $('#seccionTotalResultados');
        let html = "";

        $.ajax({
            url: url,
            type: 'GET',
            data: { _token: _token, search: search, totalResultados: totalResultados },
            dataType: 'json',
            success: function (response) {
                tableIndex.empty();
                if (response.data.data.length > 0) {
                    error.html('');
                    html = `
                    <thead class="table-secondary fw-semibold">
                        <tr>
                            <th>CI</th>
                            <th>NOMBRE</th>
                            <th>FECHA DE NACIMIENTO</th>
                            <th>TELÉFONO</th>
                            <th>ROL</th>
                            <th width="200">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                `;
                    response.data.data.forEach(personal => {
                        html += `
                        <tr>
                            <td>${personal.ci_rsdt}</td>
                            <td>${personal.nombre_rsdt} ${personal.apellidop_rsdt} ${personal.apellidom_rsdt}</td>
                            <td>${personal.fechanac_rsdt}</td>
                            <td>${personal.telefono_rsdt}</td>
                            <td>${personal.usuario.roles[0].name}</td>
                            <td>
                                <button id='btnSelect' data-id="${personal.id_rsdt}" class='btn p-0 btn-sm btn-info text-white' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-magnifying-glass-plus'></i></button>
                                <button id='btnEdit' data-id="${personal.id_rsdt}" class='btn p-0 btn-sm btn-secondary text-white' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-pen-to-square'></i></button>
                                <button id='btnDelete' data-id="${personal.id_rsdt}" class='btn p-0 btn-sm btn-primary' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-trash'></i></button>
                            </td>
                        </tr>
                    `;
                    });
                    html += `</tbody>`;
                    tableIndex.append(html);

                    // Llamar a la función para generar los botones de paginación
                    generatePaginationButtons(response.data.current_page, response.data.last_page, url, search);
                    seccionTotalResultados.removeClass('d-none');
                } else {
                    error.html('No se encontraron resultados.')
                    let paginationContainer = $('#pagination-container');
                    paginationContainer.empty();
                    seccionTotalResultados.addClass('d-none');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function store() {
        let formData = $('#rsdtForm').serialize();

        $.ajax({
            url: urlStore,
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log(response);
                if (response.state) {
                    searchInput.val(response.data.ci_rsdt);
                    index(response.data.ci_rsdt);
                    modalMain.modal('hide');
                    setTimeout(function () {
                        searchInput.focus();
                    }, 700);
                }
                else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function llenarFormulario(data) {
        let residente = data;
        $('#id_rsdt').val(residente.id_rsdt);
        $('#ci_rsdt').val(residente.ci_rsdt);
        $('#nombre_rsdt').val(residente.nombre_rsdt);
        $('#apellidop_rsdt').val(residente.apellidop_rsdt);
        $('#apellidom_rsdt').val(residente.apellidom_rsdt);
        $('#fechanac_rsdt').val(residente.fechanac_rsdt);
        $('#telefono_rsdt').val(residente.telefono_rsdt);
        $('#email').val(residente.usuario.email);
        $('#password').attr('type', 'password');
        $('#password').val(residente.usuario.password);
        $('#rol').val(residente.usuario.roles[0].name);
    }

    function show(id) {
        let _token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: urlShow + id,
            type: 'GET',
            data: { _token: _token },
            dataType: 'json',
            success: function(response) {
                if (response.state) {
                    llenarFormulario(response.data);
                    modalMain.modal('show');
                } else {
                    console.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function update(id) {
        let formData = $('#rsdtForm').serialize();

        $.ajax({
            url: urlUpdate + id,
            type: 'PUT',
            data: formData,
            success: function (response) {
                console.log(response);
                if (response.state) {
                    // Actualización exitosa
                    console.log("¡Actualización exitosa!");
                    searchInput.val(response.data.ci_rsdt);
                    index(response.data.ci_rsdt);
                    modalMain.modal('hide');
                    setTimeout(function () {
                        searchInput.focus();
                    }, 700);
                } else {
                    console.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                // Si hay un error en la solicitud AJAX, muestra el mensaje de error en la consola
                console.error(xhr.responseText);
            }
        });
    }

    function destroy(id)
    {
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: urlDelete + id,
            type: 'DELETE',
            data: { _token: _token },
            success: function(response) {
                if (response.state) {
                    console.log(response.message);
                    searchInput.val('');
                    index();
                    modalMain.modal('hide');
                } else {
                    console.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    //#endregion

    //#region Interacción DOM
    //#region Funciones de Carga Inicial
    index();
    getRolesOnSelect();
    //#endregion

    //#region Busqueda
    searchInput.on('input', function () {
        index($(this).val());
    });

    $('#totalResultados').on('input', function () {
        index(searchInput.val());
    });
    //#endregion

    //#region Store
    btnCrud.click(function (e) {
        e.preventDefault();
        let action = $(this).attr('name');
        let id = $('#id_rsdt').val();
        switch (action) {
            case "store":
                {
                    store();
                }
                break;
            case "edit":
                {
                    update(id);
                }
                break;
            case "delete":
                {
                    destroy(id);
                }
                break;
        }
    });
    //#endregion
    //#endregion

    //#region Activacion de botones de CRUD
    tableIndex.on('click', '#btnSelect', function () {
        let id = $(this).data('id');
        btnCrud.attr('name', 'show');
        btnCrud.text('Show');
        prepareSelect(id);
    });

    tableIndex.on('click', '#btnEdit', function () {
        let id = $(this).data('id');
        btnCrud.attr('name', 'edit');
        btnCrud.text('Editar');
        prepareEdit(id);
    });

    tableIndex.on('click', '#btnDelete', function () {
        let id = $(this).data('id');
        btnCrud.attr('name', 'delete');
        btnCrud.text('Eliminar');
        prepareDelete(id);
    });
    //#endregion

    //#region Configuracion en Modales
    $("#modalMain").on("shown.bs.modal", function () {

    });

    modalMain.on('hidden.bs.modal', function () {
        resetAllOnModal();
    });
    //#endregion
});
