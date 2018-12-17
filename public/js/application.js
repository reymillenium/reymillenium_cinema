$(document).ready(function () {

    // Permite marcar y desmarcar todos los checkbox (.sub_chk) a la vez usando un master checkbox (.master)
    $('#master').on('click', function (e) {
        if ($(this).is(':checked', true)) {
            $(".sub_chk").prop('checked', true);
        } else {
            $(".sub_chk").prop('checked', false);
        }
    });

    // Defino la acción al presionar el botón de borrar todos
    $('.delete_all').on('click', function (e) {

        var allVals = [];
        $(".sub_chk:checked").each(function () {
            allVals.push($(this).attr('data-id'));
        });

        if (allVals.length <= 0) {
            // alert("Please select row.");
            alertify.alert("Error de selección", "Debe marcar al menos una fila para borrar");
        } else {

            // Muestro al usuario una confirmación de borrado de acuerdo a la cantidad de filas seleccionadas

            // if (allVals.length == 1) {
            //     var check = alertify.confirm("¿Está usted seguro que desea borrar esta fila?").set({'title': 'Confirmación de borrado'});
            // } else {
            //
            //     var check = alertify.confirm("¿Está usted seguro que desea borrar esta fila?").set({'title': 'Confirmación de borrado'});
            // }

            var check = (alertify.confirm(allVals.length == 1 ? "¿Está usted seguro que desea borrar la fila seleccionada?" : "¿Está usted seguro que desea borrar todas las filas seleccionadas?").set({'title': 'Confirmación de borrado'}));

            // Verifico si escogió borrar o no borrar
            if (check == true) { // Si escogió borrar...

                var join_selected_values = allVals.join(",");

                $.ajax({
                    url: $(this).data('url'),
                    type: 'DELETE',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: 'ids=' + join_selected_values,
                    success: function (data) {
                        if (data['success']) {
                            $(".sub_chk:checked").each(function () {
                                $(this).parents("tr").remove();
                            });
                            alert(data['success']);
                        } else if (data['error']) {
                            alert(data['error']);
                        } else {
                            alert('Whoops Something went wrong!!');
                        }
                    },
                    error: function (data) {
                        alert(data.responseText);
                    }
                });

                $.each(allVals, function (index, value) {
                    $('table tr').filter("[data-row-id='" + value + "']").remove();
                });
            }
        }
    });

    $('[data-toggle=confirmation]').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        onConfirm: function (event, element) {
            element.trigger('confirm');
        }
    });

    $(document).on('confirm', function (e) {
        var ele = e.target;
        e.preventDefault();

        $.ajax({
            url: ele.href,
            type: 'DELETE',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                if (data['success']) {
                    $("#" + data['tr']).slideUp("slow");
                    alert(data['success']);
                } else if (data['error']) {
                    alert(data['error']);
                } else {
                    alert('Whoops Something went wrong!!');
                }
            },
            error: function (data) {
                alert(data.responseText);
            }
        });

        return false;
    });

    // Permite colorear una fila entera de una tabla siempre y cuando tenga su checkbox checkeado
    $('input[name="chkOrgRow"]').on('change', function () {
        $(this).closest('tr').toggleClass('selected_checkbox_row', $(this).is(':checked'));
    });


});