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
    let urlNoAsignaciones = urlShow + 'NoAsignaciones/';
    let urlAsignaciones = urlShow + 'Asignaciones/';
    let urlStoreAsignaciones = $('#url-store-asignaciones').val();
    let urlUpdateAsignaciones = $('#url-update-asignaciones').val();

    let modalParticipantes = $('#modalParticipantes');
    let modalAsignaciones = $('#modalAsignaciones');
    let btnAsignar = $('#btnAsignar');
    let btnUpdateAsignaciones = $('#btnUpdateAsignaciones');

    let arrayParticipantes = [];
    let arrayParticipantesOriginal = [];
    let arrayParticipantesNuevos = []; // Array para participantes nuevos
    let arrayParticipantesEliminados = [];
    let contenedorParticipantes = $('#participantesContainer'); // Div para participantes ya registrados
    let contenedorSeleccionados = $('#participantesSeleccionadosContainer') // Div para nuevos participantes
    let contenedorEliminados = $('#participantesEliminadosContainer'); // Div para eliminar participantes
    //#endregion

    //#region Funciones Extras
    function resetAllOnModal() {
        $('#rsdtForm')[0].reset(); // Reiniciar Formulario
        $('#rsdtForm').find(':input').prop('disabled', false);
        $('#botonesModal').removeClass('opacity-0');
        $('#propietario_id_mas').val($('#propietario_id_mas option:first').val()).trigger('change');
        tituloModal.text('Nueva Planificación');
        mensajeModal.html('');
        btnCrud.attr('name', 'store');
        btnCrud.text('Agregar');
    }

    function resetAllOnModalParticipantes() {
        $('#searchParticipantes').val('');
        arrayParticipantesOriginal = [];
        arrayParticipantes = [];
        arrayParticipantesEliminados = [];
        actualizarContadores();
        btnUpdateAsignaciones.prop('disabled', true);
        contenedorParticipantes.html('');
        contenedorEliminados.html('');
    }

    function resetAllOnModalAsignaciones()
    {
        arrayParticipantesNuevos = [];
        contenedorSeleccionados.html('');
        actualizarContadorNuevos();
        btnAsignar.prop('disabled', true);
    }

    function resetSelect() {
        $('#SelParti').val(0);
    }

    function moverParticipante(participanteAMover, accion, divParticipante) {
        divParticipante.remove();
        if (accion == 'Eliminar') {
            arrayParticipantes = arrayParticipantes.filter(idParticipante => idParticipante !== participanteAMover.id_rsdt);
            if (esParticipanteUnico(participanteAMover.id_rsdt, arrayParticipantesEliminados)) {
                arrayParticipantesEliminados.push(participanteAMover.id_rsdt);
                let nuevoDiv = crearDivParticipante(participanteAMover, 'Agregar');
                contenedorEliminados.append(nuevoDiv);
            }
            else {
                console.log('El participante ya se encuentra en la lista de eliminados.');
            }
        }
        else {
            arrayParticipantesEliminados = arrayParticipantesEliminados.filter(idParticipante => idParticipante !== participanteAMover.id_rsdt);
            if (esParticipanteUnico(participanteAMover.id_rsdt, arrayParticipantes)) {
                arrayParticipantes.push(participanteAMover.id_rsdt);
                let nuevoDiv = crearDivParticipante(participanteAMover, 'Eliminar');
                contenedorParticipantes.append(nuevoDiv);
            }
            else {
                console.log('El participante ya se encuentra en la actividad.');
            }
        }
        actualizarContadores();
    }

    function actualizarContadores() {
        let contadorParticipantes = arrayParticipantes.length;
        $('#contadorParticipantes').text(contadorParticipantes);
        let contadorParticipantesEliminados = arrayParticipantesEliminados.length;
        $('#contadorParticipantesEliminados').text(contadorParticipantesEliminados);
        $('#etiquetaOculta').attr('hidden', contadorParticipantesEliminados < 1);

        btnUpdateAsignaciones.prop('disabled', contadorParticipantes === arrayParticipantesOriginal.length)
    }

    function actualizarContadorNuevos() {
        let contadorNuevos = arrayParticipantesNuevos.length;
        $('#contadorNuevosParticipantes').text(contadorNuevos);

        btnAsignar.prop('disabled', contadorNuevos < 1)
    }

    function esParticipanteUnico(idParticipanteComparado, array) {
        let esUnico = true;
        array.forEach(idParticipante => {
            if (idParticipanteComparado === idParticipante) {
                esUnico = false;
            }
        });
        return esUnico;
    }

    function crearDivParticipante(participante, accion) {
        let idParticipante = participante.id_rsdt;
        let nombreParticipante = participante.apellidop_rsdt ? participante.nombre_rsdt + ' ' + participante.apellidop_rsdt : participante.nombre_rsdt;
        let rolParticipante = participante.usuario.roles[0].name;
        let icono = '<i class="fas fa-times"></i>';
        if (accion == 'Agregar') {
            icono = '<i class="fas fa-plus"></i>';
        }

        let nuevoDiv = $(`
            <div class="datoUser mt-2 d-flex justify-content-center align-items-stretch">
                <div class="datoS card w-100 input-group border-secondary rounded-end-0">
                    <div class="card-body px-2 py-1">
                        <div class="d-flex align-items-center">
                            <div class="user-iconAP" style="background-image: url(Resources/imgs/perfil.jpg)"></div>
                            <div class="ms-2">
                                <div class="id_participante" hidden>${idParticipante}</div>
                                <div class="nombre_participante">${nombreParticipante}</div>
                                <div class="rol_participante" style="font-size: 10px;">${rolParticipante}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <button name="${accion}" class="btn btn-secondary btn-card-participante d-flex align-items-center justify-content-center rounded-start-0 rounded-end">${icono}</button>
            </div>
        `);

        return nuevoDiv;
    }
    //#endregion

    //#region Funciones Prepare
    function prepareAsignacion(id) {
        $('#id_plan').val(id);
        getParticipantes(id);
    }

    function prepareSelect(id) {
        tituloModal.text('Detalles sobre la Planificación');
        $('#rsdtForm').find(':input').prop('disabled', true);
        $('#botonesModal').addClass('opacity-0');
        show(id);
    }

    function prepareEdit(id) {
        tituloModal.text('Editar Planificación');
        $('#rsdtForm').find(':input').prop('disabled', false);
        show(id);
    }

    function prepareDelete(id) {
        tituloModal.text('Eliminar Planificación');
        mensajeModal.html('La siguiente Planificación se eliminará a continuación,<br>¿Está seguro?');
        $('#rsdtForm').find(':input:not(button)').prop('disabled', true);
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
    function getParticipantes(id, search = "") {
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: urlAsignaciones + id,
            type: 'GET',
            data: { _token: _token, search: search },
            dataType: 'json',
            success: function (response) {
                console.log(response);
                if (response.state) {
                    $('#titulo-asignacion').text(response.motivo);
                    let participantes = response.data;
                    contadorParticipantes = participantes.length;
                    if (contadorParticipantes > 0) {
                        participantes.forEach(participante => {
                            if (esParticipanteUnico(participante.id_rsdt, arrayParticipantes)) {
                                arrayParticipantes.push(participante.id_rsdt);
                                let divParticipante = crearDivParticipante(participante, 'Eliminar');
                                contenedorParticipantes.append(divParticipante);
                                modalParticipantes.modal('show');
                            }
                            else {
                                console.log('El participante ya se encuentra en la actividad.');
                            }
                        });
                        arrayParticipantesOriginal = arrayParticipantes.slice();
                    }
                    else {
                        console.log('Aún no hay participantes registrados.');
                    }
                    actualizarContadores();
                } else {
                    console.error(response.message);
                }

            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function getNoParticipantesOnSelect(id) {
        let _token = $('meta[name="csrf-token"]').attr('content');
        let select = $("#SelParti");

        $.ajax(
            {
                url: urlNoAsignaciones + id,
                type: 'GET',
                data: { _token: _token },
                dataType: 'json',
                success: function (response) {
                    let participantes = response.data;
                    if (participantes.length > 0) {
                        select.empty();
                        select.append($("<option>",
                            {
                                value: 0, text: 'Seleccionar Participante:', selected: true, disabled: true
                            }));
                        participantes.forEach(participante => {
                            let idParticipante = participante.id_rsdt;
                            let nombreParticipante = participante.nombre_rsdt + ' ' + participante.apellidop_rsdt;
                            let rolParticipante = participante.usuario.roles[0].name;

                            let option = `
                                <option data-rol='${rolParticipante}' value='${idParticipante}'>${nombreParticipante}</option>
                            `;
                            select.append(option);
                        });
                        modalParticipantes.modal('show');
                        actualizarContadorNuevos();
                    }
                    else {
                        select.empty();
                        select.append($("<option>",
                            {
                                value: 0, text: 'No hay participantes disponibles.', selected: true, disabled: true
                            }));
                    }
                },
                error: function (xhr, status, error) {
                    console.log("Error en la solicitud AJAX: " + error);
                }
            });
    }

    function updateAsignaciones(idPlanificacion, idsEliminados)
    {
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: urlUpdateAsignaciones,
            type: 'POST',
            data: { _token: _token, idPlanificacion: idPlanificacion, idsEliminados: idsEliminados },
            success: function (response) {
                console.log(response);
                if (response.state) {
                    resetAllOnModalParticipantes();
                    getParticipantes(idPlanificacion);
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

    function storeAsignaciones(idPlanificacion, idsParticipantes)
    {
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: urlStoreAsignaciones,
            type: 'POST',
            data: { _token: _token, idPlanificacion: idPlanificacion, idsParticipantes: idsParticipantes },
            success: function (response) {
                console.log(response);
                if (response.state) {
                    modalAsignaciones.modal('hide');
                    getParticipantes(idPlanificacion);
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
                            <th>ID</th>
                            <th>MOTIVO</th>
                            <th>AREA</th>
                            <th>PAGO</th>
                            <th>INICIO</th>
                            <th>FIN</th>
                            <th width="200">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                `;
                    response.data.data.forEach(planificacion => {
                        html += `
                        <tr>
                            <td>${planificacion.id_plan}</td>
                            <td>${planificacion.motivo_plan}</td>
                            <td>${planificacion.area_plan}</td>
                            <td>${planificacion.pago_plan}</td>
                            <td>${planificacion.inicio_plan}</td>
                            <td>${planificacion.fin_plan}</td>
                            <td>
                                <button id='btnAdd' data-id="${planificacion.id_plan}" class='btn p-0 btn-sm btn-info text-white' type='button' data-bs-toggle='modal' data-bs-target='#modalParticipantes'><i class='fas fa-users'></i></button>
                                <button id='btnSelect' data-id="${planificacion.id_plan}" class='btn p-0 btn-sm btn-success text-white' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-magnifying-glass-plus'></i></button>
                                <button id='btnEdit' data-id="${planificacion.id_plan}" class='btn p-0 btn-sm btn-secondary text-white' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-pen-to-square'></i></button>
                                <button id='btnDelete' data-id="${planificacion.id_plan}" class='btn p-0 btn-sm btn-primary' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-trash'></i></button>
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
                    searchInput.val(response.data.id_plan);
                    index(response.data.id_plan);
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
        let planificacion = data;
        $('#id_plan').val(planificacion.id_plan);
        $('#motivo_plan').val(planificacion.motivo_plan);
        $('#descripcion_plan').val(planificacion.descripcion_plan);
        $('#area_plan').val(planificacion.area_plan);
        $('#pago_plan').val(planificacion.pago_plan);
        $('#inicio_plan').val(planificacion.inicio_plan);
        $('#fin_plan').val(planificacion.fin_plan);
    }

    function show(id) {
        let _token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: urlShow + id,
            type: 'GET',
            data: { _token: _token },
            dataType: 'json',
            success: function (response) {
                console.log(response);
                if (response.state) {
                    llenarFormulario(response.data);
                    modalMain.modal('show');
                } else {
                    console.error(response.message);
                }
            },
            error: function (xhr, status, error) {
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
                    searchInput.val(response.data.id_plan);
                    index(response.data.id_plan);
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

    function destroy(id) {
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: urlDelete + id,
            type: 'DELETE',
            data: { _token: _token },
            success: function (response) {
                if (response.state) {
                    console.log(response.message);
                    searchInput.val('');
                    index();
                    modalMain.modal('hide');
                } else {
                    console.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    //#endregion

    //#region Interacción DOM

    //#region Extras
    $('#asignacionBtn').on('click', function () {
        let id = $('#id_plan').val();
        getNoParticipantesOnSelect(id);
    });

    $("#SelParti").select2({
        theme: "bootstrap-5",
        selectionCssClass: "select2--small",
        dropdownCssClass: "select2--small",
        dropdownParent: $('#modalAsignaciones')
    });

    $('#btnRegresarAsig').on('click', function () {
        let id = $('#id_plan').val();
        getParticipantes(id);
    });

    $('#SelParti').on('change', function() {
        let idParticipante = parseInt($(this).find('option:selected').val());
        if (idParticipante > 0)
        {
            let nombreParticipante = $(this).find('option:selected').text();
            let rolParticipante = $(this).find('option:selected').data('rol');
            let participante = {
                id_rsdt: idParticipante,
                nombre_rsdt: nombreParticipante,
                usuario: { roles: [ { name: rolParticipante } ] }
            };

            if (esParticipanteUnico(idParticipante, arrayParticipantesNuevos))
            {
                let nuevoDiv = crearDivParticipante(participante, 'Eliminar');
                contenedorSeleccionados.append(nuevoDiv);
                arrayParticipantesNuevos.push(idParticipante);
                actualizarContadorNuevos();

                btnAsignar.prop('disabled', arrayParticipantes.length > 0)
            }
            else
            {
                console.log('El participante ya se encuentra en la lista.');
            }
        }
        resetSelect();
    });
    //#endregion

    //#region Funciones de Carga Inicial
    index();
    //#endregion

    //#region Busqueda
    searchInput.on('input', function () {
        index($(this).val());
    });

    $('#totalResultados').on('input', function () {
        index(searchInput.val());
    });

    $('#searchParticipantes').on('input', function() {
        let searchText = $(this).val().toLowerCase();
        let resultadosEncontrados = false;
        $('#participantesContainer .datoUser').each(function() {
            let nombreParticipante = $(this).find('.nombre_participante').text().toLowerCase();
            if (nombreParticipante.includes(searchText)) {
                $(this).removeClass('d-none');
                resultadosEncontrados = true;
            } else {
                $(this).addClass('d-none');
            }
        });

        $('#participantesEliminadosContainer .datoUser').each(function() {
            let nombreParticipante = $(this).find('.nombre_participante').text().toLowerCase();
            if (nombreParticipante.includes(searchText)) {
                $(this).removeClass('d-none');
                resultadosEncontrados = true;
            } else {
                $(this).addClass('d-none');
            }
        });

        if (!resultadosEncontrados) {
            $('#noResultsMessage').removeClass('d-none');
        } else {
            $('#noResultsMessage').addClass('d-none');
        }
    });
    //#endregion

    //#region CRUD
    btnCrud.click(function (e) {
        e.preventDefault();
        let action = $(this).attr('name');
        let id = $('#id_plan').val();
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

    btnAsignar.click(function () {
        let id = $('#id_plan').val();
        storeAsignaciones(id, arrayParticipantesNuevos);
    });

    btnUpdateAsignaciones.click(function () {
        let id = $('#id_plan').val();
        updateAsignaciones(id, arrayParticipantesEliminados);
    });
    //#endregion

    //#region Activacion de botones de CRUD
    tableIndex.on('click', '#btnAdd', function () {
        let id = $(this).data('id');
        prepareAsignacion(id);
    });

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
    modalMain.on('hidden.bs.modal', function () {
        resetAllOnModal();
    });

    modalParticipantes.on('hidden.bs.modal', function () {
        resetAllOnModalParticipantes();
    });

    modalAsignaciones.on('hidden.bs.modal', function () {
        resetAllOnModalAsignaciones();
    });
    //#endregion

    //#region Interaccion con Cards de Participantes en el Modal Participantes
    modalParticipantes.on('click', '.btn-card-participante', function () {
        let divParticipante = $(this).closest('.datoUser');
        let idParticipante = parseInt(divParticipante.find('.id_participante').text());
        let nombreParticipante = divParticipante.find('.nombre_participante').text();
        let rolParticipante = divParticipante.find('.rol_participante').text();
        let participante = { id_rsdt: idParticipante, nombre_rsdt: nombreParticipante, usuario: { roles: [{ name: rolParticipante }] } }
        let accion = $(this).attr('name');

        moverParticipante(participante, accion, divParticipante);
    });

    modalAsignaciones.on('click', '.btn-card-participante', function() {
        let divParticipante = $(this).closest('.datoUser');
        let idParticipanteDiv = parseInt(divParticipante.find('.id_participante').text());
        let accion = $(this).attr('name');

        if (accion == 'Eliminar')
        {
            arrayParticipantesNuevos = arrayParticipantesNuevos.filter(idParticipante => idParticipante !== idParticipanteDiv);
            actualizarContadorNuevos();
            divParticipante.remove();
        }
    });
    //#endregion
});
