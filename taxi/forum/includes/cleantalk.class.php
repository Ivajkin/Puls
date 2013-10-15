<?php
/**
 * Cleantalk base class
 *
 * @version 0.6
 * @package Cleantalk
 * @subpackage Base
 * @author Сleantalk team (shagimuratov@cleantalk.ru)
 * @copyright (C) 2011 - 2012 Сleantalk team (http://cleantalk.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 *
 **/

if (!defined('IN_PHPBB'))
{
	exit;
}

require(dirname(__FILE__).'/cleantalk.xmlrpc.php');

/**
 * Response class
 */
class CleantalkResponse {

    /**
     * Class version
     */
    const VERSION = '0.6';

    /**
     * Unique user ID
     * @var string
     */
    public $sender_id = null;

    /**
     *  Is stop words
     * @var int
     */
    public $stop_words = null;

    /**
     * Cleantalk comment
     * @var string
     */
    public $comment = null;

    /**
     * Is blacklisted
     * @var int
     */
    public $blacklisted = null;

    /**
     * Is allow, 1|0
     * @var int
     */
    public $allow = null;

    /**
     * Request ID
     * @var int
     */
    public $id = null;

    /**
     * Request errno
     * @var int
     */
    public $errno = null;

    /**
     * Error string
     * @var string
     */
    public $errstr = null;

    /**
     * Create server response
     *
     * @param type $response
     * @param xmlrpcresp $obj
     */
    function __construct($response = null, xmlrpcresp $obj = null) {
        if ($response && is_array($response) && count($response) > 0) {
            foreach ($response as $param => $value) {
                $this->{$param} = $value;
            }
        } else {
            // Разбираем xmlrpcresp ответ с клинтолка
            $this->errno = $obj->errno;
            $this->errstr = $obj->errstr;

            if ($obj->val !== 0) {
                $this->sender_id = (isset($obj->val['sender_id'])) ? $obj->val['sender_id'] : null;
                $this->stop_words = isset($obj->val['stop_words']) ? $obj->val['stop_words'] : null;
                $this->comment = $obj->val['comment'];
                $this->blacklisted = (isset($obj->val['blacklisted'])) ? $obj->val['blacklisted'] : null;
                $this->allow = (isset($obj->val['allow'])) ? $obj->val['allow'] : null;
                $this->id = (isset($obj->val['id'])) ? $obj->val['id'] : null;
            }
        }
    }

}

/**
 * Request class
 */
class CleantalkRequest {

    const VERSION = '0.6';

    /**
     * User message
     * @var string
     */
    public $message = null;

    /**
     * Post example with last comments
     * @var string
     */
    public $example = null;

    /**
     * Auth key
     * @var string
     */
    public $auth_key = null;

    /**
     * Engine
     * @var string
     */
    public $agent = null;

    /**
     * Page url
     * @var string
     */
    public $url = null;

    /**
     * Is check for stoplist,
     * valid are 0|1
     * @var int
     */
    public $stoplist_check = 1;

    /**
     * Language server response,
     * valid are 'en' or 'ru'
     * @var string
     */
    public $response_lang = 'en';

    /**
     * User IP
     * @var strings
     */
    public $sender_ip = null;

    /**
     * User email
     * @var strings
     */
    public $sender_email = null;

    /**
     * User nickname
     * @var string
     */
    public $sender_nickname = null;

    /**
     * Is allow links, email and icq,
     * valid are 1|0
     * @var int
     */
    public $allow_links = 0;

    /**
     * Time form filling
     * @var int
     */
    public $submit_time = null;

    /**
     * Is enable Java Script,
     * valid are 1|0
     * @var int
     */
    public $js_on = null;

    /**
     * user time zone
     * @var string
     */
    public $tz = null;

    /**
     * Feedback string,
     * valid are 'requset_id:(1|0)'
     * @var string
     */
    public $feedback = null;

    /**
     * Fill params with constructor
     * @param type $params
     */
    public function __construct($params = null) {
        if (is_array($params) && count($params) > 0) {
            foreach ($params as $param => $value) {
                $this->{$param} = $value;
            }
        }
    }

}

/**
 * Cleantalk class create request
 */
class Cleantalk {

    const VERSION = '0.6';

    /**
     * Debug level
     * @var int
     */
    public $debug = 0;

    /**
     * Cleantalk server url
     * @var string
     */
    public $server_url = null;

    /**
     * Last work url
     * @var string
     */
    public $work_url = null;

    /**
     * WOrk url ttl
     * @var int
     */
    public $server_ttl = null;

    /**
     * Time wotk_url changer
     * @var int
     */
    public $server_changed = null;

    /**
     * Flag is change server url
     * @var bool
     */
    public $server_change = false;

