/**
 * Registration Form Related jQuery
 *
 * @package Registration Portal Management
 * @subpackage javascript
 */
Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator) {
    var n = this,
    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
    decSeparator = decSeparator == undefined ? "." : decSeparator,
    thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
    sign = n < 0 ? "-" : "",
    i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
    j = (j = i.length) > 3 ? j % 3 : 0;
    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
};
jQuery(document).ready(function($) {

    var form_validation = $('#ipt_rpm_form').validationEngine({scroll : false});

    $('#ipt_rpm_form').find('input[type="reset"]').click(function(e) {
        e.preventDefault();
        $('#ipt_rpm_form').find('input:checked').attr('checked', false).trigger('change');
        $('#ipt_rpm_form').find('input[type="text"]').val('');
    });

    var submit_form_func = function() {
        var layer_div = $('<div />').css({
            position : 'fixed',
            zIndex : 99999,
            width : $(window).width(),
            height : $(window).height(),
            backgroundColor : 'rgba(0,0,0,0.7)',
            left : 0,
            top : 0
        });
        $('<div class="ajax_img" />').css({
            position : 'absolute',
            width : 200,
            height : 50,
            top : '50%',
            left : '50%',
            backgroundColor : '#ffffff',
            border : '1px solid #dfdfdf',
            marginLeft : -100,
            marginTop : -25
        }).appendTo(layer_div);
        $('body').append(layer_div);
        var data = form_validation.serialize();
        $.post(ajaxurl, data, function(json) {
            if(json.status == true) {
                var title = 'Successfully added the registration';
                if($('#data_id').val() == '') {
                    $('#ipt_rpm_form').find('input[type="reset"]').trigger('click');
                } else {
                    title = 'Successfully renewed the registration';
                }
                layer_div.remove();
                form_validation.validationEngine('hideAll');
                tb_show(title, 'admin-ajax.php?action=ipt_rpm_view_registration&id=' + json.id);
                //@todo add dialog to show the submission
            } else {
                form_validation.validationEngine('hideAll');
                for(var a in json.errors) {
                    $('#' + json.errors[a][0]).validationEngine('showPrompt', json.errors[a][2]);
                }
            }

        }, 'json').fail(function() {
            alert('Could not save due to AJAX error.');
        }).always(function() {
            layer_div.remove();
        });
    };
    //ajax submission
    form_validation.submit(function(e) {
        e.preventDefault();
        if(!form_validation.validationEngine('validate')) {
            return;
        }
        var confirm = $('<div><p>Once a registration form is submitted it can not be degraded.</p><p>Please confirm your submission by clicking the yes button.</p></div>').dialog({
            buttons : {
                'YES' : function() {
                    $(this).dialog('close');
                    submit_form_func();
                },
                'NO' : function() {
                    $(this).dialog('close');
                }
            },
            title : 'Confim submission',
            modal : true,
            minWidth : 400
        });
    });

    //change on portal
    if($('#data_portal').length) {
        $('#data_portal').change(function() {
            var prefix = $(this).find('option:selected').data('prefix');
            var portal = $(this).find('option:selected').val();
            $('#ipt_rpm_portal_prefix_input').val(portal);
            $('#ipt_rpm_portal_prefix').html(prefix);
            $('#data_code').validationEngine('validate');
        });
    }

    //auto generate code
    if($('#auto_button').length) {
        $('#auto_button').click(function(e) {
            e.preventDefault();
            $(this).attr('disabled', true);
            $('#data_portal').trigger('change');
            $('#data_code').val('Please wait...').attr('readonly', true);
            $.get(ajaxurl, {
                action : 'ipt_rpm_gen_reg_code',
                portal : $('#ipt_rpm_portal_prefix_input').val()
            }, function(code) {
                $('#data_code').val(parseInt(code));
                $('#data_code').attr('readonly', false);
                $('#data_code').validationEngine('validate');
            }).fail(function() {
                alert('Could not get due to AJAX error.');
            }).always(function() {
                $('#auto_button').attr('disabled', false);
            });
        })
    }

    //change on registration specific questions
    if($('input.tr-toggle').length) {
        $('input.tr-toggle').each(function() {
            var target_one = $(this).parent().parent().prev();
            var target_two = $(this).parent().parent().next();
            var toggle_trs = $(this).parent().parent().parent().siblings('tr.' + $(this).data('trs'));
            var fee = $(this).data('regFee');

            var total_fee_target = $(this).parent().parent().parent().parent().parent().find('tfoot th.total');
            var currency = total_fee_target.data('currency');

            //failsafe
            //comes the problem, probably because the form was refreshed
            if($(this).is(':checked')) {
                if(toggle_trs.is(':hidden')) {
                    $(this).attr('checked', false);
                }
            } else {
                if(toggle_trs.is(':visible')) {
                    $(this).attr('checked', true);
                }
            }

            var close_row_span = $(this).is(':checked') ? (parseInt(target_one.attr('rowspan')) - toggle_trs.length) : parseInt(target_one.attr('rowspan'));
            var open_row_span = close_row_span + toggle_trs.length;


            $(this).change(function() {
                var total = total_fee_target.data('totalFee');
                if($(this).is(':checked')) {
                    //alert(total);
                    //update the fee
                    total += fee;
                    //alert(total);
                    target_one.attr('rowspan', open_row_span);
                    target_two.attr('rowspan', open_row_span);
                    toggle_trs.show().css('background-color', '#aaffaa').stop(true, true).animate({'background-color' : '#f9f9f9'}, 'normal');
                } else {
                    //update the fee
                    //alert(total);
                    total -= fee;
                    //alert(total);
                    target_one.attr('rowspan', close_row_span);
                    target_two.attr('rowspan', close_row_span);
                    toggle_trs.hide();
                }

                total_fee_target.html(currency + '&nbsp;' + total.formatMoney());
                total_fee_target.data('totalFee', total);
            });
        });
    }
});



