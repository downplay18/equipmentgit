/**
 * Site : http:www.smarttutorials.net
 * @author muni
 */

/*เป็น auto complete แบบ บรรทัดเดียว*/

$('#varDetail_1').autocomplete({
    source: function (request, response) {
        $.ajax({
            url: 'ajax.php',
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
        $('#var_suffix_1').val(names[1]);
    }
});
