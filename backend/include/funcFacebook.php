<?php
/*
 function fb_publicar_fun_page() {
 		require_once  FILE_PATH . '/api/facebook4/src/Facebook/autoload.php';   
	define('APP_ID', CONF_FB_APP_API);
	define('APP_SECRET', CONF_FB_APP_SECRET);

	// instancia el objeto de fb
	$fb = new Facebook\Facebook([
    'app_id'     => APP_ID,
    'app_secret' => APP_SECRET,
    'default_graph_version' => 'v2.4'
	]);
	
	
	// si existe una session del tk lo utiliza
	if(isset($_SESSION['fb_access_token'])) {
    $accessToken = $_SESSION['fb_access_token'];

	// obtiene el primer token
	} else {

    $helper = $fb->getRedirectLoginHelper();
    
    try {
        $accessToken = $helper->getAccessToken();
        
        print "accessToken" . $accessToken;
        if(isset($accessToken)) {
            $oAuth2Client = $fb->getOAuth2Client();

            // longlived access token
            if (!$accessToken->isLongLived()) {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken); 
            }
        }

    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
  }
  
  // hay token
	if(isset($accessToken)) {
    // Logged in!
    $_SESSION['fb_access_token'] = (string) $accessToken;

    try {
        $response = $fb->get('/me/accounts', $accessToken);

        echo '<pre>';
        print_r($response->getDecodedBody());
        echo '</pre>';

    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    } 
    
  } else {
    $helper = $fb->getRedirectLoginHelper();

    $redirect_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $loginUrl = $helper->getLoginUrl($redirect_url, [ 'manage_pages', 'publish_actions' ]);
    echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
  }    
 }

 function obtener_extended_page_token() {
 }
  
 function fb_mis_fun_page() {
 	


 }// fin f
*/
?>