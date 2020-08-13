Vue.component('submit-button-conditional-logics', {
    template: '#tmpl-wpuf-submit-button-conditional-logic',

    data: function() {
        return {
            settings: this.$store.state.settings
        };
    },

    computed: {

        wpuf_cond_supported_fields: function () {
            return wpuf_form_builder.wpuf_cond_supported_fields;
        },

        dependencies: function () {
            var self = this;

            return this.$store.state.form_fields.filter(function (form_field) {
                return (_.indexOf(self.wpuf_cond_supported_fields, form_field.template) >= 0) && form_field.name && form_field.label;
            });
        }
    },

    methods: {
        get_cond_options: function (field_name) {
            var options = [];

            if (_.indexOf(this.wpuf_cond_supported_fields, field_name) < 0) {
                var dep = this.dependencies.filter(function (field) {
                    return field.name === field_name;
                });

                if (dep.length && dep[0].options) {
                    _.each(dep[0].options, function (option_title, option_name) {
                        options.push({opt_name: option_name, opt_title: option_title});
                    });
                }
            }

            return options;
        },

        on_change_cond_field: function (index) {
            this.$store.state.settings.submit_button_cond.conditions[index].option = '';
        },

        add_condition: function () {
            this.$store.state.settings.submit_button_cond.conditions.push({
                name: '',
                operator: '=',
                option: ''
            });
        },

        delete_condition: function (index) {
            if (this.$store.state.settings.submit_button_cond.conditions.length === 1) {
                this.warn({
                    text: this.i18n.last_choice_warn_msg,
                    showCancelButton: false,
                    confirmButtonColor: "#46b450",
                });

                return;
            }

            this.$store.state.settings.submit_button_cond.conditions.splice(index, 1);
        }
    },
});