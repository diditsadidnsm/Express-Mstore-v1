<?php

//add_filter('puc_pre_inject_update-aioseop','preinject');
// This only works IF there is a plugin update for Pro. 

/*
For testing

			//Let plugins filter the update info before it's passed on to WordPress.
			$update = apply_filters('puc_pre_inject_update-' . $this->slug, $update);
			error_log(print_r($update,true));
			$myarray = array(
				'type' => 'plugin',
				'slug' => 'all-in-one-seo-pack',
				'language' => 'bg_BG',
				'version' => '2.3.8',
				'updated' => '2016-02-26 12:53:29',
				'package' => 'https://downloads.wordpress.org/translation/plugin/all-in-one-seo-pack/2.3.8/bg_BG.zip',
				'autoupdate' => '1'
			);
			$updates->translations[] = $myarray;
			$updates = $this->addUpdateToList($updates, $update);

*/
function preinject($update){
	
	error_log(print_r($update,true));
	return $update;
	$myarray = array(
		'type' => 'plugin',
		'slug' => 'all-in-one-seo-pack',
		'language' => 'bg_BG',
		'version' => '2.3.7',
		'updated' => '2016-02-26 12:53:29',
		'package' => 'https://downloads.wordpress.org/translation/plugin/all-in-one-seo-pack/2.3.8/bg_BG.zip',
		'autoupdate' => '1'
	);
	$update->translations = (object) $myarray;
	error_log(print_r($update, true));
	return $update;
}

// TODO - add update to transient in db... see if we can get the update bubble number to bump using inject_transient()
// TODO - some of this code can probably be improved by referencing translation-install.php and/or update.php

class aio_translations_check {
	function __construct() {
return;
		if( get_locale() === 'en_US'){
			return;
		}

		//add_action('delete_site_transient_update_plugins', array($this, 'clearCachedTranslationUpdates'));
//add_filter('site_transient_update_plugins',
		//add_filter('transient_update_plugins', array($this

		//add_filter('plugins_api', array($this, 'injectInfo'), 20, 3);

		//add_filter('transient_update_plugins', array($this,'inject_transient'), 10, 1); //WP 2.8+
		//add_filter('site_transient_update_plugins', array($this, 'inject_transient'), 10, 1);

		add_action( 'upgrader_process_complete', array( $this, 'hooks' ), 11, 0 );

		add_action( 'load-update-core.php', array( $this, 'hooks' ) );
		add_action( 'load-plugins.php', array( $this, 'hooks' ) );
		add_action( 'load-update.php', array( $this, 'hooks' ) );
		add_action( 'upgrader_process_complete', array(
			$this,
			'hooks',
		), 11, 0 ); // Fires after a bulk update is complete.

		add_action( 'load-update-core.php', array( $this, 'inject_transient' ) );
		add_action( 'load-plugins.php', array( $this, 'inject_transient' ) );

	}

	function maybeinject() {
		return '321321';
	}

	function inject_transient() {

		// Then the filtered stuff elsewhere needs to verify that language isn't already in the database

		$upplugins = get_site_transient( 'update_plugins' );


		$maybearray = (array) $upplugins->translations;

		$readytoinject = $this->injectTranslationUpdates( $upplugins );

		//error_log(print_r($readytoinject, true));


		//$upplugins = $this->injectTranslationUpdates($upplugins);


		//set_site_transient('update_plugins',$upplugins);

		//return;
		/*
				$myarray = array(
					'type' => 'plugin',
					'slug' => 'all-in-one-seo-pack',
					'language' => 'es_ES',
					'version' => '2.3.8',
					'updated' => '2016-02-26 12:53:29',
					'package' => 'https://downloads.wordpress.org/translation/plugin/all-in-one-seo-pack/2.3.8/es_ES.zip',
					'autoupdate' => '1'
				);

				$upplugins->translations[] = $myarray;

				$myarray = array(
					'type' => 'plugin',
					'slug' => 'all-in-one-seo-pack',
					'language' => 'bg_BG',
					'version' => '2.3.8',
					'updated' => '2016-02-26 12:53:29',
					'package' => 'https://downloads.wordpress.org/translation/plugin/all-in-one-seo-pack/2.3.8/bg_BG.zip',
					'autoupdate' => '1'
				);
				$upplugins->translations[] = $myarray;
		*/


		/*
				// This part injects into the DB... but doesn't seem to work right...
				$convertedUpdate = array_merge(
					(array)$readytoinject,
					(array)$upplugins
				);
				$upplugins->translations[] = $convertedUpdate;
		*/

		//$upplugins->translations[] = array('things'=>'stuff');

//		get_site_transient('update_plugins',$upplugins);

		//print_r($upplugins);

		set_site_transient( 'update_plugins', $upplugins );

		//echo "<br />AGAIN:<br />";

		//$upplugins = get_site_transient('update_plugins');
		//print_r($upplugins);
	}

