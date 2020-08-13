<?php

/* Report Template */

WPUF_Report::require_lib();

/* User Report */

function wpuf_user_reg_report_chart() {
    global $wpdb;

    $color_arr = array( "#EC5657", "#1BCDD1", "#8FAABB", "#B08BEB", "#3EA0DD", "#F5A52A", "#23BFAA", "#FAA586", "#EB8CC6", "#36A2EB", "#FF6384", "#FFCE56", "#4BC0C0", "#4661EE" );

    $options = array('responsive' => true); $reg_colors = array(); $reg_attributes = array(); $reg_datasets = array();
    $curr_reg_data = array(); $reg_labels = array(); $last_reg_data = array(); $c_total_user = 0; $l_total_user = 0; $percent_change = 0;
    $select1 = ''; $select2 = ''; $select3 = ''; $select4 = ''; $select5 = ''; $select6 = ''; $select7 = '';

    if ( isset( $_GET['wpuf-user-dropdown'] ) ) {
        $filter_time = $_GET['wpuf-user-dropdown'];

        switch ( $filter_time ) {
            case 'wpuf-reg-this-month':
                $start_date = date( 'Y-m-d', strtotime( 'first day of this month' ) );
                $end_date   = date( 'Y-m-d', strtotime( 'last day of this month' ) );
                $last_start_date = date( 'Y-m-d', strtotime( 'first day of last month' ) );
                $last_end_date   = date( 'Y-m-d', strtotime( 'last day of last month' ) );
                $query_selector = 'date';
                $select1 = 'selected';
                break;

            case 'wpuf-reg-last-month':
                $start_date = date( 'Y-m-d', strtotime( 'first day of last month' ) );
                $end_date   = date( 'Y-m-d', strtotime( 'last day of last month' ) );
                $last_start_date = date( 'Y-m-d', strtotime('-1 month', strtotime( $start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime( '+1 month', strtotime( $last_start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $last_end_date ) ) );
                $query_selector = 'date';
                $select2 = 'selected';
                break;

            case 'wpuf-reg-this-quarter':
                $current_quarter = ceil(date('n') / 3);
                $start_date = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3) - 2) . '-1'));
                $end_date   = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3)) . '-1'));
                $query_selector = 'monthname';
                $current_quarter = ceil(date('n') / 3);
                $first_date = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3) - 2) . '-1'));
                $last_date  = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3)) . '-1'));
                $last_start_date = date( 'Y-m-d', strtotime( '-4 months', strtotime( $first_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('+4 months', strtotime( $start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $end_date ) ) );
                $select3 = 'selected';
                break;

            case 'wpuf-reg-last-quarter':
                $current_quarter = ceil(date('n') / 3);
                $first_date = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3) - 2) . '-1'));
                $last_date  = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3)) . '-1'));
                $start_date = date( 'Y-m-d', strtotime( '-4 months', strtotime( $first_date ) ) );
                $end_date   = date( 'Y-m-d', strtotime('+4 months', strtotime( $start_date ) ) );
                $end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $end_date ) ) );
                $last_start_date = date( 'Y-m-d', strtotime( '-4 months', strtotime( $start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('+4 months', strtotime( $last_start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $last_end_date ) ) );
                $query_selector  = 'monthname';
                $select4 = 'selected';
                break;

            case 'wpuf-reg-last-6-month':
                $start_date = date( 'Y-m-d', strtotime( '-6 months', strtotime('first day of last month')) );
                $end_date   = date( 'Y-m-d', strtotime( 'last day of last month' ) );
                $last_start_date = date( 'Y-m-d', strtotime( '-6 months', strtotime( $start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('-6 months', strtotime( $end_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $last_end_date ) ) );
                $query_selector  = 'monthname';
                $select5 = 'selected';
                break;

            case 'wpuf-reg-this-year':
                $start_date = date( 'Y-m-d', strtotime( 'first day of January' ) );
                $end_date   = date( 'Y-m-d', strtotime( 'last day of this month' ) );
                $last_start_date = date( 'Y-m-d', strtotime( 'last year January 1st' ) );
                $last_end_date   = date( 'Y-m-d', strtotime( 'last year December 31st' ) );
                $query_selector  = 'monthname';
                $select6 = 'selected';
                break;

            case 'wpuf-reg-last-year':
                $start_date = date( 'Y-m-d', strtotime( 'last year January 1st' ) );
                $end_date   = date( 'Y-m-d', strtotime( 'last year December 31st' ) );
                $last_start_date = date( 'Y-m-d', strtotime( '-1 year', strtotime( $start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('-1 year', strtotime( $end_date ) ) );
                $query_selector  = 'monthname';
                $select7 = 'selected';
                break;

            case 'wpuf-reg-custom-time':
                $start_date = $_GET['user_start_date'];
                $end_date   = $_GET['user_end_date'];
                $query_selector = 'date';
                break;

            default:
                break;
        }
    } else {
        $start_date = date( 'Y-m-d', strtotime( 'first day of this month' ) );
        $end_date = date( 'Y-m-d', strtotime( 'last day of this month' ) );
        $last_start_date = date( 'Y-m-d', strtotime( 'first day of last month' ) );
        $last_end_date   = date( 'Y-m-d', strtotime( 'last day of last month' ) );
        $query_selector  = 'date';
    }

    $curr_sql   = "SELECT count(*) as total_users , " . "{$query_selector}" ."(user_registered) as reg_label
        FROM " . $wpdb->prefix . "users WHERE DATE_FORMAT(user_registered, '%Y-%m-%d') BETWEEN " . "'{$start_date}'" ." AND " . "'{$end_date}'" . " group by reg_label order by user_registered";

    $curr_results  = $wpdb->get_results( $curr_sql );

    foreach ( $curr_results as $curr_result ) {
        $c_total_user += $curr_result->total_users;
        $curr_reg_data[] = $curr_result->total_users;
        if ( isset( $_GET['wpuf-user-dropdown'] ) && 'wpuf-reg-last-year' == $_GET['wpuf-user-dropdown'] ) {
            $reg_labels[] = date( 'M', strtotime( $curr_result->reg_label ) ) . '-' . date("Y",strtotime("-1 year"));
        } elseif ( $query_selector == 'monthname' ) {
            $reg_labels[] = date( 'M', strtotime( $curr_result->reg_label ) );
        } else {
            $reg_labels[] = date( 'j-M', strtotime( $curr_result->reg_label ) );
        }
    }

    if ( isset( $last_start_date ) && isset( $last_end_date ) ) {
        $last_sql   = "SELECT count(*) as total_users , " . "{$query_selector}" ."(user_registered) as reg_label
        FROM " . $wpdb->prefix . "users WHERE DATE_FORMAT(user_registered, '%Y-%m-%d') BETWEEN " . "'{$last_start_date}'" ." AND " . "'{$last_end_date}'" . " group by reg_label order by user_registered";

        $last_results  = $wpdb->get_results( $last_sql );

        foreach ( $last_results as $last_result ) {
            $l_total_user += $last_result->total_users;
            $last_reg_data[] = $last_result->total_users;
        }

        for ( $i = 0; $i < count( $reg_labels ) ; $i++ ) {
            if ( empty( $last_reg_data[$i] ) ) {
                $last_reg_data[$i] = 0;
            }
        }
    }

    $users_by_role = count_users(); $user_roles = array(); $user_counts = array();
    unset($users_by_role['avail_roles']['none']);

    foreach ( $users_by_role['avail_roles'] as $key => $value ) {
        $user_roles[] = $key;
        $user_counts[]= $value;
    }

    $colors = array();
    for ($i = 0, $j = 0; $i < count( $user_roles ) ; $i++, $j++) {
        if ( $j == 14 ) {
            $j = 0;
        }
        $colors[] = $color_arr[$j];
    }

    $reg_colors[0]  = array( 'backgroundColor' => 'transparent', 'borderColor' => '#3498db');
    $reg_colors[1]  = array( 'backgroundColor' => 'transparent', 'borderColor' => '#1abc9c');
    $reg_colors[2]  = array( 'backgroundColor' => $colors, 'borderColor' => 'transparent' );

    $reg_attributes[0] = array('id' => 'wpuf_reg_chart', 'width' => 90, 'height' => 50, 'style' => 'display:inline;');
    $reg_datasets[0]   = array('data' => $curr_reg_data, 'label' => "Users in this Period") + $reg_colors[0];

    $reg_attributes[1] = array('id' => 'wpuf_reg_pie', 'width' => 100, 'height' => 100, 'style' => 'display:inline;');
    $reg_datasets[2]   = array('data' => $user_counts, 'label' => "User By Roles") + $reg_colors[2];

    $reg_line   =  new ChartJS( 'line', $reg_labels, $options, $reg_attributes[0]);
    $reg_line->addDataset( $reg_datasets[0] );

    if ( !empty( $last_reg_data ) ) {
        $reg_datasets[1] = array('data' => $last_reg_data, 'label' => "Users in last Period") + $reg_colors[1];
        $reg_line->addDataset( $reg_datasets[1] );
    }

    $user_pie = new ChartJS( 'pie', $user_roles, $options, $reg_attributes[1]);
    $user_pie->addDataset( $reg_datasets[2] );

    ?>

    <div class="wpuf-reg-report-nav" style="width: 100%; margin-top: 15px;">
        <!-- <label style="display: inline; margin-right: 50px; ">User Registration</label> -->
        <form method="get" action="<?php echo admin_url( 'admin.php'); ?>" class="form-inline report-filter" style="float: right; margin-right: 20px; display: inline;">
            <span class="form-group">
                <input type="hidden" name="page" value="wpuf_reports" />
                <input type="hidden" name="tab" value="reg_reports" />
                <label><?php _e( 'User Registration Period:', 'wpuf-pro' ); ?></label>
                <select id="wpuf-user-dropdown" name="wpuf-user-dropdown" style="display: inline;">
                    <option value="wpuf-reg-this-month" <?php echo $select1; ?> >This Month</option>
                    <option value="wpuf-reg-last-month" <?php echo $select2; ?> >Last Month</option>
                    <option value="wpuf-reg-this-quarter" <?php echo $select3; ?> >This Quarter</option>
                    <option value="wpuf-reg-last-quarter" <?php echo $select4; ?> >Last Quarter</option>
                    <option value="wpuf-reg-last-6-month" <?php echo $select5; ?> >Last 6 Month</option>
                    <option value="wpuf-reg-this-year" <?php echo $select6; ?> >This Year</option>
                    <option value="wpuf-reg-last-year" <?php echo $select7; ?> >Last Year</option>
                    <option value="wpuf-reg-custom-time" >Custom Range</option>
                </select>
            </span>
            <span id="wpuf-reg-custom-time" class="wpuf-date-range">
                <span class="form-group">
                    <label for="from"><?php _e( 'From:', 'wpuf-pro' ); ?></label>
                    <input type="text" name="user_start_date" id="from" class="datepicker" readonly="readonly" value="" />
                </span>
                <span class="form-group">
                    <label for="to"><?php _e( 'To:', 'wpuf-pro' ); ?></label>
                    <input type="text" name="user_end_date" id="to" class="datepicker" readonly="readonly" value="" />
                </span>
            </span>
            <button name="wpuf_report_filter_user" class="button button-secondary" value="submit"><?php _e( 'Show', 'wpuf-pro' ); ?></button>
        </form>
    </div>

    <div class="wpuf-report-container">
        <div class="wpuf-chart-legend">
            <ul style="width: 24%; display:inline;">
                <li>
                    Registered Users in this Period
                    <?php
                    if ( $l_total_user === 0 || $c_total_user === 0 ) {
                        $percent_change = ( $c_total_user - $l_total_user ) * 100;
                    } else {
                        $percent_change = ( ( $c_total_user - $l_total_user ) / $l_total_user ) * 100;
                    }

                    if ( $c_total_user > $l_total_user ) { ?>
                        <div class="wpuf-chart-sidebar">
                            <div class="wpuf-chart-flex-item">
                                <strong> <?php echo $c_total_user; ?> </strong>
                            </div>
                            <div class="wpuf-chart-flex-item2">
                                <span class="percentage-change increase-class dashicons dashicons-arrow-up"></span>
                                <span class="percentage-change"> <?php echo '+' . number_format((float)$percent_change, 2, '.', '') . '%'; ?> </span>
                            </div>
                        </div>
                    <?php }
                    elseif ( $c_total_user < $l_total_user ) { ?>
                        <div class="wpuf-chart-sidebar">
                            <div class="wpuf-chart-flex-item">
                                <strong> <?php echo $c_total_user; ?> </strong>
                            </div>
                            <div class="wpuf-chart-flex-item2">
                                <span class="percentage-change decrease-class dashicons dashicons-arrow-down"></span>
                                <span class="percentage-change"> <?php echo number_format((float)$percent_change, 2, '.', '') . '%'; ?> </span>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="wpuf-chart-sidebar">
                            <div class="wpuf-chart-flex-item">
                                <strong> <?php echo $c_total_user; ?> </strong>
                            </div>
                            <div class="wpuf-chart-flex-item2">
                                <span class="percentage-change nochange-class dashicons dashicons-leftright"></span>
                                <span class="percentage-change"> <?php echo number_format((float)$percent_change, 2, '.', '') . '%'; ?> </span>
                            </div>
                        </div>
                    <?php } ?>
                </li>
                <li style="width: 90%;">
                    Users by Roles
                    <?php
                    if ( !empty( $user_pie ) ) {
                        echo $user_pie;
                    }
                    ?>
                </li>
            </ul>
        </div>
        <div id="wpuf-reg-line" class="wpuf-reg-chart-container" style="width: 72%; float: right; display:inline; background-color: #fff; margin: 10px 5px 0 0;">
            <?php if ( !empty( $reg_line ) ) {
                echo $reg_line;
            }
            ?>
        </div>
    </div>
<?php
}

/* Post Report */

function wpuf_post_report_chart() {
    global $wpdb;

    $color_arr = array( "#EC5657", "#1BCDD1", "#8FAABB", "#B08BEB", "#3EA0DD", "#F5A52A", "#23BFAA", "#FAA586", "#EB8CC6", "#36A2EB", "#FF6384", "#FFCE56", "#4BC0C0", "#4661EE" );

    $options = array( 'responsive' => true ); $post_colors = array(); $post_attributes = array(); $post_datasets = array();
    $curr_post_data = array(); $post_labels = array(); $last_post_data = array(); $c_total_post = 0; $l_total_post = 0; $percent_change = 0;
    $select1 = ''; $select2 = ''; $select3 = ''; $select4 = ''; $select5 = ''; $select6 = ''; $select7 = '';

    if ( isset( $_GET['wpuf-post-dropdown'] ) ) {
        $filter_time = $_GET['wpuf-post-dropdown'];

        switch ( $filter_time ) {
            case 'wpuf-post-this-month':
                $start_date = date( 'Y-m-d', strtotime( 'first day of this month' ) );
                $end_date   = date( 'Y-m-d', strtotime( 'last day of this month' ) );
                $last_start_date = date( 'Y-m-d', strtotime( 'first day of last month' ) );
                $last_end_date   = date( 'Y-m-d', strtotime( 'last day of last month' ) );
                $query_selector = 'date';
                $select1 = 'selected';
                break;

            case 'wpuf-post-last-month':
                $start_date = date( 'Y-m-d', strtotime( 'first day of last month' ) );
                $end_date   = date( 'Y-m-d', strtotime( 'last day of last month' ) );
                $last_start_date = date( 'Y-m-d', strtotime('-1 month', strtotime( $start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime( '+1 month', strtotime( $last_start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $last_end_date ) ) );
                $query_selector = 'date';
                $select2 = 'selected';
                break;

            case 'wpuf-post-this-quarter':
                $current_quarter = ceil(date('n') / 3);
                $start_date = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3) - 2) . '-1'));
                $end_date   = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3)) . '-1'));
                $query_selector = 'monthname';
                $current_quarter = ceil(date('n') / 3);
                $first_date = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3) - 2) . '-1'));
                $last_date  = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3)) . '-1'));
                $last_start_date = date( 'Y-m-d', strtotime( '-4 months', strtotime( $first_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('+4 months', strtotime( $start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $end_date ) ) );
                $select3 = 'selected';
                break;

            case 'wpuf-post-last-quarter':
                $current_quarter = ceil(date('n') / 3);
                $first_date = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3) - 2) . '-1'));
                $last_date  = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3)) . '-1'));
                $start_date = date( 'Y-m-d', strtotime( '-4 months', strtotime( $first_date ) ) );
                $end_date   = date( 'Y-m-d', strtotime('+4 months', strtotime( $start_date ) ) );
                $end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $end_date ) ) );
                $last_start_date = date( 'Y-m-d', strtotime( '-4 months', strtotime( $start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('+4 months', strtotime( $last_start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $last_end_date ) ) );
                $query_selector  = 'monthname';
                $select4 = 'selected';
                break;

            case 'wpuf-post-last-6-month':
                $start_date = date( 'Y-m-d', strtotime( '-6 months', strtotime('first day of last month')) );
                $end_date   = date( 'Y-m-d', strtotime( 'last day of last month' ) );
                $last_start_date = date( 'Y-m-d', strtotime( '-6 months', strtotime( $start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('-6 months', strtotime( $end_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $last_end_date ) ) );
                $query_selector  = 'monthname';
                $select5 = 'selected';
                break;

            case 'wpuf-post-this-year':
                $start_date = date( 'Y-m-d', strtotime( 'first day of January' ) );
                $end_date   = date( 'Y-m-d', strtotime( 'last day of this month' ) );
                $query_selector = 'monthname';
                $last_start_date = date( 'Y-m-d', strtotime( 'last year January 1st' ) );
                $last_end_date   = date( 'Y-m-d', strtotime( 'last year December 31st' ) );
                $select6 = 'selected';
                break;

            case 'wpuf-post-last-year':
                $start_date = date( 'Y-m-d', strtotime( 'last year January 1st' ) );
                $end_date   = date( 'Y-m-d', strtotime( 'last year December 31st' ) );
                $last_start_date = date( 'Y-m-d', strtotime( '-1 year', strtotime( $start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('-1 year', strtotime( $end_date ) ) );
                $query_selector = 'monthname';
                $select7 = 'selected';
                break;

            case 'wpuf-post-custom-time':
                $start_date = $_GET['post_start_date'];
                $end_date = $_GET['post_end_date'];
                $query_selector = 'date';
                break;

            default:
                break;
        }
    } else {
        $start_date = date( 'Y-m-d', strtotime( 'first day of this month' ) );
        $end_date = date( 'Y-m-d', strtotime( 'last day of this month' ) );
        $last_start_date = date( 'Y-m-d', strtotime( 'first day of last month' ) );
        $last_end_date   = date( 'Y-m-d', strtotime( 'last day of last month' ) );
        $query_selector  = 'date';
    }

    $curr_sql = "SELECT count(*) as post_count, " . "{$query_selector}" . "(DATE_FORMAT(post_date, '%Y-%m-%d')) as created
        FROM {$wpdb->posts} WHERE DATE_FORMAT(post_date, '%Y-%m-%d') BETWEEN " . "'{$start_date}'" ." AND " . "'{$end_date}'" . " AND post_status = 'publish'
        GROUP BY " . "{$query_selector}" ."(DATE_FORMAT(post_date, '%Y-%m-%d')) ORDER BY post_date" ;

    $current_posts = $wpdb->get_results( $curr_sql );

    foreach ( $current_posts as $current_post ) {
        $c_total_post += $current_post->post_count;
        $curr_post_data[]   = $current_post->post_count;

        if ( isset( $_GET['wpuf-post-dropdown'] ) && 'wpuf-post-last-year' == $_GET['wpuf-post-dropdown'] ) {
            $post_labels[] = date( 'M', strtotime( $current_post->created ) ) . '-' . date("Y",strtotime("-1 year"));
        } elseif ( $query_selector == 'monthname' ) {
            $post_labels[] = date( 'M', strtotime( $current_post->created ) );
        } else {
            $post_labels[] = date( 'j-M', strtotime( $current_post->created ) );
        }
    }

    if ( isset( $last_start_date ) && isset( $last_end_date ) ) {
        $last_sql = "SELECT count(*) as post_count, " . "{$query_selector}" . "(DATE_FORMAT(post_date, '%Y-%m-%d')) as created
            FROM {$wpdb->posts} WHERE DATE_FORMAT(post_date, '%Y-%m-%d') BETWEEN " . "'{$last_start_date}'" ." AND " . "'{$last_end_date}'" . " AND post_status = 'publish'
            GROUP BY " . "{$query_selector}" ."(DATE_FORMAT(post_date, '%Y-%m-%d')) ORDER BY post_date";

        $last_posts = $wpdb->get_results( $last_sql );

        foreach ( $last_posts as $last_post ) {
            $l_total_post += $last_post->post_count;
            $last_post_data[] = $last_post->post_count;
        }

        for ( $i = 0; $i < count( $post_labels ) ; $i++ ) {
            if ( empty( $last_post_data[$i] ) ) {
                $last_post_data[$i] = 0;
            }
        }
    }

    $post_types = array();
    $args       = array(
        '_builtin' => false
    );

    $output   = 'names';
    $operator = 'and';
    $report_post_types = get_post_types( $args, $output, $operator );

    unset( $report_post_types['attachment'] );
    unset( $report_post_types['revision'] );
    unset( $report_post_types['nav_menu_item'] );
    unset( $report_post_types['wpuf_forms'] );
    unset( $report_post_types['wpuf_profile'] );
    unset( $report_post_types['wpuf_input'] );
    unset( $report_post_types['wpuf_subscription'] );
    unset( $report_post_types['custom_css'] );
    unset( $report_post_types['customize_changeset'] );
    unset( $report_post_types['wpuf_coupon'] );
    unset( $report_post_types['oembed_cache'] );
    unset( $report_post_types['product'] );
    unset( $report_post_types['product_variation'] );
    unset( $report_post_types['shop_order'] );
    unset( $report_post_types['shop_coupon'] );
    unset( $report_post_types['shop_order_refund'] );
    unset( $report_post_types['shop_webhook'] );

    foreach ( $report_post_types as $post_type ) {
        $post_types[] = $post_type;
    }

    $post_types[] = 'post';
    $post_types[] = 'page';

    $post_type_keys = array(); $post_type_count = array(); $posts_per_type = array(); $total_posts    = 0;

    foreach ( $post_types as $post_type ) {
        $type_object                           = get_post_type_object( $post_type );
        $count                                 = wp_count_posts( $post_type )->publish;
        $posts_per_type[$type_object->label]   = $count;
        $total_posts                           += $count;
    }

    foreach ( $posts_per_type as $key => $value) {
        $post_type_keys[]  = $key;
        $post_type_count[] = $value;
    }

    $colors = array();
    for ($i = 0, $j = 0; $i < count( $post_type_count ) ; $i++, $j++) {
        if ( $j == 14 ) {
            $j = 0;
        }
        $colors[] = $color_arr[$j];
    }

    $post_colors[0]  = array( 'backgroundColor' => 'transparent', 'borderColor' => '#3498db');
    $post_colors[1]  = array( 'backgroundColor' => $colors, 'borderColor' => 'transparent' );
    $post_colors[2]  = array( 'backgroundColor' => 'transparent', 'borderColor' => '#1abc9c');

    $post_attributes[0] = array( 'id' => 'wpuf_posts_chart', 'width' => 90, 'height' => 50, 'style' => 'display:inline;' );
    $post_datasets[0]   = array( 'data' => $curr_post_data, 'label' => "Posts in this Period" ) + $post_colors[0];

    $post_attributes[1] = array('id' => 'wpuf_post_pie', 'width' => 100, 'height' => 100, 'style' => 'display:inline;');
    $post_datasets[1]   = array('data' => $post_type_count, 'label' => "Posts by Post Types") + $post_colors[1];

    $post_line = new ChartJS( 'line', $post_labels, $options, $post_attributes[0] );
    $post_line->addDataset( $post_datasets[0] );

    if ( !empty( $last_post_data ) ) {
        $post_datasets[2]   = array( 'data' => $last_post_data, 'label' => "Posts in last Period" ) + $post_colors[2];
        $post_line->addDataset( $post_datasets[2] );
    }

    $post_pie = new ChartJS( 'pie', $post_type_keys, $options, $post_attributes[1]);
    $post_pie->addDataset( $post_datasets[1] );

    $posts_per_author = array();
    foreach ( get_users() as $user ) {
        $user_data = array(
            'ID'   => $user->ID,
            'name' => $user->display_name
        );
        $total     = 0;
        foreach ( $post_types as $post_type ) {
            $count                   = count_user_posts( $user->ID, $post_type, true );
            $user_data[ $post_type ] = $count;
            $total                   += $count;
        }
        $user_data['total'] = $total;
        array_push( $posts_per_author, $user_data );
    }

    ?>

    <div class="wpuf-post-report-nav" style="width: 100%; margin-top: 15px;">
        <!-- <label style="display: inline; float: right;">Post Report</label> -->
        <form method="get" action="<?php echo admin_url( 'admin.php'); ?>" class="form-inline report-filter" style="float: right; margin-right: 20px; display: inline;">
            <span class="form-group">
                <input type="hidden" name="page" value="wpuf_reports" />
                <input type="hidden" name="tab" value="post_reports" />
                <label><?php _e( 'Post Report Period:', 'wpuf-pro' ); ?></label>
                <select id="wpuf-post-dropdown" name="wpuf-post-dropdown" style="display: inline;">
                    <option value="wpuf-post-this-month" <?php echo $select1; ?> >This Month</option>
                    <option value="wpuf-post-last-month" <?php echo $select2; ?> >Last Month</option>
                    <option value="wpuf-post-this-quarter" <?php echo $select3; ?> >This Quarter</option>
                    <option value="wpuf-post-last-quarter" <?php echo $select4; ?> >Last Quarter</option>
                    <option value="wpuf-post-last-6-month" <?php echo $select5; ?> >Last 6 Month</option>
                    <option value="wpuf-post-this-year" <?php echo $select6; ?> >This Year</option>
                    <option value="wpuf-post-last-year" <?php echo $select7; ?> >Last Year</option>
                    <option value="wpuf-post-custom-time" >Custom Range</option>
                </select>
            </span>
            <span id="wpuf-post-custom-time" class="wpuf-date-range" style="display:inline;">
                <span class="form-group">
                    <label for="from"><?php _e( 'From:', 'wpuf-pro' ); ?></label>
                    <input type="text" name="post_start_date" id="from" class="datepicker" readonly="readonly" value=""/>
                </span>
                <span class="form-group">
                    <label for="to"><?php _e( 'To:', 'wpuf-pro' ); ?></label>
                    <input type="text" name="post_end_date" id="to" class="datepicker" readonly="readonly" value=""/>
                </span>
            </span>
            <button type="submit" name="wpuf_report_filter_post" class="button button-secondary" value="submit"><?php _e( 'Show', 'wpuf-pro' ); ?></button>
        </form>
    </div>

    <div class="wpuf-report-container">
        <div class="wpuf-chart-legend">
            <ul style="width: 24%; height: auto; display:inline;">
                <li>
                    Total Posts in this Period
                    <?php
                    if ( $l_total_post === 0 || $c_total_post === 0 ) {
                        $percent_change = ( $c_total_post - $l_total_post ) * 100;
                    } else {
                        $percent_change = ( ( $c_total_post - $l_total_post ) / $l_total_post ) * 100;
                    }

                    if ( $c_total_post > $l_total_post ) { ?>
                        <div class="wpuf-chart-sidebar">
                            <div class="wpuf-chart-flex-item">
                                <strong> <?php echo $c_total_post; ?> </strong>
                            </div>
                            <div class="wpuf-chart-flex-item2">
                                <span class="percentage-change increase-class dashicons dashicons-arrow-up"></span>
                                <span class="percentage-change"> <?php echo number_format((float)$percent_change, 2, '.', '') . '%'; ?> </span>
                            </div>
                        </div>
                    <?php }
                    elseif ( $c_total_post < $l_total_post ) { ?>
                        <div class="wpuf-chart-sidebar">
                            <div class="wpuf-chart-flex-item">
                                <strong> <?php echo $c_total_post; ?> </strong>
                            </div>
                            <div class="wpuf-chart-flex-item2">
                                <span class="percentage-change decrease-class dashicons dashicons-arrow-down"></span>
                                <span class="percentage-change"> <?php echo number_format((float)$percent_change, 2, '.', '') . '%'; ?> </span>
                            </div>
                        </div>
                   <?php } else { ?>
                        <div class="wpuf-chart-sidebar">
                            <div class="wpuf-chart-flex-item">
                                <strong> <?php echo $c_total_post; ?> </strong>
                            </div>
                            <div class="wpuf-chart-flex-item2">
                                <span class="percentage-change nochange-class dashicons dashicons-leftright"></span>
                                <span class="percentage-change"> <?php echo number_format((float)$percent_change, 2, '.', '') . '%'; ?> </span>
                            </div>
                        </div>
                    <?php } ?>
                </li>
                <li style="width: 90%;">
                    Posts Breakdown
                    <?php
                    if ( !empty( $post_pie ) ) {
                        echo $post_pie;
                    }
                    ?>
                </li>
            </ul>
        </div>
        <div id="wpuf-post-line" class="wpuf-post-chart-container" style="width: 72%; float: right; display:inline; background-color: #fff; margin: 10px 5px 0 0;">
            <?php
            if ( !empty( $post_line ) ) {
                echo $post_line;
            }
            ?>
        </div>
    </div>
    <div class="clear"></div>
    <div class="wpuf-posts-by-author">
        <h2 style="margin-left: 10px;"><?php _e( 'Post Statistics by Author', 'wpuf-pro' ); ?></h2>
        <table id="wpuf-post-author-by-types" class="wp-list-table widefat">
            <thead>
                <tr>
                    <th scope="col"><?php _e( 'Author', 'wpuf-pro' ); ?></th>
                    <?php
                    if ( !empty($post_types) ){
                        foreach( $post_types as $post_type ) {
                            $type_object = get_post_type_object( $post_type ); ?>
                        <th><?php echo $type_object->label; ?></th>
                        <?php }
                    } ?>
                    <th><?php _e( 'All Post Types', 'wpuf-pro' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ( !empty( $post_types ) && !empty( $posts_per_author ) ) {
                    foreach( $posts_per_author as $author ) {
                        ?>
                <tr>
                    <td><?php echo $author['name']; ?></td>
                    <?php foreach( $post_types as $post_type ) { ?>
                    <td><a href="<?php echo admin_url() . 'edit.php?post_type=' . $post_type . '&author=' . $author['ID'] ?>"><?php echo $author[$post_type]; ?></a></td>
                    <?php } ?>
                    <td><strong><?php echo $author['total']; ?></strong></td>
                    <?php } ?>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php
}

/* Subscription Report */

function wpuf_subscription_report_chart() {
    global $wpdb;

    $color_arr = array( "#EC5657", "#1BCDD1", "#8FAABB", "#B08BEB", "#3EA0DD", "#F5A52A", "#23BFAA", "#FAA586", "#EB8CC6", "#36A2EB", "#FF6384", "#FFCE56", "#4BC0C0", "#4661EE" );

    $options = array( 'responsive' => true ); $subs_colors = array(); $subs_attributes = array(); $subs_datasets = array();
    $curr_subs_data = array(); $subs_labels = array(); $last_subs_data = array(); $last_subs_label = array(); $total_sale    = 0; $last_sale  = 0; $percent_change  = 0;
    $subs_datasets = array(); $pack_count = 0; $sub_pie_label = array(); $select1 = ''; $select2 = ''; $select3 = ''; $select4 = ''; $select5 = ''; $select6 = ''; $select7 = '';

    if ( isset( $_GET['wpuf-subs-dropdown'] ) ) {
        $filter_time = $_GET['wpuf-subs-dropdown'];

        switch ( $filter_time ) {
            case 'wpuf-subs-this-month':
                $start_date = date( 'Y-m-d', strtotime( 'first day of this month' ) );
                $end_date   = date( 'Y-m-d', strtotime( 'last day of this month' ) );
                $last_start_date = date( 'Y-m-d', strtotime( 'first day of last month' ) );
                $last_end_date   = date( 'Y-m-d', strtotime( 'last day of last month' ) );
                $query_selector = 'date';
                $select1 = 'selected';
                break;

            case 'wpuf-subs-last-month':
                $start_date = date( 'Y-m-d', strtotime( 'first day of last month' ) );
                $end_date   = date( 'Y-m-d', strtotime( 'last day of last month' ) );
                $last_start_date = date( 'Y-m-d', strtotime('-1 month', strtotime( $start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime( '+1 month', strtotime( $last_start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $last_end_date ) ) );
                $query_selector = 'date';
                $select2 = 'selected';
                break;

            case 'wpuf-subs-this-quarter':
                $current_quarter = ceil(date('n') / 3);
                $start_date = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3) - 2) . '-1'));
                $end_date   = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3)) . '-1'));
                $query_selector = 'monthname';
                $current_quarter = ceil(date('n') / 3);
                $first_date = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3) - 2) . '-1'));
                $last_date  = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3)) . '-1'));
                $last_start_date = date( 'Y-m-d', strtotime( '-4 months', strtotime( $first_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('+4 months', strtotime( $start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $end_date ) ) );
                $select3 = 'selected';
                break;

            case 'wpuf-subs-last-quarter':
                $current_quarter = ceil(date('n') / 3);
                $first_date = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3) - 2) . '-1'));
                $last_date  = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3)) . '-1'));
                $start_date = date( 'Y-m-d', strtotime( '-4 months', strtotime( $first_date ) ) );
                $end_date   = date( 'Y-m-d', strtotime('+4 months', strtotime( $start_date ) ) );
                $end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $end_date ) ) );
                $last_start_date = date( 'Y-m-d', strtotime( '-4 months', strtotime( $start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('+4 months', strtotime( $last_start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $last_end_date ) ) );
                $query_selector  = 'monthname';
                $select4 = 'selected';
                break;

            case 'wpuf-subs-last-6-month':
                $start_date = date( 'Y-m-d', strtotime( '-6 months', strtotime('first day of last month')) );
                $end_date   = date( 'Y-m-d', strtotime( 'last day of last month' ) );
                $last_start_date = date( 'Y-m-d', strtotime( '-6 months', strtotime( $start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('-6 months', strtotime( $end_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $last_end_date ) ) );
                $query_selector  = 'monthname';
                $select5 = 'selected';
                break;

            case 'wpuf-subs-this-year':
                $start_date = date( 'Y-m-d', strtotime( 'first day of January' ) );
                $end_date   = date( 'Y-m-d', strtotime( 'last day of this month' ) );
                $query_selector = 'monthname';
                $last_start_date = date( 'Y-m-d', strtotime( 'last year January 1st' ) );
                $last_end_date   = date( 'Y-m-d', strtotime( 'last year December 31st' ) );
                $select6 = 'selected';
                break;

            case 'wpuf-subs-last-year':
                $start_date = date( 'Y-m-d', strtotime( 'last year January 1st' ) );
                $end_date   = date( 'Y-m-d', strtotime( 'last year December 31st' ) );
                $last_start_date = date( 'Y-m-d', strtotime( '-1 year', strtotime( $start_date ) ) );
                $last_end_date   = date( 'Y-m-d', strtotime('-1 year', strtotime( $end_date ) ) );
                $query_selector  = 'monthname';
                $select7 = 'selected';
                break;

            case 'wpuf-subs-custom-time':
                $start_date = $_GET['subs_start_date'];
                $end_date = $_GET['subs_end_date'];
                $query_selector = 'date';
                break;

            default:
                break;
        }
    } else {
        $start_date = date( 'Y-m-d', strtotime( 'first day of this month' ) );
        $end_date = date( 'Y-m-d', strtotime( 'last day of this month' ) );
        $last_start_date = date( 'Y-m-d', strtotime( 'first day of last month' ) );
        $last_end_date   = date( 'Y-m-d', strtotime( 'last day of last month' ) );
        $query_selector  = 'date';
    }

    $completed_sql = "SELECT count(*) as pack_count, SUM(`cost`) as total_sale, " . "{$query_selector}" . "(DATE_FORMAT(`created`, '%Y-%m-%d')) as buy_date
        FROM `" . $wpdb->prefix . "wpuf_transaction` WHERE DATE_FORMAT(`created`, '%Y-%m-%d') BETWEEN " . "'{$start_date}'" ." AND " . "'{$end_date}'" . " AND `status` = 'completed' AND pack_id > 0
        GROUP BY buy_date ORDER BY created";

    $pending_orders = wpuf_get_pending_transactions( array( 'count' => true ) );

    $completed_results = $wpdb->get_results( $completed_sql );

    foreach ( $completed_results as $completed_result ) {
        $pack_count += $completed_result->pack_count;
        $total_sale += $completed_result->total_sale;
        $curr_subs_data[] =  $completed_result->total_sale;

        if ( isset( $_GET['wpuf-subs-dropdown'] ) && 'wpuf-subs-last-year' == $_GET['wpuf-subs-dropdown'] ) {
            $subs_labels[] = date( 'M', strtotime( $completed_result->buy_date ) ) . '-' . date("Y",strtotime("-1 year"));
        } elseif ( $query_selector == 'monthname' ) {
            $subs_labels[] = date( 'M', strtotime( $completed_result->buy_date ) );
        } else {
            $subs_labels[] = date( 'j-M', strtotime( $completed_result->buy_date ) );
        }
    }

    if ( isset( $last_start_date ) && isset( $last_end_date ) ) {
        $last_completed_sql = "SELECT count(*) as pack_count, SUM(`cost`) as total_sale, DATE_FORMAT(`created`, '%Y-%m-%d') as buy_date
            FROM `" . $wpdb->prefix . "wpuf_transaction` WHERE DATE_FORMAT(`created`, '%Y-%m-%d') BETWEEN " . "'{$last_start_date}'" ." AND " . "'{$last_end_date}'" . " AND `status` = 'completed' AND pack_id > 0
            GROUP BY buy_date ORDER BY buy_date";
        $last_completed_results = $wpdb->get_results( $last_completed_sql );

        foreach ( $last_completed_results as $last_completed_result ) {
            $last_sale += $last_completed_result->total_sale;
            $last_subs_data[] =  $last_completed_result->total_sale;
        }

        for ( $i = 0; $i < count( $subs_labels ); $i++ ) {
            if ( empty( $last_subs_data[$i] ) ) {
                $last_subs_data[$i] = 0;
            }
        }
    }

    $colors = array();
    for ( $i = 0; $i< 2; $i++ ) {
        $colors[] = $color_arr[$i];
    }

    $subs_colors[0] = array( 'backgroundColor' => 'transparent', 'borderColor' => '#3498db' );
    $subs_colors[1] = array( 'backgroundColor' => 'transparent', 'borderColor' => '#1abc9c' );
    $subs_colors[2] = array( 'backgroundColor' => $colors, 'borderColor' => 'transparent' );

    $subs_attributes[0]  = array( 'id'    => 'wpuf_subs_chart', 'width'  => 90, 'height' => 50, 'style'  => 'display:inline;' );
    $subs_attributes[1]  = array( 'id'    => 'wpuf_subs_pie', 'width'  => 100, 'height' => 100, 'style'  => 'display:inline;' );

    $subs_datasets[0] = array( 'data'  => $curr_subs_data, 'label' => "Pack Sales in this Period" ) + $subs_colors[0];

    $subs_line = new ChartJS( 'line', $subs_labels, $options, $subs_attributes[0] );
    $subs_line->addDataset( $subs_datasets[0] );

    if ( !empty( $last_subs_data ) ) {
        $subs_datasets[1] = array( 'data'  => $last_subs_data, 'label' => "Pack Sales in last Period" ) + $subs_colors[1];
        $subs_line->addDataset( $subs_datasets[1] );
    }

    $sub_pie_data = array( $pack_count, $pending_orders );
    $sub_pie_label= array( 'Sold Packs', 'Pending Transactions' );

    $subs_datasets[2] = array( 'data'  => $sub_pie_data, 'label' => "Subscription Chart" ) + $subs_colors[2];

    $subs_pie = new ChartJS( 'pie', $sub_pie_label, $options, $subs_attributes[1] );
    $subs_pie->addDataset( $subs_datasets[2] );

    ?>

    <div class="wpuf-subs-report-nav" style="width: 100%; margin-top: 15px;">
        <!-- <label style="display: inline;">Subscription Report</label> -->
        <form method="get" action="<?php echo admin_url( 'admin.php'); ?>" class="form-inline report-filter" style="float: right; margin-right: 20px; display: inline;">
            <span class="form-group">
                <input type="hidden" name="page" value="wpuf_reports" />
                <input type="hidden" name="tab" value="subscription_reports" />
                <label><?php _e( 'Subscription Report Period:', 'wpuf-pro' ); ?></label>
                <select id="wpuf-subs-dropdown" name="wpuf-subs-dropdown" style="display: inline;">
                    <option value="wpuf-subs-this-month" <?php echo $select1; ?> ><?php _e( 'This Month', 'wpuf-pro' ); ?></option>
                    <option value="wpuf-subs-last-month" <?php echo $select2; ?> ><?php _e( 'Last Month', 'wpuf-pro' ); ?></option>
                    <option value="wpuf-subs-this-quarter" <?php echo $select3; ?> ><?php _e( 'This Quarter', 'wpuf-pro' ); ?></option>
                    <option value="wpuf-subs-last-quarter" <?php echo $select4; ?> ><?php _e( 'Last Quarter', 'wpuf-pro' ); ?></option>
                    <option value="wpuf-subs-last-6-month" <?php echo $select5; ?> ><?php _e( 'Last 6 Month', 'wpuf-pro' ); ?></option>
                    <option value="wpuf-subs-this-year" <?php echo $select6; ?> ><?php _e( 'This Year', 'wpuf-pro' ); ?></option>
                    <option value="wpuf-subs-last-year" <?php echo $select7; ?> ><?php _e( 'Last Year', 'wpuf-pro' ); ?></option>
                    <option value="wpuf-subs-custom-time" ><?php _e( 'Custom Range', 'wpuf-pro' ); ?></option>
                </select>
            </span>
            <span id="wpuf-subs-custom-time" class="wpuf-date-range">
                <span class="form-group">
                    <label for="from"><?php _e( 'From:', 'wpuf-pro' ); ?></label>
                    <input type="text" name="sub_start_date" id="from" class="datepicker" readonly="readonly" value=""/>
                </span>
                <span class="form-group">
                    <label for="to"><?php _e( 'To:', 'wpuf-pro' ); ?></label>
                    <input type="text" name="sub_end_date" id="to" class="datepicker" readonly="readonly" value=""/>
                </span>
            </span>
            <button type="submit" name="wpuf_report_filter_subs" class="button button-secondary" value="submit"><?php _e( 'Show', 'wpuf-pro' ); ?></button>
        </form>
    </div>

    <div class="wpuf-report-container">
        <div class="wpuf-chart-legend">
            <ul style="width: 24%; display:inline;">
                <li>
                    Total Subscription Pack Sales
                    <?php
                    $currency         = wpuf_get_option( 'currency', 'wpuf_payment', 'USD' );
                    if ( $last_sale === 0 || $total_sale === 0 ) {
                        $percent_change = ( $total_sale - $last_sale ) * 100;
                    } else {
                        $percent_change = ( ( $total_sale - $last_sale ) / $last_sale ) * 100;
                    }
                    if ( $total_sale > $last_sale ) { ?>
                        <div class="wpuf-chart-sidebar">
                            <div class="wpuf-chart-flex-item">
                                <strong> <?php echo wpuf_format_price( $total_sale ); ?> </strong>
                            </div>
                            <div class="wpuf-chart-flex-item2">
                                <span class="percentage-change increase-class dashicons dashicons-arrow-up"></span>
                                <span class="percentage-change"> <?php echo number_format((float)$percent_change, 2, '.', '') . '%'; ?> </span>
                            </div>
                        </div>
                    <?php } elseif ( $total_sale < $last_sale ) { ?>
                        <div class="wpuf-chart-sidebar">
                            <div class="wpuf-chart-flex-item">
                                <strong> <?php echo wpuf_format_price( $total_sale ); ?> </strong>
                            </div>
                            <div class="wpuf-chart-flex-item2">
                                <span class="percentage-change decrease-class dashicons dashicons-arrow-down"></span>
                                <span class="percentage-change"> <?php echo number_format((float)$percent_change, 2, '.', '') . '%'; ?> </span>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="wpuf-chart-sidebar">
                            <div class="wpuf-chart-flex-item">
                                <strong> <?php echo wpuf_format_price( $total_sale ); ?> </strong>
                            </div>
                            <div class="wpuf-chart-flex-item2">
                                <span class="percentage-change nochange-class dashicons dashicons-leftright"></span>
                                <span class="percentage-change"> <?php echo number_format((float)$percent_change, 2, '.', '') . '%'; ?> </span>
                            </div>
                        </div>
                    <?php } ?>
                </li>
                <li style="width: 90%;">
                    Subscription Breakdown
                    <?php
                    if ( !empty( $subs_pie ) ) {
                        echo $subs_pie;
                    }
                    ?>
                </li>
            </ul>
        </div>
        <div id="wpuf-subs-line" class="wpuf-subs-chart-container" style="width: 72%; float: right; display:inline; background-color: #fff; margin: 10px 5px 0 0;">
            <?php
            if ( ! empty( $subs_line ) ) {
                echo $subs_line;
            }
            ?>
        </div>
    </div>
<?php
}

/* Transaction Reports */

function wpuf_transaction_report() {
    global $wpdb;

    $options = array( 'responsive' => true ); $transaction_colors = array(); $transaction_attributes = array(); $transaction_datasets = array();
    $transaction_labels = array(); $sales_count = 0; $total_sale = 0; $curr_sales_data = array(); $percent_change  = 0; $last_sale = 0; $last_sales_data = array();
    $tax_count = 0; $total_tax = 0; $curr_tax_data = array(); $select1 = ''; $select2 = ''; $select3 = ''; $select4 = ''; $select5 = ''; $select6 = ''; $select7 = '';

    if ( isset( $_GET['wpuf-transaction-dropdown'] ) ) {
        $filter_time = $_GET['wpuf-transaction-dropdown'];

        switch ( $filter_time ) {
        case 'wpuf-transaction-this-month':
            $start_date = date( 'Y-m-d', strtotime( 'first day of this month' ) );
            $end_date   = date( 'Y-m-d', strtotime( 'last day of this month' ) );
            $last_start_date = date( 'Y-m-d', strtotime( 'first day of last month' ) );
            $last_end_date   = date( 'Y-m-d', strtotime( 'last day of last month' ) );
            $query_selector = 'date';
            $select1 = 'selected';
            break;

        case 'wpuf-transaction-last-month':
            $start_date = date( 'Y-m-d', strtotime( 'first day of last month' ) );
            $end_date   = date( 'Y-m-d', strtotime( 'last day of last month' ) );
            $last_start_date = date( 'Y-m-d', strtotime('-1 month', strtotime( $start_date ) ) );
            $last_end_date   = date( 'Y-m-d', strtotime( '+1 month', strtotime( $last_start_date ) ) );
            $last_end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $last_end_date ) ) );
            $query_selector = 'date';
            $select2 = 'selected';
            break;

        case 'wpuf-transaction-this-quarter':
            $current_quarter = ceil( date( 'n' ) / 3 );
            $start_date      = date( 'Y-m-d', strtotime( date( 'Y' ) . '-' . ( ( $current_quarter * 3 ) - 2 ) . '-1' ) );
            $end_date        = date( 'Y-m-d', strtotime( date( 'Y' ) . '-' . ( ( $current_quarter * 3 ) ) . '-1' ) );
            $query_selector  = 'monthname';
            $current_quarter = ceil( date( 'n' ) / 3 );
            $first_date      = date( 'Y-m-d', strtotime( date( 'Y' ) . '-' . ( ( $current_quarter * 3 ) - 2 ) . '-1' ) );
            $last_date       = date( 'Y-m-d', strtotime( date( 'Y' ) . '-' . ( ( $current_quarter * 3 ) ) . '-1' ) );
            $last_start_date = date( 'Y-m-d', strtotime( '-4 months', strtotime( $first_date ) ) );
            $last_end_date   = date( 'Y-m-d', strtotime('+4 months', strtotime( $start_date ) ) );
            $last_end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $end_date ) ) );
            $select3 = 'selected';
            break;

        case 'wpuf-transaction-last-quarter':
            $current_quarter = ceil(date('n') / 3);
            $first_date = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3) - 2) . '-1'));
            $last_date  = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3)) . '-1'));
            $start_date = date( 'Y-m-d', strtotime( '-4 months', strtotime( $first_date ) ) );
            $end_date   = date( 'Y-m-d', strtotime('+4 months', strtotime( $start_date ) ) );
            $end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $end_date ) ) );
            $last_start_date = date( 'Y-m-d', strtotime( '-4 months', strtotime( $start_date ) ) );
            $last_end_date   = date( 'Y-m-d', strtotime('+4 months', strtotime( $last_start_date ) ) );
            $last_end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $last_end_date ) ) );
            $query_selector  = 'monthname';
            $select4 = 'selected';
            break;

        case 'wpuf-transaction-last-6-month':
            $start_date = date( 'Y-m-d', strtotime( '-6 months', strtotime('first day of last month')) );
            $end_date   = date( 'Y-m-d', strtotime( 'last day of last month' ) );
            $last_start_date = date( 'Y-m-d', strtotime( '-6 months', strtotime( $start_date ) ) );
            $last_end_date   = date( 'Y-m-d', strtotime('-6 months', strtotime( $end_date ) ) );
            $last_end_date   = date( 'Y-m-d', strtotime('-1 day', strtotime( $last_end_date ) ) );
            $query_selector  = 'monthname';
            $select5 = 'selected';
            break;

        case 'wpuf-transaction-this-year':
            $start_date      = date( 'Y-m-d', strtotime( 'first day of January' ) );
            $end_date        = date( 'Y-m-d', strtotime( 'last day of this month' ) );
            $query_selector  = 'monthname';
            $last_start_date = date( 'Y-m-d', strtotime( 'last year January 1st' ) );
            $last_end_date   = date( 'Y-m-d', strtotime( 'last year December 31st' ) );
            $select6 = 'selected';
            break;

        case 'wpuf-transaction-last-year':
            $start_date = date( 'Y-m-d', strtotime( 'last year January 1st' ) );
            $end_date   = date( 'Y-m-d', strtotime( 'last year December 31st' ) );
            $last_start_date = date( 'Y-m-d', strtotime( '-1 year', strtotime( $start_date ) ) );
            $last_end_date   = date( 'Y-m-d', strtotime('-1 year', strtotime( $end_date ) ) );
            $query_selector  = 'monthname';
            $select7 = 'selected';
            break;

        case 'wpuf-transaction-custom-time':
            $start_date     = $_GET['sub_start_date'];
            $end_date       = $_GET['sub_end_date'];
            $query_selector = 'date';
            break;

        default:
            break;
        }
    } else {
        $start_date = date( 'Y-m-d', strtotime( 'first day of this month' ) );
        $end_date = date( 'Y-m-d', strtotime( 'last day of this month' ) );
        $last_start_date = date( 'Y-m-d', strtotime( 'first day of last month' ) );
        $last_end_date   = date( 'Y-m-d', strtotime( 'last day of last month' ) );
        $query_selector  = 'date';
    }

    $total_sales_sql = "SELECT count(*) as sales_number, SUM(`cost`) as total_sale, " . "{$query_selector}" . "(DATE_FORMAT(`created`, '%Y-%m-%d')) as buy_date
        FROM `" . $wpdb->prefix . "wpuf_transaction` WHERE DATE_FORMAT(`created`, '%Y-%m-%d') BETWEEN " . "'{$start_date}'" ." AND " . "'{$end_date}'" . " AND `status` = 'completed'
        GROUP BY buy_date ORDER BY created";

    $total_tax_sql = "SELECT count(*) as sales_number, SUM(`tax`) as total_tax, " . "{$query_selector}" . "(DATE_FORMAT(`created`, '%Y-%m-%d')) as buy_date
        FROM `" . $wpdb->prefix . "wpuf_transaction` WHERE DATE_FORMAT(`created`, '%Y-%m-%d') BETWEEN " . "'{$start_date}'" ." AND " . "'{$end_date}'" . " AND `status` = 'completed'
        GROUP BY buy_date ORDER BY created";

    $total_sales_results = $wpdb->get_results( $total_sales_sql );
    $total_tax_results = $wpdb->get_results( $total_tax_sql );

    foreach ( $total_sales_results as $total_sales_result ) {
        $sales_count += $total_sales_result->sales_number;
        $total_sale += $total_sales_result->total_sale;
        $curr_sales_data[] =  $total_sales_result->total_sale;

        if ( isset( $_GET['wpuf-transaction-dropdown'] ) && 'wpuf-transaction-last-year' == $_GET['wpuf-transaction-dropdown'] ) {
            $transaction_labels[] = date( 'M', strtotime( $total_sales_result->buy_date ) ) . '-' . date("Y",strtotime("-1 year"));
        } elseif ( $query_selector == 'monthname' ) {
            $transaction_labels[] = date( 'M', strtotime( $total_sales_result->buy_date ) );
        } else {
            $transaction_labels[] = date( 'j-M', strtotime( $total_sales_result->buy_date ) );
        }
    }

    foreach ( $total_tax_results as $total_tax_result ) {
        $tax_count += $total_tax_result->sales_number;
        $total_tax += $total_tax_result->total_tax;
        $curr_tax_data[] =  $total_tax_result->total_tax;
    }

    if ( isset( $last_start_date ) && isset( $last_end_date ) ) {
        $last_sales_sql = "SELECT count(*) as sales_number, SUM(`cost`) as total_sale, DATE_FORMAT(`created`, '%Y-%m-%d') as buy_date
            FROM `" . $wpdb->prefix . "wpuf_transaction` WHERE DATE_FORMAT(`created`, '%Y-%m-%d') BETWEEN " . "'{$last_start_date}'" ." AND " . "'{$last_end_date}'" . " AND `status` = 'completed' AND pack_id > 0
            GROUP BY buy_date ORDER BY buy_date";
        $last_sales_results = $wpdb->get_results( $last_sales_sql );

        foreach ( $last_sales_results as $last_sales_result ) {
            $last_sale += $last_sales_result->total_sale;
            $last_sales_data[] =  $last_sales_result->total_sale;
        }

        for ( $i = 0; $i < count( $transaction_labels ); $i++ ) {
            if ( empty( $last_sales_data[$i] ) ) {
                $last_sales_data[$i] = 0;
            }
        }
    }


    $transaction_colors[0] = array( 'backgroundColor' => 'transparent', 'borderColor' => '#3498db' );
    $transaction_colors[1] = array( 'backgroundColor' => 'transparent', 'borderColor' => '#1abc9c' );
    $transaction_colors[2] = array( 'backgroundColor' => 'transparent', 'borderColor' => '#73a724' );

    $transaction_attributes[0]  = array( 'id'    => 'wpuf_sales_chart', 'width'  => 90, 'height' => 50, 'style'  => 'display:inline;' );

    $transaction_datasets[0] = array( 'data'  => $curr_sales_data, 'label' => __( "Sales in this Period", "wpuf-pro" ) ) + $transaction_colors[0];
    $transaction_datasets[1] = array( 'data'  => $curr_tax_data, 'label' => __( "Tax in this Period", "wpuf-pro" ) ) + $transaction_colors[1];
    $transaction_datasets[2] = array( 'data'  => $last_sales_data, 'label' => __( "Sales in last Period", "wpuf-pro" ) ) + $transaction_colors[2];

    $transaction_line = new ChartJS( 'line', $transaction_labels, $options, $transaction_attributes[0] );
    $transaction_line->addDataset( $transaction_datasets[0] );
    $transaction_line->addDataset( $transaction_datasets[1] );
    $transaction_line->addDataset( $transaction_datasets[2] );
    ?>

    <div class="wpuf-transaction-report-nav" style="width: 100%; margin-top: 15px;">
        <!-- <label style="display: inline;">Transaction Report</label> -->
        <form method="get" action="<?php echo admin_url( 'admin.php'); ?>" class="form-inline report-filter" style="float: right; margin-right: 20px; display: inline;">
            <span class="form-group">
                <input type="hidden" name="page" value="wpuf_reports" />
                <input type="hidden" name="tab" value="transaction_reports" />
                <label><?php _e( 'Transaction Report Period:', 'wpuf-pro' ); ?></label>
                <select id="wpuf-transaction-dropdown" name="wpuf-transaction-dropdown" style="display: inline;">
                    <option value="wpuf-transaction-this-month" <?php echo $select1; ?> ><?php _e( 'This Month', 'wpuf-pro' ); ?></option>
                    <option value="wpuf-transaction-last-month" <?php echo $select2; ?> ><?php _e( 'Last Month', 'wpuf-pro' ); ?></option>
                    <option value="wpuf-transaction-this-quarter" <?php echo $select3; ?> ><?php _e( 'This Quarter', 'wpuf-pro' ); ?></option>
                    <option value="wpuf-transaction-last-quarter" <?php echo $select4; ?> ><?php _e( 'Last Quarter', 'wpuf-pro' ); ?></option>
                    <option value="wpuf-transaction-last-6-month" <?php echo $select5; ?> ><?php _e( 'Last 6 Month', 'wpuf-pro' ); ?></option>
                    <option value="wpuf-transaction-this-year" <?php echo $select6; ?> ><?php _e( 'This Year', 'wpuf-pro' ); ?></option>
                    <option value="wpuf-transaction-last-year" <?php echo $select7; ?> ><?php _e( 'Last Year', 'wpuf-pro' ); ?></option>
                    <option value="wpuf-transaction-custom-time" ><?php _e( 'Custom Range', 'wpuf-pro' ); ?></option>
                </select>
            </span>
            <span id="wpuf-transaction-custom-time" class="wpuf-date-range">
                <span class="form-group">
                    <label for="from"><?php _e( 'From:', 'wpuf-pro' ); ?></label>
                    <input type="text" name="sub_start_date" id="from" class="datepicker" readonly="readonly" value=""/>
                </span>
                <span class="form-group">
                    <label for="to"><?php _e( 'To:', 'wpuf-pro' ); ?></label>
                    <input type="text" name="sub_end_date" id="to" class="datepicker" readonly="readonly" value=""/>
                </span>
            </span>
            <button type="submit" name="wpuf_report_filter_transaction" class="button button-secondary" value="submit"><?php _e( 'Show', 'wpuf-pro' ); ?></button>
        </form>
    </div>

    <div class="wpuf-report-container">
        <div class="wpuf-chart-legend">
            <ul style="width: 24%; display:inline;">
                <li>
                    Total Sales
                    <?php
                    if ( $last_sale === 0 || $total_sale === 0 ) {
                        $percent_change = ( $total_sale - $last_sale ) * 100;
                    } else {
                        $percent_change = ( ( $total_sale - $last_sale ) / $last_sale ) * 100;
                    }
                    if ( $total_sale > $last_sale ) { ?>
                        <div class="wpuf-chart-sidebar">
                            <div class="wpuf-chart-flex-item">
                                <strong> <?php echo wpuf_format_price( $total_sale ); ?> </strong>
                            </div>
                            <div class="wpuf-chart-flex-item2">
                                <span class="percentage-change increase-class dashicons dashicons-arrow-up"></span>
                                <span class="percentage-change"> <?php echo number_format((float)$percent_change, 2, '.', '') . '%'; ?> </span>
                            </div>
                        </div>
                    <?php } elseif ( $total_sale < $last_sale ) { ?>
                        <div class="wpuf-chart-sidebar">
                            <div class="wpuf-chart-flex-item">
                                <strong> <?php echo wpuf_format_price( $total_sale ); ?> </strong>
                            </div>
                            <div class="wpuf-chart-flex-item2">
                                <span class="percentage-change decrease-class dashicons dashicons-arrow-down"></span>
                                <span class="percentage-change"> <?php echo number_format((float)$percent_change, 2, '.', '') . '%'; ?> </span>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="wpuf-chart-sidebar">
                            <div class="wpuf-chart-flex-item">
                                <strong> <?php echo wpuf_format_price( $total_sale ); ?> </strong>
                            </div>
                            <div class="wpuf-chart-flex-item2">
                                <span class="percentage-change nochange-class dashicons dashicons-leftright"></span>
                                <span class="percentage-change"> <?php echo number_format((float)$percent_change, 2, '.', '') . '%'; ?> </span>
                            </div>
                        </div>
                    <?php } ?>
                </li>
                <li>
                    Tax Amount
                    <br>
                    <strong> <?php echo wpuf_format_price( $total_tax ); ?> </strong>
                </li>
                <li>
                    Net Income
                    <br>
                    <strong> <?php echo wpuf_format_price( $total_sale - $total_tax ); ?> </strong>
                </li>
            </ul>
        </div>
        <div id="wpuf-transaction-line" class="wpuf-transaction-chart-container" style="width: 72%; float: right; display:inline; background-color: #fff; margin: 10px 5px 0 0;">
            <?php
            if ( ! empty( $transaction_line ) ) {
                echo $transaction_line;
            }
            ?>
        </div>
    </div>