    /**
     * Function checks whether it is possible to publish the message
     * @param CleantalkRequest $request
     * @return type
     */
    public function isAllowMessage(CleantalkRequest $request) {
        $error_params = $this->filterRequest('check_message', $request);

        if (!empty($error_params)) {
            $response = new CleantalkResponse(
                            array(
                                'allow' => 0,
                                'comment' => 'Params error: ' . implode(', ', $error_params)
                            ), null);

            return $response;
        }

        $msg = $this->createMsg('check_message', $request);
        return $this->xmlRequest($msg);
    }

    /**
     * Function checks whether it is possible to publish the message
     * @param CleantalkRequest $request
     * @return type
     */
    public function isAllowUser(CleantalkRequest $request) {
        $error_params = $this->filterRequest('check_newuser', $request);

        if (!empty($error_params)) {
            $response = new CleantalkResponse(
                            array(
                                'allow' => 0,
                                'comment' => 'Params error: ' . implode(', ', $error_params)
                            ), null);

            return $response;
        }

        $msg = $this->createMsg('check_newuser', $request);
        return $this->xmlRequest($msg);
    }

    /**
     * Function sends the results of manual moderation
     *
     * @param CleantalkRequest $request
     * @return type
     */
    public function sendFeedback(CleantalkRequest $request) {
        $error_params = $this->filterRequest('send_feedback', $request);

        if (!empty($error_params)) {
            $response = new CleantalkResponse(
                            array(
                                'allow' => 0,
                                'comment' => 'Params error: ' . implode(', ', $error_params)
                            ), null);

            return $response;
        }

        $msg = $this->createMsg('send_feedback', $request);
        return $this->xmlRequest($msg);
    }

    /**
     *  Filter request params
     * @param CleantalkRequest $request
     * @return type
     */
    private function filterRequest($method, CleantalkRequest $request) {
        $error_params = array();

        // general
        foreach ($request as $param => $value) {
            if (in_array($param, array('message', 'example', 'agent',
                        'url', 'sender_nickname', 'sender_id')) && !empty($value)) {
                if (!is_string($value) && !is_integer($value)) {
                    $error_params[] = $param;
                }
            }

            if (in_array($param, array('stoplist_check', 'allow_links', 'js_on')) && !empty($value)) {
                if (!in_array($value, array(1, 2))) {
                    $error_params[] = $param;
                }
            }

            if ($param == 'sender_ip' && !empty($value)) {
                if (!is_string($value)) {
                    $error_params[] = $param;
                }
            }

            if ($param == 'sender_email' && !empty($value)) {
                if (!is_string($value)) {
                    $error_params[] = $param;
                }
            }

            if ($param == 'submit_time' && !empty($value)) {
                if (!is_int($value)) {
                    $error_params[] = $param;
                }
            }
        }

        // special
        switch ($method) {
            case 'check_message':
                if (empty($request->message)) {
                    $error_params[] = 'message';
                }
                if (empty($request->auth_key)) {
                    $error_params[] = 'auth_key';
                }
                if (!in_array($request->response_lang, array('ru', 'en'))) {
                    $error_params[] = 'response_lang';
                }
                break;

            case 'check_newuser':
                if (empty($request->auth_key)) {
                    $error_params[] = 'auth_key';
                }
                if (empty($request->sender_nickname)) {
                    $error_params[] = 'sender_nickname';
                }
                if (empty($request->sender_email)) {
                    $error_params[] = 'sender_email';
                }
                if (!in_array($request->response_lang, array('ru', 'en'))) {
                    $error_params[] = 'response_lang';
                }
                break;

            case 'send_feedback':
                if (empty($request->feedback)) {
                    $error_params[] = 'feedback';
                }
                break;
        }

        return $error_params;
    }

    /**
     * Create msg for cleantalk server
     * @param type $method
     * @param CleantalkRequest $request
     * @return \xmlrpcmsg
     */
    private function createMsg($method, CleantalkRequest $request) {
        switch ($method) {
            case 'check_message':
                $params = array(
                    'message' => $request->message,
                    'base_text' => $request->example,
                    'auth_key' => $request->auth_key,
                    'engine' => $request->agent,
                    'url' => $request->url,
                    'ct_stop_words' => $request->stoplist_check,
                    'response_lang' => $request->response_lang,
                    'session_ip' => $request->sender_ip,
                    'user_email' => $request->sender_email,
                    'user_name' => $request->sender_nickname,
                    'sender_id' => $this->getSenderId(),
                    'ct_links' => $request->allow_links,
                    'submit_time' => $request->submit_time,
                    'checkjs' => $request->js_on);
                break;

            case 'check_newuser':
                $params = array(
                    'auth_key' => $request->auth_key, // !
                    'engine' => $request->agent,
                    'response_lang' => $request->response_lang,
                    'session_ip' => $request->sender_ip,
                    'user_email' => $request->sender_email,
                    'user_name' => $request->sender_nickname,
                    'tz' => $request->tz,
                    'submit_time' => $request->submit_time,
                    'checkjs' => $request->js_on);
                break;

            case 'send_feedback':
                if (is_array($request->feedback)) {
                    $feedback = implode(';', $request->feedback);
                } else {
                    $feedback = $request->feedback;
                }

                $params = array(
                    'auth_key' => $request->auth_key, // !
                    'feedback' => $feedback);
                break;
        }

        $xmlvars = array();
        foreach ($params as $param) {
            $xmlvars[] = new xmlrpcval($param);
        }

        $ct_params = new xmlrpcmsg(
                        $method,
                        array(new xmlrpcval($xmlvars, "array"))
        );

        return $ct_params;
    }