	function injectTranslationUpdates( $updates ) {

		$installed_translations = wp_get_installed_translations( 'plugins' );

		if ( ! empty( $installed_translations['all-in-one-seo-pack'] ) ) {
			$installed_translations = $installed_translations['all-in-one-seo-pack'];
		} else {
			$installed_translations = '';
		}
		// $installed_translations Gets array of currently installed translations for AIOSEOP, each translation has the locale as the array key, which contains an info array with
		// PO-Revision-Date as the array key for the revision date

		$available_translations = $this->get_available_translations(); //list of all translation packs available for latest version of AIOSEOP

		if( is_wp_error( $available_translations ) ){
			return $updates;
		}

		$locales = array_values( get_available_languages() ); // list of all locales ever used

		$filtered_available_translations = $this->filter_available_translations( $available_translations, $locales ); // Filters for available translations that match needed locales

		/*
		if ( $available_translations == 1 ) {
				error_log( 'available_translations');
				error_log(print_r($available_translations, true));
				error_log('installed translations');
				error_log( print_r( $installed_translations, true ) );
				error_log('filtered available translations');
				error_log( print_r( $filtered_available_translations, true ) );
				error_log('desired locales');
				error_log( print_r( $locales, true) );
		}*/

		$date_filtered_translations = $this->filter_translations_by_date( $filtered_available_translations, $installed_translations );


		// Gets the latest version.
		$args        = (object) array( 'slug' => 'all-in-one-seo-pack' );
		$request     = array( 'action' => 'plugin_information', 'timeout' => 15, 'request' => serialize( $args ) );
		$url         = 'http://api.wordpress.org/plugins/info/1.0/';
		$response    = wp_remote_post( $url, array( 'body' => $request ) );

		if( is_wp_error( $response ) ){
			return $updates;
		}

		$plugin_info = unserialize( $response['body'] );

		$latest_version = $plugin_info->version;

		if ( ! isset( $date_filtered_translations ) || empty( $date_filtered_translations ) ) {
			return $updates;
		}


		foreach ( $date_filtered_translations['translations'] as $translation ) {

			$locale  = $translation['language'];
			$package = $translation['package'];
			$updated = $translation['updated'];
//$new_updates = array();
			$new_updates->translations[] = array(
				'type'       => 'plugin',
				'slug'       => 'all-in-one-seo-pack',
				'language'   => $locale,
				'autoupdate' => 1,
				'version'    => $latest_version,
				'package'    => $package,
				'updated'    => $updated,
			);

		}


		// TODO look for dupes between the two lists $new_updates['translations'] and $updates_['translations']
		// we can possibly do this in the above foreach and just not insert it if (array key exists or something)
		if ( isset( $new_updates ) ) {
			return $new_updates;
		} else {
			return $updates;
		}


		$convertedUpdate         = array_merge(
			array(
				'type'       => 'plugin',
				'slug'       => 'all-in-one-seo-pack',
				'autoupdate' => 1,
				//AFAICT, WordPress doesn't actually use the "version" field for anything.
				//But lets make sure it's there, just in case.
				'version'    => '2.3.7',
				'updated'    => '2016-02-26 12:53:29',
			),
			(array) $updates
		);
		$updates->translations[] = $convertedUpdate;
		$updates->translations[] = array( 'things' => 'stuff' );

//error_log( print_r( $updates, true ) );
		return $updates;
	}

	function get_available_translations() {

		$api = false;
		if ( function_exists( 'translations_api' ) ) {
			$latest_version = $this->get_latest_version();
			$api            = translations_api( 'plugins', array(
				'slug'    => 'all-in-one-seo-pack',
				'version' => $latest_version,
			) );
		} else {
			include( ABSPATH . 'wp-admin/includes/translation-install.php' );
		}

		//error_log( print_r($api, true));
		return $api;
	}

