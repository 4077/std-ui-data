// head {
var __nodeId__ = "std_ui_data__main_tree";
var __nodeNs__ = "std_ui_data";
// }

(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, {
        options: {},

        _create: function () {
            this.bind();
        },

        _setOption: function (key, value) {
            $.Widget.prototype._setOption.apply(this, arguments);
        },

        bind: function () {
            var widget = this;

            $(".right.array", widget.element).rebind("click", function (e) {
                request(widget.options.paths.togglePath, {
                    instance: widget.options.instance,
                    path:     $(this).closest("tr").attr("node_path")
                });

                e.stopPropagation();
            });

            $(".left", widget.element).rebind("click contextmenu", function (e) {
                request(widget.options.paths.contextmenu, {
                    instance: widget.options.instance,
                    path:     $(this).closest("tr").attr("node_path")
                });

                e.stopPropagation();
                e.preventDefault();
            });

            $(".right.bool", widget.element).rebind("click", function () {
                request(widget.options.paths.toggleBoolValue, {
                    instance: widget.options.instance,
                    path:     $(this).closest("tr").attr("node_path")
                });
            });

            $(".right.string, .right.number", widget.element).each(function () {
                widget._stringValueBind($(this));
            });
        },

        _stringValueBind: function (stringControl) {
            var widget = this;

            stringControl.rebind("click", function (e) {
                var value = stringControl.find(".value");
                var nodePath = value.closest("tr").attr("node_path");

                var input = $('<input>');

                var valueContainer = $('<div class="value"></div>');

                input
                    .val(value.text())
                    .width($(this).width())
                    .bind("blur", function () {
                        request(widget.options.paths.updateStringValue, {
                            instance: widget.options.instance,
                            path:     nodePath,
                            value:    $(this).val()
                        }, function (value) {
                            stringControl.html(valueContainer).find(".value").html(value);
                            widget._stringValueBind(stringControl);
                        });
                    })
                    .bind("keyup", function (e) {
                        if (e.keyCode === 13) {
                            request(widget.options.paths.updateStringValue, {
                                instance: widget.options.instance,
                                path:     nodePath,
                                value:    $(this).val()
                            }, function (value) {
                                stringControl.html(valueContainer).find(".value").html(value);
                                widget._stringValueBind(stringControl);
                            });
                        }

                        if (e.keyCode === 27) {
                            stringControl.html(valueContainer).find(".value").html(value);
                            widget._stringValueBind(stringControl);
                        }

                        e.stopPropagation();
                    });

                $(this).html(input).unbind("click");

                input.focus();

                e.stopPropagation();
            });
        }
    });
})(__nodeNs__, __nodeId__);
