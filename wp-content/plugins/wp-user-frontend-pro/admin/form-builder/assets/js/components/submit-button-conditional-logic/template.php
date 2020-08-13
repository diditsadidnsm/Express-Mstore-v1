<div class="panel-field-opt panel-field-opt-conditional-logic notification-conditional-logic">

    <ul class="list-inline condition-status">
        <li>
            <label><input type="radio" value="yes" v-model="settings.submit_button_cond.condition_status" name="wpuf_settings[submit_button_cond][condition_status]"> <?php _e( 'Yes', 'wpuf-pro' ); ?></label>
        </li>

        <li>
            <label><input type="radio" value="no" v-model="settings.submit_button_cond.condition_status" name="wpuf_settings[submit_button_cond][condition_status]"> <?php _e( 'No', 'wpuf-pro' ); ?></label>
        </li>
    </ul>

    <div v-if="'yes' === settings.submit_button_cond.condition_status" class="condiotional-logic-container">
        <ul class="condiotional-logic-repeater">
            <li v-for="(condition, index) in settings.submit_button_cond.conditions" class="clearfix">
                <div class="cond-field">
                    <select v-model="condition.name" @change="on_change_cond_field(index, condition.name)" :name="`wpuf_settings[submit_button_cond][conditions][${index}][name]`">
                        <option value=""><?php _e( '- select -', 'wpuf-pro' ); ?></option>
                        <option
                            v-for="dep_field in dependencies"
                            :value="dep_field.name"
                        >{{ dep_field.label }}</option>
                    </select>
                </div>

                <div class="cond-operator">
                    <select v-model="condition.operator" :name="`wpuf_settings[submit_button_cond][conditions][${index}][operator]`">
                        <option value="="><?php _e( 'is', 'wpuf-pro' ); ?></option>
                        <option value="!="><?php _e( 'is not', 'wpuf-pro' ); ?></option>
                    </select>
                </div>

                <div class="cond-option">
                    <select v-model="condition.option" :name="`wpuf_settings[submit_button_cond][conditions][${index}][option]`">
                        <option value=""><?php _e( '- select -', 'wpuf-pro' ); ?></option>
                        <option
                            v-for="cond_option in get_cond_options(condition.name)"
                            :value="cond_option.opt_name"
                        >
                            {{ cond_option.opt_title }}
                        </option>
                    </select>
                </div>

                <div class="cond-action-btns">
                    <i class="fa fa-plus-circle" @click="add_condition"></i>
                    <i class="fa fa-minus-circle pull-right" @click="delete_condition(index)"></i>
                </div>
            </li>
        </ul>

        <p class="help">
            <?php
                printf(
                    __( 'Show submit button when %s of these rules are met', 'wpuf-pro' ),
                    '<select v-model="settings.submit_button_cond.cond_logic" name="wpuf_settings[submit_button_cond][cond_logic]"><option value="any">' . __( 'any', 'wpuf-pro' ) . '</option><option value="all">' . __( 'all', 'wpuf-pro' ) . '</option></select>'
                );
            ?>
        </p>
    </div>
    <div v-else>
        <input type="hidden" name="wpuf_settings[submit_button_cond][conditions][0][name]" value="">
        <input type="hidden" name="wpuf_settings[submit_button_cond][conditions][0][operator]" value="=">
        <input type="hidden" name="wpuf_settings[submit_button_cond][conditions][0][option]" value="">
        <input type="hidden" name="wpuf_settings[submit_button_cond][cond_logic]" valye="any">
    </div>
</div>