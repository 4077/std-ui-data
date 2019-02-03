// head {
var __nodeId__ = "std_ui_data__main_tree_nodeContextmenu";
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

            this._bindImport();
            this._bindExportButtons();
            this._bindActions();
            this._bindIndexForm();
            this._bindTypeForm();
            this._bindFnApplyForm();

            widget.element.rebind("click", function (e) {
                e.stopPropagation();
            });
        },

        _bindExportButtons: function () {
            var widget = this;

            $.each(widget.options.exportData, function (key, value) {
                $("input[export='" + key + "']", widget.element).val(value);

                $(".button[export='" + key + "']", widget.element).bind("click", function () {
                    $("input[export='" + key + "']", widget.element).select();

                    document.execCommand("copy");

                    $(".button[export]", widget.element).removeClass("exported");
                    $(this).addClass("exported");
                });
            });
        },

        _bindImport: function () {
            var widget = this;

            var $importInput = $(".import_form input", widget.element);

            $importInput.bind("focus", function () {
                $(this).select();
            }).bind("paste", function () {
                ewma.delay(function () {
                    request(widget.options.paths.setBuffer, {
                        value: $importInput.val()
                    })
                });
            });
        },

        importStatus: function (data) {
            var widget = this;

            p(data);

            var $importInput = $(".import_form input", widget.element);

            if (data.updated) {
                $importInput.addClass("updated");
            } else {
                $importInput.removeClass("updated");
            }
        },

        _bindActions: function () {
            var widget = this;

            $(".button[action]", widget.element).rebind("click", function () {
                request(widget.options.paths.performAction, {
                    node_path: widget.options.nodePath,
                    action:    $(this).attr("action")
                });
            });
        },

        _bindIndexForm: function () {
            var widget = this;

            var form = $(".index_form", widget.element);
            var input = $("input", form);
            var saveButton = $(".save_button", form);

            var wrongValue = false;

            input.focus().bind("keyup", function (e) {
                if (e.keyCode == 13) {
                    if (!wrongValue) {
                        widget._submitIndexForm();
                    }
                } else {
                    var value = input.val();

                    if (value == widget.options.index) {
                        saveButton.hide();
                    } else {
                        saveButton.show();
                    }

                    if (value.length == 0 || in_array(value, widget.options.usedIndexes)) {
                        wrongValue = true;
                        form.addClass("wrong_input_value");
                    } else {
                        wrongValue = false;
                        form.removeClass("wrong_input_value");
                    }
                }
            });

            saveButton.bind("click", function () {
                widget._submitIndexForm();
            });
        },

        _submitIndexForm: function () {
            var widget = this;

            request(widget.options.paths.updateIndex, {
                node_path: widget.options.nodePath,
                value:     $(".index_form input", widget.element).val()
            });
        },

        _bindTypeForm: function () {
            var widget = this;

            var form = $(".type_form", widget.element);
            var saveButton = $(".save_button", form);

            var selectedType;

            $(".button." + widget.options.valueType, form).addClass("current");

            $(".button", form).bind("click", function () {
                $(".button", form).removeClass("selected");

                if (!$(this).hasClass("current")) {
                    $(this).addClass("selected");
                    saveButton.show();

                    selectedType = $(this).attr("type");
                } else {
                    saveButton.hide();

                    selectedType = false;
                }
            });

            saveButton.bind("click", function () {
                request(widget.options.paths.updateType, {
                    node_path: widget.options.nodePath,
                    type:      selectedType
                });
            });
        },

        _bindFnApplyForm: function () {
            var widget = this;

            var form = $(".fn_apply_form", widget.element);
            var fnSelector = $("select", form);

            var applyButton = $(".apply_button", form);

            applyButton.bind("click", function () {
                request(widget.options.paths.applyFn, {
                    node_path: widget.options.nodePath,
                    fn:        $("option:selected", fnSelector).val()
                });
            });

            var applyToLevelButton = $(".apply_to_level_button", form);

            applyToLevelButton.bind("click", function () {
                request(widget.options.paths.applyFn, {
                    node_path:      widget.options.nodePath,
                    fn:             $("option:selected", fnSelector).val(),
                    apply_to_level: true
                });
            });
        }
    });
})(__nodeNs__, __nodeId__);
