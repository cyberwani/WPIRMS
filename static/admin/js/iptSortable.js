/**
 * iPanel Themes Table Sortable
 * Used to quickly add Add New/Drag and Sort/Delete UI using widefat tables.
 */

(function($) {
    var methods = {
        init : function(options) {

            return $(this).each(function() {
                //get the submit button
                var $submit_button = $(this).find('> .ipt-sortable-foot input.ipt-sortable-button');

                //make the data not submittable
                $(this).find('> .ipt-sortable-data').find('input, select, textarea').attr('disabled', true);

                //get some variables
                var vars = {
                    sort : true,
                    add : ($submit_button.length && $submit_button.hasClass('add') ? true : false),
                    del : ($submit_button.length && $submit_button.hasClass('del') ? true : false),
                    count : ($submit_button.length && $submit_button.data('count') ? $submit_button.data('count') : 0),
                    key : ($submit_button.length && $submit_button.data('key') ? $submit_button.data('key') : '__key__'),
                    confirmDel : ($submit_button.length && $submit_button.data('confirmDel') ? $submit_button.data('confirmDel') : 'Are you sure you want to delete? This can not be undone.'),
                    confirmTitle : ($submit_button.length && $submit_button.data('confirmTitle') ? $submit_button.data('confirmTitle') : 'Confirmation of Deletion')
                };
                //alert(typeof($submit_button.data('count')));

                //store this
                $(this).data('iptSortableData', vars);

                //make them sortable
                if(vars.sort)
                    methods.sort.apply(this);

                //make them deletable
                if(vars.del) {
                    methods.attachDel.apply(this, [vars]);
                }

                var $this = this;
                //attach to add new
                if(vars.add) {
                    $submit_button.click(function(e) {
                        e.preventDefault();
                        methods.add.apply($this);
                    });
                }
            });
        },

        attachDel : function(vars) {
            $(this).find('> .ipt-sortable-body > .ipt-sortable-elem > .ipt-sortable-del > img').click(function() {
                var $this = this;
                var dialog = $('<p>' + vars.confirmDel + '</p>');
                dialog.dialog({
                    autoOpen : true,
                    modal : true,
                    minWidth : 400,
                    closeOnEscape : true,
                    title : vars.confirmTitle,
                    buttons : {
                        'Confirm' : function() {
                            methods.del.apply($this);
                            $(this).dialog('close');
                        },
                        'Cancel' : function() {
                            $(this).dialog('close');
                        }
                    }
                });
            });
        },

        attachAddDel : function(vars) {
            $(this).find('> .ipt-sortable-del > img').click(function() {
                var $this = this;
                var dialog = $('<p>' + vars.confirmDel + '</p>');
                dialog.dialog({
                    autoOpen : true,
                    modal : true,
                    minWidth : 400,
                    closeOnEscape : true,
                    title : vars.confirmTitle,
                    buttons : {
                        'Confirm' : function() {
                            methods.del.apply($this);
                            $(this).dialog('close');
                        },
                        'Cancel' : function() {
                            $(this).dialog('close');
                        }
                    }
                });
            });
        },

        sort : function() {
            $(this).find('> .ipt-sortable-body').sortable({
                items : 'div.ipt-sortable-elem',
                placeholder : 'ipt-sortable-highlight'
            });
        },

        del : function() {
            var target = $(this).parent().parent();
            target.slideUp('normal');
            target.css('background-color', '#ffaaaa').animate({'background-color' : '#ffffff'}, 'normal', function() {
                target.stop().remove();
            });
        },

        add : function() {
            var vars = $(this).data('iptSortableData');

            //make the data not submittable
            $(this).find('> .ipt-sortable-data').find('input, select, textarea').attr('disabled', false);

            var $add_string = $(this).find('> .ipt-sortable-data').html();

            //make the data not submittable
            $(this).find('> .ipt-sortable-data').find('input, select, textarea').attr('disabled', true);
            //alert($add_string);
            var count = vars.count++;
            var re = new RegExp(methods.quote(vars.key), 'g');

            $add_string = $add_string.replace(re, count);
            //alert($add_string);

            var new_div = $('<div class="ipt-sortable-elem" />').append($($add_string));

            $(this).find('> .ipt-sortable-body').append(new_div);

            var old_color = new_div.css('background-color');

            new_div.hide().slideDown('fast').css('background-color', '#aaffaa').animate({'background-color' : old_color}, 'normal');

            //attach the delete
            if(vars.del) {
                methods.attachAddDel.apply(new_div, [vars]);
            }

            //attach the sortable function again if anything is present
            if($('.ipt-sortable', new_div).length) {
                $('.ipt-sortable', new_div).iptSortable();
            }
        },

        quote : function(str) {
            return str.replace(/([.?*+^$[\]\\(){}|-])/g, "\\$1");
        }
    };

    $.fn.iptSortable = function(method) {
        if(methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof(method) == 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jquery.daembed');
            return this;
        }
    }
})(jQuery);
