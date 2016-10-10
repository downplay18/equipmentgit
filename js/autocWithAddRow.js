/**
 * Site : http:www.smarttutorials.net
 * @author muni
 */

$(".delete").on('click', function () {
    $('.case:checkbox:checked').parents("tr").remove();
    $('.check_all').prop("checked", false);
    check();
});
var i = $('table tr').length;

$(".addmore").on('click', function () {
    count = $('table tr').length;

    var data = "<tr><td><input type='checkbox' class='case'/></td><td><span id='snum" + i + "'>" + count + ".</span></td>";
    data += "<td><input class='form-control' type='text' id='zdir_" + i + "' name='var_zdir[]'/></td> \n\
<td><input class='form-control' type='text' id='varDetail_" + i + "' name='varDetail[]'/></td> \n\
<td><input class='form-control' type='text' id='var_slipSuffix_" + i + "' name='var_slipSuffix[]'/></td> \n\
<td><input class='form-control' type='number' id='var_qty_" + i + "' name='var_qty[]'/></td> \n\
<td><input class='form-control' type='number' id='var_unitPrice_" + i + "' name='var_unitPrice[]'/></td> \n\
<td><input class='form-control' type='number' id='var_amount_" + i + "' name='var_amount[]'/></td> \n\
<td bgcolor='#ffffe6'><input class='form-control' type='text' id='var_lastSuffix_" + i + "' name='var_lastSuffix[]'/></td>\n\
<td bgcolor='#ffffe6'><input class='form-control' type='number' id='var_lastQty_" + i + "' name='var_lastQty[]'/></td></tr>";
    $('table').append(data);
    //row = i;
    $('#varDetail_' + i).autocomplete({
        source: function (request, response) {
            $.ajax({
                url: 'add_autoc_ajax.php',
                dataType: "json",
                method: 'post',
                data: {
                    name_startsWith: request.term,
                    type: 'item_table',
                    row_num: i
                },
                success: function (data) {
                    response($.map(data, function (item) {
                        var code = item.split("|");
                        return {
                            label: code[0],
                            value: code[0],
                            data: item
                        };
                    }));
                }
            });
        },
        autoFocus: true,
        minLength: 0,
        select: function (event, ui) {
            var names = ui.item.data.split("|");
            id_arr = $(this).attr('id');
            id = id_arr.split("_");
            $('#var_lastSuffix_' + id[1]).val(names[1]);
        }
    });
    i++;
});

function select_all() {
    $('input[class=case]:checkbox').each(function () {
        if ($('input[class=check_all]:checkbox:checked').length == 0) {
            $(this).prop("checked", false);
        } else {
            $(this).prop("checked", true);
        }
    });
}

function check() {
    obj = $('table tr').find('span');
    $.each(obj, function (key, value) {
        id = value.id;
        $('#' + id).html(key + 1);
    });
}

$('#varDetail_1').autocomplete({
    source: function (request, response) {
        $.ajax({
            url: 'add_autoc_ajax.php',
            dataType: "json",
            method: 'post',
            data: {
                name_startsWith: request.term,
                type: 'item_table',
                row_num: 1
            },
            success: function (data) {
                response($.map(data, function (item) {
                    var code = item.split("|");
                    return {
                        label: code[0],
                        value: code[0],
                        data: item
                    }
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 0,
    select: function (event, ui) {
        var names = ui.item.data.split("|");
        $('#var_lastSuffix_1').val(names[1]);
    }
});