    /**
     * XM-Request
     * @param xmlrpcmsg $msg
     * @return boolean|\CleantalkResponse
     */
    private function xmlRequest(xmlrpcmsg $msg) {
        $ct_request = new xmlrpc_client($this->work_url);
        $ct_request->request_charset_encoding = 'utf-8';
        $ct_request->return_type = 'phpvals';
        $ct_request->setDebug($this->debug);

        if ((isset($this->work_url) && $this->work_url !== '') && ($this->server_changed + $this->server_ttl > time())) {
            $result = $ct_request->send($msg);
        }

        if (!isset($result)) {
            $matches = array();
            preg_match("#^(http://|https://)([a-z\.\-0-9]+):?(\d*)$#i", $this->server_url, $matches);
            $url_prefix = $matches[1];
            $pool = $matches[2];
            $port = $matches[3];
            if (empty($url_prefix))
                $url_prefix = 'http://';
            if (empty($pool)) {
                return false;
            } else {
                foreach ($this->get_servers_ip($pool) as $server) {
                    $server_host = gethostbyaddr($server['ip']);
                    $work_url = $url_prefix . $server_host;
                    if ($server['host'] === 'localhost')
                        $work_url = $url_prefix . $server['host'];

                    $work_url = ($port !== '') ? $work_url . ':' . $port : $work_url;

                    $this->work_url = $work_url;
                    $ct_request = new xmlrpc_client($this->work_url);
                    $ct_request->request_charset_encoding = 'utf-8';
                    $ct_request->return_type = 'phpvals';
                    $ct_request->setDebug($this->debug);
                    $result = $ct_request->send($msg);

                    if (!$result->faultCode()) {
                        $this->server_change = true;
                        break;
                    }
                }
            }
        }

        $response = new CleantalkResponse(null, $result);

        if (!empty($response->sender_id)) {
            $this->setSenderId($response->sender_id);
        }
        return $response;
    }

    /**
     * Function DNS request
     * @param $host
     * @return array
     */
    public function get_servers_ip($host) {
        $response = null;
        if (!isset($host))
            return $response;

        if (function_exists('dns_get_record')) {
            foreach (dns_get_record($host, DNS_A) as $server) {
                $response[] = $server;
            }
            if (count($response))
                return $response;
        }
        if (function_exists('gethostbynamel')) {
            foreach (gethostbynamel($host) as $server) {
                $response[] = array("ip" => $server,
                    "host" => $host,
                    "ttl" => $this->server_ttl
                );
            }
        }

        return $response;
    }

    /**
     * Function to get the SenderID
     * @return string
     */
    public function getSenderId() {
        return ( isset($_COOKIE['ct_sender_id']) && !empty($_COOKIE['ct_sender_id']) ) ? $_COOKIE['ct_sender_id'] : '';
    }

    /**
     * Function to change the SenderID
     * @param $senderId
     * @return bool
     */
    private function setSenderId($senderId) {
        return @setcookie('ct_sender_id', $senderId);
    }

    /**
     * Function to get the message hash from Cleantalk.ru comment
     * @param $message
     * @return null
     */
    public function getCleantalkCommentHash($message) {
        $matches = array();
        if (preg_match('/\n\n\*\*\*.+([a-z0-9]{32}).+\*\*\*$/', $message, $matches))
            return $matches[1];
        else if (preg_match('/\<br.*\>[\n]{0,1}\<br.*\>[\n]{0,1}\*\*\*.+([a-z0-9]{32}).+\*\*\*$/', $message, $matches))
            return $matches[1];

        return NULL;
    }

    /**
     * Function adds to the post comment Cleantalk.ru
     * @param $message
     * @param $comment
     * @return string
     */
    public function addCleantalkComment($message, $comment) {
        $comment = preg_match('/\*\*\*(.+)\*\*\*/', $comment, $matches) ? $comment : '*** ' . $comment . ' ***';
        return $message . "\n\n" . $comment;
    }

    /**
     * Function deletes the comment Cleantalk.ru
     * @param $message
     * @return mixed
     */
    public function delCleantalkComment($message) {
        $message = preg_replace('/\n\n\*\*\*.+\*\*\*$/', '', $message);
        $message = preg_replace('/\<br.*\>[\n]{0,1}\<br.*\>[\n]{0,1}\*\*\*.+\*\*\*$/', '', $message);
        return $message;
    }

}
?>
