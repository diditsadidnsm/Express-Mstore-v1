<?php
$changelog = array(
    array(
        'version'  => 'Version 3.1.11',
        'released' => '2019-10-17',
        'changes' => array(
            array(
                'title'       => __( "Embed Field's meta key is missing in the field settings", 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => "Embed Field's meta key is missing in the field settings",
            ),
            array(
                'title'       => __( 'Email confirmation link not working with bedrock environment.', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => 'Email confirmation link not working with bedrock environment. It was redirecting to 404 page.',
            ),
        )
    ),
    array(
        'version'  => 'Version 3.1.2',
        'released' => '2019-04-01',
        'changes' => array(
            array(
                'title'       => __( 'Repeat field with more than one column did not render data.', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => 'Repeat field with more than one column did not render data, fixed in this version.',
            ),
            array(
                'title'       => __( 'Checkbox and radio field data were not showing properly on user listing page.', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => 'Checkbox and radio field data were not showing properly on user listing page, fixed in this version.',
            ),
            array(
                'title'       => __( 'File type meta key in the WPUF User Listing module was not being saved.', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => 'File type meta key in the WPUF User Listing module was not being saved from backend settings, you will get it fixed.',
            ),
            array(
                'title'       => __( 'Subscription reminder email was being sent at a wrong time.', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => 'Subscription reminder email was being sent at a wrong time, from this version the email will be sent on time.',
            ),
            array(
                'title'       => __( 'Updated Stripe library & set Stripe AppInfo.', 'wpuf-pro' ),
                'type'        => 'Improvement',
                'description' => 'Updated Stripe library & set Stripe AppInfo.',
            ),
        )
    ),
    array(
        'version'  => 'Version 3.1.0',
        'released' => '2019-01-31',
        'changes' => array(
            array(
                'title'       => __( 'User logged in without activation', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( 'If <strong>Auto Login After Registration</strong> option is enabled from Login/Registration settings, also admin approves and email verification options are required from the registration form, user get auto logged in after registration. This issue has been fixed in this version.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Custom field data not showing on the frontend', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( 'If a user applies multiple conditions in a single field, the field was unable to show the data on the frontend.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'User details not showing on the frontend when user activity module is active', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( 'User details not showing on the frontend when user activity module is active. You will get it fixed in this version.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Unable to edit the page where registration form shortcode exists', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( 'When `Subscription at Registration` option is enabled, it was unable to edit the page where the registration form shortcode exists, it just automatically goes to the frontend subscription page. Fixed in this release.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Dokan Vendor Registration Form: some fields were not mapping correctly on vendor store page', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( 'When using `Dokan Vendor Registration Form` following fields were not mapping correctly on the vendor store page: <br><br>- Store location google map <br>- Country field <br>- State field', 'wpuf-pro' ),
            ),
        )
    ),
    array(
        'version'  => 'Version 2.9.0',
        'released' => '2018-09-20',
        'changes' => array(
            array(
                'title'       => __( 'File upload field - make uploaded audio/video files playable on the frontend', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => __( 'Make Audio/Video files playable - This new option has been added in file upload field advanced options. After enabling this option user uploaded audio/video file will play on the frontend post page.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Embed field - new custom field', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => __( 'You can allow user to embed a video or another object into a post using this field. User just need to insert URL of the object, WPUF will automatically turn the URL into a related embed and provide a live preview in the visual editor. For supported sites please check <a href="https://codex.wordpress.org/Embeds">Embeds</a> documentation of codex.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Notification settings in the registration form', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => __( 'Added new notification section under registration form settings tab. Now, admin can enable/disable form specific email notifiations and change the email content.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Conditional logic option to run/skip MailChimp integration after submission', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => __( 'Now you have more control on Mailchimp integration. You can configure conditional logic with form fields, then MailChimp integration will only run if the configured condition meets by user when registering.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Reports module', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => __( 'Show various reports (User Reports, Post Reports, Subscription Reports, Transaction Reports). If you have purchased WPUF Pro business package then you can activate this module and check the reports under <b>User Frontend->Reports</b> menu.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'The Events Calendar integration template', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => __( 'One click post form template, The Events Calendar form will allow users to create event from the frontend. Please check the documentation <a href="https://wedevs.com/docs/wp-user-frontend-pro/posting-forms/the-events-calendar-integration-template/">here.</a>', 'wpuf-pro' ),
            ),
        )
    ),
    array(
        'version'  => 'Version 2.8.2',
        'released' => '2018-07-19',
        'changes' => array(
            array(
                'title'       => __( 'Added content filter feature for Post Title and Post Content', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => __( 'Now you can restrict use of certain words for user submitted posts. You can find this under Content Filter section of WPUF Settings', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Resend activation email', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => __( 'Users can now resend activation email in case they didn\'t  receive the email first time.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'New template for Easy Digital downloads products', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => __( 'Just like WooCommerce product template we have provided a product template for EDD.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Set custom Edit Profile form on Account page', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => __( "Now you can override the default Edit Profile Form on my account page. Go to WPUF Settings > My Account and choose a Profile Form", 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'More options within Customizer to change the look and feel of WPUF components', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => __( 'We have added a new section under WordPress Customizer named WP User Front end , here you can change colors of notices and subscriptions and more', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Facebook social login URL not working issue is fixed', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( 'Facebook redirect URL was not rendered properly and is fixed now', 'wpuf-pro' ),
            )
        )
    ),
    array(
        'version'  => 'Version 2.8.1',
        'released' => '2018-04-15',
        'changes' => array(
            array(
                'title'       => __( 'Added Tax for payments', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => __( 'Now you can setup Tax rates on WPUF payments like Pay Per Post payments and Subscription Pack payments. Check the setup guideline <a href="https://wedevs.com/docs/wp-user-frontend-pro/settings/tax/" target="_blank">here</a>.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Avatar image size on registration', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => __( 'You can now set avatar size from User Frontend > Settings > Login/Registration > Avatar Size.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Updated Stripe SDK', 'wpuf-pro' ),
                'type'        => 'Improvement',
                'description' => __( 'Updated Stripe SDK to 6.4.1. <br> Stripe module is now fully compatible with the latest Stripe API. If you are still using old API you should upgrade to latest API version from your  <a href="https://dashboard.stripe.com/developers" target="_blank">Stripe Dashboard</a>. Older API should work fine as well but it\'s recommended that you upgrade soon.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Registration confirmation URL wasn\'t redirecting to login page', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( "Registration confirmation link now redirects users to Login page set in settings.", 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Date format in coupon', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( 'Coupon date format was not compatible with WordPress date format. Now it works with WordPress date format.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'User directory search query', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( 'User directory search was not working for custom fields is fixed now.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Showing country code on the frontend instead of country name', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( 'Country field was showing country code which is irrelevant, now it will show country name on the frontend.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Fixed google callback in social login', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( "Social login with google was not working in some cases.", 'wpuf-pro' ),
            )
        )
    ),
    array(
        'version'  => 'Version 2.8.0',
        'released' => '2018-01-02',
        'changes' => array(
            array(
                'title'       => __( 'Introducing New Modules for better Integration and Workflow of your Forms', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => '<ul>
                                    <li style="margin-bottom: 5px"><b><i style="color: #1794CE;">Personal Package </i>: MailPoet 2</b></li>
                                    <li style="margin-bottom: 5px"><b><i style="color: #20C5BA;">Professional Package </i>: MailPoet 3 , Campaign Monitor, GetResponse & HTML Email Templates</b></li>
                                    <li style="margin-bottom: 5px"><b><i style="color: #F16E58">Business Package Exclusive </i> : Private Messaging, Zapier, Convert Kit & User Activity</b></li>
                                  </ul>
                                  <br>
                                  <a href="https://wedevs.com/in/wpuf-v2-8" target="_blank"> Click here to read more </a>'
            ),
            array(
                'title'       => __( 'Admin approval for newly Registered users', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => __( 'A new option added on registration form settings to approve user by admin. You can make a user pending before approved by admin.', 'wpuf-pro' ) .
                '<br><br><iframe width="100%" height="372" src="https://www.youtube.com/embed/jJ05767-Ew4" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>'
            ),
            array(
                'title'       => __( 'Subscription expire notification', 'wpuf-pro' ),
                'type'        => 'New',
                'description' => __( 'Add new notification for subscription expiration. User will get custom email after subscription expiration.', 'wpuf-pro' ) .
                '<br><br><iframe width="100%" height="372" src="https://www.youtube.com/embed/jotTY4FCHsk" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>'
            ),
            array(
                'title'       => __( 'Form submission with Captcha field', 'wpuf-pro' ),
                'type'        => 'Improvement',
                'description' => __( 'Form field validation process updated if form submits with captcha field.', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Confirmation email not sent while email module is deactivated', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( 'Users were not receiving confirmation email if the email module is deactivated, this issue is fixed now', 'wpuf-pro' ),
            ),
            array(
                'title'       => __( 'Various other bug fixed and improvements are done', 'wpuf-pro' ),
                'type'        => 'Fix',
                'description' => __( 'For more details see the Changelog.', 'wpuf-pro' ),
            ),
        )
    )
);

if ( ! function_exists( '_wpuf_changelog_content' ) ) {
    function _wpuf_changelog_content( $content ) {
        $content = wpautop( $content, true );

        return $content;
    }
}

?>

<div class="wrap wpuf-whats-new">
    <h1><?php _e( 'What\'s New in WPUF Pro?', 'wpuf' ); ?></h1>

    <div class="wedevs-changelog-wrapper">

        <?php foreach ( $changelog as $release ) { ?>
            <div class="wedevs-changelog">
                <div class="wedevs-changelog-version">
                    <h3><?php echo esc_html( $release['version'] ); ?></h3>
                    <p class="released">
                        (<?php echo human_time_diff( time(), strtotime( $release['released'] ) ); ?> ago)
                    </p>
                </div>
                <div class="wedevs-changelog-history">
                    <ul>
                        <?php foreach ( $release['changes'] as $change ) { ?>
                            <li>
                                <h4>
                                    <span class="title"><?php echo esc_html( $change['title'] ); ?></span>
                                    <span class="label <?php echo strtolower( $change['type'] ); ?>"><?php echo esc_html( $change['type'] ); ?></span>
                                </h4>

                                <div class="description">
                                    <?php echo _wpuf_changelog_content( $change['description'] ); ?>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        <?php } ?>
    </div>

</div>
