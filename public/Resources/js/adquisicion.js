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
    let urlGetRep = $('#url-get-rep').val();
    let urlGetDptosDisp = $('#url-get-dptos-disp').val();
    let urlGetDptos = $('#url-dpto').val() + '/index';
    let urlDepartamento = $('#url-dpto').val() + '/';
    //#endregion

    //#region Funciones Extras
    function formatearFecha(fecha) {
        fecha.setHours(fecha.getHours() - 4);
        return fecha.toISOString().slice(0, 10);
    }

    function getRepresentantesOnSelect() {
        let _token = $('meta[name="csrf-token"]').attr('content');
        let select = $("#residente_id_reg");

        $.ajax(
            {
                url: urlGetRep,
                type: 'GET',
                data: { _token: _token },
                dataType: 'json',
                success: function (response) {
                    let representantes = response.data;

                    if (representantes.length > 0) {
                        select.empty();
                        representantes.forEach(representante => {
                            select.append($("<option>",
                                {
                                    value: representante.id_rsdt, text: representante.nombre_rsdt + ' ' + representante.apellidop_rsdt
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

    function getDptosOnSelect() {
        let _token = $('meta[name="csrf-token"]').attr('content');
        let select = $("#departamento_id_reg");
        let url = urlGetDptosDisp;
        $.ajax(
            {
                url: url,
                type: 'GET',
                data: { _token: _token },
                dataType: 'json',
                success: function (response) {
                    let departamentos = response.data;

                    if (departamentos.length > 0) {
                        select.empty();
                        departamentos.forEach(departamento => {
                            select.append($("<option>",
                                {
                                    value: departamento.id_dpto, text: departamento.codigo_dpto
                                }));
                        });
                        select.children('option').first().prop('selected', true).trigger('change');
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

    function getDptosDisponiblesOnSelect() {
        let _token = $('meta[name="csrf-token"]').attr('content');
        let select = $("#departamento_id_reg");
        let url = urlGetDptosDisp;
        $.ajax(
            {
                url: url,
                type: 'GET',
                data: { _token: _token },
                dataType: 'json',
                success: function (response) {
                    let departamentos = response.data;
                    let countDisp = 0;

                    if (departamentos.length > 0) {
                        select.empty();
                        departamentos.forEach(departamento => {
                            if (departamento.estado_dpto == 'DISPONIBLE') {
                                select.append($("<option>",
                                    {
                                        value: departamento.id_dpto, text: departamento.codigo_dpto
                                    }));
                                countDisp++;
                            }
                        });
                        if (countDisp > 0) {
                            select.children('option').first().prop('selected', true).trigger('change');
                        }
                        else {
                            $('#seccionAdquisicion').addClass('d-none');
                            mensajeModal.html('No hay departamentos disponibles para registrar una nueva adquisición.');
                            $('#botonesModal').addClass('opacity-0');
                        }
                    }
                    else {
                        console.log('No hay resultados');
                    }
                },
                error: function (xhr, status, error) {
                    console.log("Error en la solicitud AJAX: " + error);
                }
            });
    }

    function getPagoTotalDpto(id, tipoAdq) {
        let _token = $('meta[name="csrf-token"]').attr('content');
        let inputPago = $('#pago_reg');
        $.ajax({
            url: urlDepartamento + id,
            type: 'GET',
            data: { _token: _token },
            dataType: 'json',
            success: function (response) {
                if (response.state) {
                    let seccionFecha = $('#seccion_fecha_fin');
                    let labelPago = $('#labelPago');
                    if (tipoAdq == 'Compra') {
                        seccionFecha.collapse('hide');
                        labelPago.text('Pago por compra Bs.:');
                        inputPago.val(response.data.precio_dpto);
                    } else {
                        let fechaInicio = new Date($('#inicio_reg').val() + 'T00:00:00Z');
                        let fechaFin = new Date($('#fin_reg').val());
                        if (isNaN(fechaFin.getTime())) {
                            fechaFin = new Date(fechaInicio);
                            fechaFin.setMonth(fechaInicio.getMonth() + 1);
                            $('#fin_reg').val(fechaFin.toISOString().slice(0, 10));
                        }
                        let diferenciaMilisegundos = fechaFin - fechaInicio;
                        let diferenciaDias = Math.ceil(diferenciaMilisegundos / (1000 * 60 * 60 * 24));

                        let pago = response.data.precioa_dpto;
                        labelPago.text(`Pago de alquiler: ${diferenciaDias} días x ${pago} Bs.`);
                        inputPago.val(pago * diferenciaDias);

                        seccionFecha.collapse('show');
                    }
                } else {
                    console.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function resetAllOnModal() {
        $('#rsdtForm')[0].reset(); // Reiniciar Formulario
        $('#rsdtForm').find(':input').not('#pago_reg').prop('disabled', false);
        $('#botonesModal').removeClass('opacity-0');
        $('#tipoadq_reg').val('Compra');
        $('#labelPago').text('Pago por compra Bs.:');
        $('#seccion_fecha_fin').removeClass('show');
        tituloModal.text('Nueva Adquisición');
        mensajeModal.html('');
        btnCrud.attr('name', 'store');
        btnCrud.text('Registrar');
        getDptosOnSelect();
        $('#inicio_reg').val(formatearFecha(new Date()));
        let fechaFin = new Date();
        fechaFin.setMonth(fechaFin.getMonth() + 1);
        $('#fin_reg').val(formatearFecha(fechaFin));
        $('#seccionAdquisicion').removeClass('d-none');
    }
    //#endregion

    //#region Funciones Prepare
    function prepareSelect(id) {
        tituloModal.text('Detalles sobre la Adquisición');
        $('#rsdtForm').find(':input').prop('disabled', true);
        $('#botonesModal').addClass('opacity-0');
        show(id);
    }

    function prepareEdit(id) {
        tituloModal.text('Editar Adquisición');
        $('#rsdtForm').find(':input').prop('disabled', false);
        $('#pago_reg').prop('disabled', true);
        $('#departamento_id_reg').prop('disabled', true);
        show(id);
    }

    function prepareDelete(id) {
        tituloModal.text('Eliminar Adquisición');
        mensajeModal.html('La siguiente adquisición se eliminará a continuación,<br>¿Está seguro?');
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
                            <th>DEPARTAMENTO</th>
                            <th>RESIDENTE</th>
                            <th>ADQUISICIÓN</th>
                            <th>FECHA DE INICIO</th>
                            <th>FECHA DE CONCLUSIÓN</th>
                            <th>TOTAL PAGADO BS.</th>
                            <th width="200">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                `;
                    response.data.data.forEach(adquisicion => {
                        let codigoDepartamento = adquisicion.departamento.codigo_dpto;
                        let nombreResidente = `${adquisicion.residente.nombre_rsdt} ${adquisicion.residente.apellidop_rsdt}`;
                        html += `
                        <tr>
                            <td>${adquisicion.id_reg}</td>
                            <td>${codigoDepartamento}</td>
                            <td>${nombreResidente}</td>
                            <td>${adquisicion.tipoadq_reg}</td>
                            <td>${adquisicion.inicio_reg}</td>
                            <td>${adquisicion.fin_reg ?? 'Sin fecha'}</td>
                            <td>${adquisicion.pago_reg}</td>
                            <td>
                                <button id='btnSelect' data-id="${adquisicion.id_reg}" class='btn p-0 btn-sm btn-info text-white' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-magnifying-glass-plus'></i></button>
                                <button id='btnEdit' data-id="${adquisicion.id_reg}" class='btn p-0 btn-sm btn-secondary text-white' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-pen-to-square'></i></button>
                                <button id='btnDelete' data-id="${adquisicion.id_reg}" class='btn p-0 btn-sm btn-primary' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-trash'></i></button>
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
                    let paginationContainer = $('#pagination-container');
                    paginationContainer.empty();
                    seccionTotalResultados.addClass('d-none');
                    error.html('No se encontraron resultados.')
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function store() {
        $('#pago_reg').prop('disabled', false);
        let formData = $('#rsdtForm').serialize();
        console.log(formData);
        $.ajax({
            url: urlStore,
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log(response);
                if (response.state) {
                    searchInput.val(response.data.id_reg);
                    index(response.data.id_reg);
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
            },
            complete: function () {
                $('#pago_reg').prop('disabled', true);
            }
        });
    }

    function llenarFormulario(data) {
        let adquisicion = data;
        $('#id_reg').val(adquisicion.id_reg);
        $('#departamento_id_reg').val(adquisicion.departamento_id_reg);
        $('#residente_id_reg').val(adquisicion.residente_id_reg);
        $('#tipoadq_reg').val(adquisicion.tipoadq_reg);
        if (adquisicion.tipoadq_reg == 'Alquiler') {
            $('#seccion_fecha_fin').addClass('show');
            let inicio = new Date($('#inicio_reg').val());
            let fin = new Date($('#fin_reg').val());
            let diferenciaMilisegundos = fin - inicio;
            let diferenciaDias = Math.ceil(diferenciaMilisegundos / (1000 * 60 * 60 * 24));
            $('#labelPago').text(`Pago de alquiler: ${diferenciaDias} días x ${adquisicion.pago_reg / diferenciaDias} Bs.`);
        }
        else
        {
            $('#labelPago').text('Pago por compra Bs.:');
        }
        $('#inicio_reg').val(adquisicion.inicio_reg);
        $('#fin_reg').val(adquisicion.fin_reg);
        $('#pago_reg').val(adquisicion.pago_reg);
    }

    function show(id) {
        let _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: urlShow + id,
            type: 'GET',
            data: { _token: _token },
            dataType: 'json',
            success: function (response) {
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
        $('#pago_reg').prop('disabled', false);
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
                    searchInput.val(response.data.id_reg);
                    index(response.data.id_reg);
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
            },
            complete: function () {
                $('#pago_reg').prop('disabled', true);
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
    //#region Select2 con Buscador
    $("#propietario_id_mas").select2({
        theme: "bootstrap-5",
        selectionCssClass: "select2--small",
        dropdownCssClass: "select2--small",
        dropdownParent: $('#modalMain')
    });
    //#endregion
    //#endregion

    //#region Funciones de Carga Inicial
    index();
    getDptosOnSelect();
    getRepresentantesOnSelect();
    $('#inicio_reg').val(formatearFecha(new Date()));
    let fechaFin = new Date();
    fechaFin.setMonth(fechaFin.getMonth() + 1);
    $('#fin_reg').val(formatearFecha(fechaFin));

    //#region Busqueda
    searchInput.on('input', function () {
        index($(this).val());
    });

    $('#totalResultados').on('input', function () {
        index(searchInput.val());
    });
    //#endregion

    //#region CRUD
    $('#btnAgregar').on('click', function () {
        getDptosDisponiblesOnSelect();
    });

    btnCrud.click(function (e) {
        e.preventDefault();
        let action = $(this).attr('name');
        let id = $('#id_reg').val();
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

    //#region Select Departamento y Adq On Change
    $('#departamento_id_reg').on('change', function () {
        getPagoTotalDpto($(this).val(), $('#tipoadq_reg').val());
    });

    $('#tipoadq_reg').on('change', function () {
        let tipoAdquisicion = $(this).val();
        let idDpto = $('#departamento_id_reg').val();
        getPagoTotalDpto(idDpto, tipoAdquisicion);
    });

    $('#fin_reg').on('input', function () {
        getPagoTotalDpto($('#departamento_id_reg').val(), $('#tipoadq_reg').val());
    });

    $('#inicio_reg').on('input', function () {
        getPagoTotalDpto($('#departamento_id_reg').val(), $('#tipoadq_reg').val());
    });
    //#endregion

    //#region Configuracion en Modales
    modalMain.on("show.bs.modal", function () {
    });

    modalMain.on('hidden.bs.modal', function () {
        resetAllOnModal();
    });
    //#endregion
});
