$(document).ready(function(e) {
    //#region Configuraciones Iniciales
    //#endregion

    //#region Paginación

    //#endregion

    //#region Funciones
    function index(search)
    {
        let url = $('#url-index').val();
        let table = $('#tabla');
        let _token = $('input[name="_token"]').val();
        let error = $('#index-error');
        let html = "";

        $.ajax({
            url: url,
            type: 'POST',
            data: { _token: _token, search: search },
            dataType: 'json',
            success: function(response)
            {
                console.log(response);
                table.empty();
                if (response.data.data.length > 0)
                {
                    error.html('');
                    html = `
                        <thead class="table-secondary fw-semibold">
                            <tr>
                                <th>CI</th>
                                <th>NOMBRE</th>
                                <th>FECHA DE NACIMIENTO</th>
                                <th>TELÉFONO</th>
                                <th>REPRESENTANTE</th>
                                <th width="200">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                    `;
                    response.data.data.forEach(residente => {
                        html += `
                            <tr>
                                <td>${residente.ci_rsdt}</td>
                                <td>${residente.nombre_rsdt} ${residente.apellidop_rsdt} ${residente.apellidom_rsdt}</td>
                                <td>${residente.fechanac_rsdt}</td>
                                <td>${residente.telefono_rsdt}</td>
                                <td>${residente.nombre_representante == null ? 'Ninguno' : residente.nombre_representante + ' ' + residente.apellido_representante}</td>
                                <td>
                                    <button id='btnSelect' data-id="${residente.id_rsdt}" class='btn p-0 btn-sm btn-info text-white' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-magnifying-glass-plus'></i></button>
                                    <button id='btnEdit' data-id="${residente.id_rsdt}" class='btn p-0 btn-sm btn-secondary text-white' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-pen-to-square'></i></button>
                                    <button id='btnDelete' data-id="${residente.id_rsdt}" class='btn p-0 btn-sm btn-primary' type='button' data-bs-toggle='modal' data-bs-target='#modalMain'><i class='fa-solid fa-trash'></i></button>
                                </td>
                            </tr>
                        `;
                    });
                    html += `</tbody>`;
                    table.append(html);
                }
                else
                {
                    error.html('No se encontraron resultados.')
                }
            },
            error: function(xhr, status, error)
            {
                console.error(xhr.responseText);
            }
        });
    }

    function store(event)
    {
        let formData = $('#rsdtForm').serialize();
        let url = $('#rsdtForm').data('url');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response)
            {
                console.log(response);
            },
            error: function(xhr, status, error)
            {
                console.error(xhr.responseText);
            }
        });
    }
    //#endregion

    //#region Interacción DOM
    index("");

    $("#search").on('input', function(e) {
        e.preventDefault();
        index($(this).val());
    });

    $("#storeBtn").click(function(e) {
        store(e);
    });
    //#endregion
});