	function get_latest_version() {
		// This currently returns latest pro version number... we probably want to do better though.
		return AIOSEOP_VERSION;
	}

	function filter_available_translations( $available_translations, $locales ) {

		if ( ! $available_translations ) {
			return false;
		}

		if( is_wp_error( $available_translations ) ){
			return $available_translations;
		}

		//print_r($locales);

		if ( in_array( 'en_CA', $locales ) ) {
//			echo 'en_ca is in array';
		}


//print_r($available_translations);


		foreach ( $available_translations['translations'] as $key => $translation ) {

			/*
			if(in_array($translation['language'],$locales)){
				echo '<br /> ' . $translation['language'] . ' is in array';
			}else{
								echo '<br /> ' . $translation['language'] . ' is not in array';
			}
			*/


			// TODO THIS MAY NEED SOME WORK.
			if ( in_array( $translation['language'], $locales ) ) {
				//echo "<br /> ............... we are in array for " . $translation['language'];
				//echo $available_translations['translations'][$translation['language']];
				//unset($available_translations['translations'][$translation['language']]);
			} else {
				unset( $available_translations['translations'][ $key ] );
				//echo "<br /> .......... we are not in array for " . $available_translations['translations'][$translation['language']];
				//echo 'we are NOT in array';
			}


//			print_r($translation);
//echo '<br />' . $translation['language'] . '<br />';

//			foreach($translation as $key => $value){


			//			echo '<br /> key: ' . $key . '  value: ' . $value;
//echo $translation['language'];
//				print_r($translation);

//			}

		}

		return $available_translations;
		//print_r($available_translations);


	}

	function filter_translations_by_date( $filtered_available_translations, $installed_translations ) {
// go through filtered_available, and remove ones with an earlier date than what is in installed

		//print_r($installed_translations);

		if ( ! $filtered_available_translations ) {
			return false;
		}

		if ( is_wp_error( $filtered_available_translations)){
			return false;
		}

		if ( empty( $installed_translations ) ) {
			return $filtered_available_translations;
		}
		//print_r($filtered_available_translations);

		foreach ( $filtered_available_translations['translations'] as $key => $translation ) {
			//echo $translation['language'];

			$current_translation        = $translation['language'];
			$available_translation_date = $translation['updated'];
			$installed_translation_date = '2000-01-01 12:12:12';
			if ( isset( $installed_translations[ $current_translation ]['PO-Revision-Date'] ) ) {
				$installed_translation_date = $installed_translations[ $current_translation ]['PO-Revision-Date'];
			}
			//echo $current_translation;
			//echo $available_translation_date;
			//echo $installed_translation_date;

			// if $translation['updated'] > installed_translations[$current_translation['PO-Revision-Date']

			if ( strtotime( $available_translation_date ) > strtotime( $installed_translation_date ) ) {
				//echo '<br /> ........ . . . .. ' . $available_translation_date . ' is greater than ' . $installed_translation_date;

			} else {
				//echo '<br /> ........ . . . .. ' . $available_translation_date . ' is not greater  than ' . $installed_translation_date;
				//echo '.... now unset this: ' . $filtered_available_translations['translations'][$key];
				unset( $filtered_available_translations['translations'][ $key ] );

			}


		}
//echo "FILTERED:";
		//print_r($filtered_available_translations);

		return $filtered_available_translations;

	}

	function hooks() {
		add_filter( 'site_transient_update_plugins', array( $this, 'injectTranslationUpdates' ) );
		add_filter( 'transient_update_plugins', array( $this, 'injectTranslationUpdates' ) );
	}

	function injectInfo( $result, $action = null, $args = null ) {

		//error_log( print_r( $result, true ) );


		return 'all-in-one-seo-pack';
		// THIS IS ALL WRONG.
		$relevant = ( $action == 'plugin_information' ) && isset( $args->slug ) && (
			( $args->slug == 'all-in-one-seo-pack' )
			);
		if ( ! $relevant ) {
			return $result;
		}

		$pluginInfo = $this->requestInfo();

		if ( $pluginInfo ) {
			return $pluginInfo->toWpFormat();
		}

		return $result;
	}

	function maybeCheckForUpdates() {

	}


}

// $aio_translations_check = new aio_translations_check();
