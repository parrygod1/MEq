<?php
require_once 'facebook/vendor/autoload.php';

class User {
    protected $google_client;
    protected $facebook, $facebook_helper, $facebook_permissions, $facebook_login_url;

    public function __construct() {
        $this->google_client = new Google_Client();

        $this->google_client->setClientId('87779269660-efp8di50vjfmodnqbn1l7euj5tp7ko4g.apps.googleusercontent.com');

        $this->google_client->setClientSecret('A7gm63efTbaSaak-kvsB6LFG');

        $this->google_client->setRedirectUri('http://localhost/MEq/php/userAccount/login.php');

        $this->google_client->addScope('email');

        $this->google_client->addScope('profile');

        $this->facebook = new \Facebook\Facebook([
            'app_id'      => '280200376349519',
            'app_secret'     => 'a6aaf43f8cd048231430f355d3307095',
            'default_graph_version'  => 'v2.10'
        ]);

        $this->facebook_helper = $this->facebook->getRedirectLoginHelper();
        $this->facebook_permissions = ['email'];
        $this->facebook_login_url = $this->facebook_helper->getLoginUrl('https://meqx.go.ro/meqtest/php/userAccount/login.php', $this->facebook_permissions);
    }

}
