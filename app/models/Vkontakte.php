
<?php
/**
 * The Vkontakte PHP SDK
 *
 * @author Bocharsky Victor, https://github.com/Vastly
 */
class Vkontakte
{
    const VERSION = '5.5';
    /**
     * The application ID
     * @var integer
     */
    private $appId;
    /**
     * The application secret code
     * @var string
     */
    private $secret;
    /**
     * The scope for login URL
     * @var array
     */
    private $scope = array();
    /**
     * The URL to which the user will be redirected
     * @var string
     */
    private $redirect_uri;
    /**
     * The response type of login URL
     * @var string
     */
    private $responceType = 'code';
    /**
     * The current access token
     * @var \StdClass
     */
    private $accessToken;
    /**
     * The Vkontakte instance constructor for quick configuration
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (isset($config['access_token'])) {
            $this->setAccessToken(json_encode(['access_token' => $config['access_token']]));
        }
        if (isset($config['app_id'])) {
            $this->setAppId($config['app_id']);
        }
        if (isset($config['secret'])) {
            $this->setSecret($config['secret']);
        }
        if (isset($config['scopes'])) {
            $this->setScope($config['scopes']);
        }
        if (isset($config['redirect_uri'])) {
            $this->setRedirectUri($config['redirect_uri']);
        }
        if (isset($config['response_type'])) {
            $this->setResponceType($config['response_type']);
        }
    }
    /**
     * Get the user id of current access token
     * @return integer
     */
    public function getUserId()
    {
        return $this->accessToken->user_id;
    }
    /**
     * Set the application id
     * @param integer $appId
     * @return \Vkontakte
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;
        return $this;
    }
    /**
     * Get the application id
     * @return integer
     */
    public function getAppId()
    {
        return $this->appId;
    }
    /**
     * Set the application secret key
     * @param string $secret
     * @return \Vkontakte
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }
    /**
     * Get the application secret key
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }
    /**
     * Set the scope for login URL
     * @param array $scope
     * @return \Vkontakte
     */
    public function setScope(array $scope)
    {
        $this->scope = $scope;
        return $this;
    }
    /**
     * Get the scope for login URL
     * @return array
     */
    public function getScope()
    {
        return $this->scope;
    }
    /**
     * Set the URL to which the user will be redirected
     * @param string $redirect_uri
     * @return \Vkontakte
     */
    public function setRedirectUri($redirect_uri)
    {
        $this->redirect_uri = $redirect_uri;
        return $this;
    }
    /**
     * Get the URL to which the user will be redirected
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirect_uri;
    }
    /**
     * Set the response type of login URL
     * @param string $responceType
     * @return \Vkontakte
     */
    public function setResponceType($responceType)
    {
        $this->responceType = $responceType;
        return $this;
    }
    /**
     * Get the response type of login URL
     * @return string
     */
    public function getResponceType()
    {
        return $this->responceType;
    }
    /**
     * Get the login URL via Vkontakte
     * @return string
     */
    public function getLoginUrl()
    {
        return 'https://oauth.vk.com/authorize'
        . '?client_id=' . urlencode($this->getAppId())
        . '&scope=' . urlencode(implode(',', $this->getScope()))
        . '&redirect_uri=' . urlencode($this->getRedirectUri())
        . '&response_type=' . urlencode($this->getResponceType())
        . '&v=' . urlencode(self::VERSION);
    }
    /**
     * Check is access token expired
     * @return boolean
     */
    public function isAccessTokenExpired()
    {
        return time() > $this->accessToken->created + $this->accessToken->expires_in;
    }
    /**
     * Authenticate user and get access token from server
     * @param string $code
     * @return \Vkontakte
     */
    public function authenticate($code = NULL)
    {
        $code = $code ? $code : $_GET['code'];
        $url = 'https://oauth.vk.com/access_token'
            . '?client_id=' . urlencode($this->getAppId())
            . '&client_secret=' . urlencode($this->getSecret())
            . '&code=' . urlencode($code)
            . '&redirect_uri=' . urlencode($this->getRedirectUri());
        $token = $this->curl($url);
        $data = json_decode($token);
        $data->created = time(); // add access token created unix timestamp
        $token = json_encode($data);
        $this->setAccessToken($token);
        return $this;
    }
    /**
     * Set the access token
     * @param string $token The access token in json format
     * @return \Vkontakte
     */
    public function setAccessToken($token)
    {
        $this->accessToken = json_decode($token);
        return $this;
    }
    /**
     * Get the access token
     * @param string $code
     * @return string The access token in json format
     */
    public function getAccessToken()
    {
        return json_encode($this->accessToken);
    }
    /**
     * Make an API call to https://api.vk.com/method/
     * @return string The response, decoded from json format
     */
    public function api($method, array $query = array())
    {
        /* Generate query string from array */
        $parameters = array();
        foreach ($query as $param => $value) {
            $q = $param . '=';
            if (is_array($value)) {
                $q .= urlencode(implode(',', $value));
            } else {
                $q .= urlencode($value);
            }
            $parameters[] = $q;
        }
        $q = implode('&', $parameters);
        if (count($query) > 0) {
            $q .= '&'; // Add "&" sign for access_token if query exists
        }
        $url = 'https://api.vk.com/method/' . $method . '?' . $q . 'access_token=' . $this->accessToken->access_token;
        $result = json_decode($this->curl($url));
       
        if (isset($result->response)) {
            return $result->response;
        }
        return $result;
    }

    /**
     * Make the curl request to specified url
     * @param string $url The url for curl() function
     * @return mixed The result of curl_exec() function
     * @throws \Exception
     */
    protected function curl($url){

        if(!function_exists('curl_init')) return false;

        $param = parse_url($url);

        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $param['scheme'].'://'.$param['host'].$param['path']);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $param['query']);
            $out = curl_exec($curl);

            curl_close($curl);

            return $out;
        }
        return false;
    }


    /**
     * @param $publicID int vk group official identifier
     * @param $fullServerPathToImage string full path to the image file, ex. /var/www/site/img/pic.jpg
     * @param $text string message text
     * @param $tags array message tags
     * @return bool true if operation finished successfully and false otherwise
     */
    public function postToPublic($publicID, $text, $fullServerPathToImage='', $tags=array(), $postId=false)
    {
        if(!empty($fullServerPathToImage)){
            $response = $this->api('photos.getWallUploadServer', [
                'group_id' => $publicID,
            ]);  
            $uploadURL = $response->upload_url;
            $output = [];
            exec("curl -X POST -F 'photo=@$fullServerPathToImage' '$uploadURL'", $output);       

            if(isset($output[0])){
                $response = json_decode($output[0]);

                $response = $this->api('photos.saveWallPhoto', [
                    'group_id' => $publicID,
                    'photo' => $response->photo,
                    'server' => $response->server,
                    'hash' => $response->hash,
                ]);

                $data['attachments'] =  "{$response[0]->id}"; // uploaded image is passed as attachment
            }
        }
     
        if (!empty($tags[0])) {
            $text .= "\n\n";
            foreach ($tags as $tag) {
                $text .= ' #' . str_replace(' ', '_', $tag);
            }
        }

        $text = html_entity_decode($text);
        $data['owner_id'] = -$publicID;
        $data['from_group'] = 1;
        $data['message'] = "$text";
        
        if($postId){
            $data['post_id'] = $postId;
            $response = $this->api('wall.edit',$data);
            return $response;
        } else {
            $response = $this->api('wall.post',$data);
            if(isset($response->post_id)){
                return $response->post_id;
            } else {
                return 0;
            }
        }     
    }
}
