<?php
// Must pass session data for the library to work (only if not already included in your app)
require_once( '../../config/conn.php' );

  
// Facebook app settings
$app_id = CONF_FB_APP_API;
$app_secret = CONF_FB_APP_SECRET;
$redirect_uri = 'http://sandbox.local/demos/facebook-sdk-php-v4/';

// Requested permissions for the app - optional
$permissions = array(
  'email',
  'user_location',
  'user_birthday'
);

// Autoload the required files
define('FACEBOOK_SDK_V4_SRC_DIR',FILE_PATH.'/api/facebook-sdk-php-v4/facebook-php-sdk-v4-4.0-dev/');
require_once("autoload.php");

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookRedirectLoginHelper;

// Initialize the SDK
FacebookSession::setDefaultApplication( $app_id, $app_secret );

// Create the login helper and replace REDIRECT_URI with your URL
// Use the same domain you set for the apps 'App Domains'
// e.g. $helper = new FacebookRedirectLoginHelper( 'http://mydomain.com/redirect' );
$helper = new FacebookRedirectLoginHelper( $redirect_uri );

// Check if existing session exists
if ( isset( $_SESSION ) && isset( $_SESSION['fb_token'] ) ) {
  // Create new session from saved access_token
  $session = new FacebookSession( $_SESSION['fb_token'] );

    // Validate the access_token to make sure it's still valid
    try {
      if ( ! $session->validate() ) {
        $session = null;
      }
    } catch ( Exception $e ) {
      // Catch any exceptions
      $session = null;
    }
} else {
  // No session exists
  try {
    $session = $helper->getSessionFromRedirect();
  } catch( FacebookRequestException $ex ) {

    // When Facebook returns an error
  } catch( Exception $ex ) {

    // When validation fails or other local issues
    echo $ex->message;
  }
}

// Check if a session exists
if ( isset( $session ) ) {

  // Save the session
  $_SESSION['fb_token'] = $session->getToken();

  // Create session using saved token or the new one we generated at login
  $session = new FacebookSession( $session->getToken() );

  // Create the logout URL (logout page should destroy the session)
  $logoutURL = $helper->getLogoutUrl( $session, 'http://yourdomain.com/logout' );

  echo '<a href="' . $logoutURL . '">Log out</a>';
} else {
  // No session

  // Get login URL
  $loginUrl = $helper->getLoginUrl( $permissions );

  echo '<a href="' . $loginUrl . '">Log in</a>';
}