<?php

}

function wpuf_report_page_tabs( $active_tab = 'reg_reports' ) {
    ?>
    <h2 class="nav-tab-wrapper">
        <a href="?page=wpuf_reports&tab=reg_reports" class="nav-tab <?php echo $active_tab == 'reg_reports' ? 'nav-tab-active' : ''; ?>"><?php _e( 'User Reports', 'wpuf-pro' ); ?></a>
        <a href="?page=wpuf_reports&tab=post_reports" class="nav-tab <?php echo $active_tab == 'post_reports' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Post Reports', 'wpuf-pro' ); ?></a>
        <a href="?page=wpuf_reports&tab=subscription_reports" class="nav-tab <?php echo $active_tab == 'subscription_reports' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Subscription Reports', 'wpuf-pro' ); ?></a>
        <a href="?page=wpuf_reports&tab=transaction_reports" class="nav-tab <?php echo $active_tab == 'transaction_reports' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Transaction Reports', 'wpuf-pro' ); ?></a>
    </h2>
<?php
    if ( $active_tab == 'reg_reports' ) {
        wpuf_user_reg_report_chart();
    } elseif ( $active_tab == 'post_reports' ) {
        wpuf_post_report_chart();
    } elseif ( $active_tab == 'subscription_reports' ) {
        wpuf_subscription_report_chart();
    } elseif( $active_tab == 'transaction_reports' ) {
        wpuf_transaction_report();
    }
}

$current_screen = get_current_screen();

if ( $current_screen->id == 'user-frontend_page_wpuf_reports' && !isset( $_GET['tab'] ) ) {
    wpuf_report_page_tabs( 'reg_reports' );
} else {
    $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'user_reports';
    wpuf_report_page_tabs( $active_tab );
}
