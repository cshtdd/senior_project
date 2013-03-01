<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("application/libraries/OAuth2/Token.php");
require_once("application/libraries/OAuth2/Client.php");
require_once("application/libraries/OAuth2/DataStore.php");
require_once("application/libraries/OAuth2/HttpClient.php");
require_once("application/libraries/OAuth2/Exception.php");
require_once("application/libraries/OAuth2/Service/Configuration.php");
require_once("application/libraries/OAuth2/DataStore/Session.php");
require_once("application/libraries/OAuth2/Service.php");

class LoginController extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->load->helper('form');
	}

	public function index()
	{
		$this->load->view('login_index');
	}

	public function oauth2()
	{
		$client = new OAuth2\Client(
	        '10019035853.apps.googleusercontent.com',
	        'MWTrFtPTn3TxUOf8q9jqPpd5',
	        'http://srprog-spr13-01.aul.fiu.edu/senior-projects/login/oauth2_callback'
        );

		$configuration = new OAuth2\Service\Configuration(
	        'https://accounts.google.com/o/oauth2/auth',
	        'https://accounts.google.com/o/oauth2/auth/token'
        );

        $dataStore = new OAuth2\DataStore\Session();

		$scope = "https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email";

		$service = new OAuth2\Service($client, $configuration, $dataStore, $scope);

		$service->authorize();
	}

	public function oauth2_callback()
	{
		$code = $this->input->get("code");

		$client = new OAuth2\Client(
	        '10019035853.apps.googleusercontent.com',
	        'MWTrFtPTn3TxUOf8q9jqPpd5',
	        'http://srprog-spr13-01.aul.fiu.edu/senior-projects/login/oauth2_callback'
        );

		$configuration = new OAuth2\Service\Configuration(
	        'https://accounts.google.com/o/oauth2/auth',
	        'https://accounts.google.com/o/oauth2/token'
        );

        $dataStore = new OAuth2\DataStore\Session();

		$scope = "https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email";

		$service = new OAuth2\Service($client, $configuration, $dataStore, $scope);

		$service->getAccessToken($code);

		$token = $dataStore->retrieveAccessToken();

		$userinfo = $service->callApiEndpoint('https://www.googleapis.com/oauth2/v1/userinfo');

		
		echo $userinfo;
	}
}
