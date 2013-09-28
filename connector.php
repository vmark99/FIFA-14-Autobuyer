<?PHP

class Connector
{

	//TODO
	// Make everything have it's own method. 
	// Finish!
    
    private $user;
    private $password;
    private $hash;
    private $getmachine;
    
    //initialise the class
    public function __construct($user, $password, $hash)
    {
        $this->user       = $user;
        $this->password   = $password;
        $this->hash       = $hash;
    }

    public function xml_to_array($root) {
	    $result = array();

	    if ($root->hasAttributes()) {
	        $attrs = $root->attributes;
	        foreach ($attrs as $attr) {
	            $result['@attributes'][$attr->name] = $attr->value;
	        }
	    }

	    if ($root->hasChildNodes()) {
	        $children = $root->childNodes;
	        if ($children->length == 1) {
	            $child = $children->item(0);
	            if ($child->nodeType == XML_TEXT_NODE) {
	                $result['_value'] = $child->nodeValue;
	                return count($result) == 1
	                    ? $result['_value']
	                    : $result;
	            }
	        }
	        $groups = array();
	        foreach ($children as $child) {
	            if (!isset($result[$child->nodeName])) {
	                $result[$child->nodeName] = $this->xml_to_array($child);
	            } else {
	                if (!isset($groups[$child->nodeName])) {
	                    $result[$child->nodeName] = array($result[$child->nodeName]);
	                    $groups[$child->nodeName] = 1;
	                }
	                $result[$child->nodeName][] = $this->xml_to_array($child);
	            }
	        }
	    }

	    return $result;
	}
    
    public function connect()
    {
        //URI
        $home	   = "https://www.easports.com/services/authenticate/login";
        $shards    = "http://www.easports.com/iframe/fut/p/ut/shards?_=".time();
        $accountinfo = "http://www.easports.com/iframe/fut/p/ut/game/fifa14/user/accountinfo?_=".time();
        $auth      = "http://www.easports.com/iframe/fut/p/ut/auth";

        // Logging in
        $data_string = "loginSource=overlay&email=".$this->user."&password=".$this->password."&overlay-stay-signed=ON";

        $ch = curl_init($home);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.76 Safari/537.36");
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate,sdch");
        curl_setopt($ch, CURLOPT_POST, 1);                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/x-www-form-urlencoded',                                                                                
			'Content-Length: ' . strlen($data_string),
			'Referer: http://www.easports.com/')                                                                   
		); 


        $response = curl_exec($ch);

        echo "<h3>Home</h3>";
		echo "<pre>";
        print_r($response);
        echo "</pre>";

        curl_close($ch);

        $dom = new DOMDocument();
        $dom->loadXML($response);

        $xmlArray  = $this->xml_to_array($dom);
        $nucleusId = $xmlArray['login']['player']['nucleusId'];


        // shards
        $ch = curl_init($shards);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.76 Safari/537.36");
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate,sdch");                                                                    
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',
			'Easw-Session-Data-Nucleus-Id: 2411412757',
			'X-UT-Embed-Error: true',
			'X-UT-Route: https://utas.fut.ea.com',
			'X-Requested-With: XMLHttpRequest')                                                                                                                                                   
		); 

        $response = json_decode(curl_exec($ch), true);

        echo "<h3>Shards</h3>";
		echo "<pre>";
        print_r($response);
        echo "</pre>";

        curl_close($ch);



        // getUserAccount
        $ch = curl_init($accountinfo);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.76 Safari/537.36");
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate,sdch");                                                                    
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',
			'Easw-Session-Data-Nucleus-Id: '.$nucleusId,
			'X-UT-Embed-Error: true',
			'X-UT-Route: https://utas.fut.ea.com',
			'X-Requested-With: XMLHttpRequest')                                                                                                                                                   
		); 

        $response = json_decode(curl_exec($ch), true);
        $userAccount = $response;

        echo "<h3>Account Info</h3>";
		echo "<pre>";
        print_r($response);
        echo "</pre>";

        curl_close($ch);


        // Session ID 
        echo "<h3>Auth</h3>";       
        $persona = array();
        $lastAccessTime = array();
        foreach ($userAccount['userAccountInfo']['personas'][0]['userClubList'] as $key) {
        	$persona[] = $key;
        }

        foreach ($persona as $key) {
        	$lastAccessTime[] = $key['lastAccessTime'];
        }

        $latestAccessTime = max($lastAccessTime);
        $lastUsedPersona  = $persona[array_search($latestAccessTime, $lastAccessTime)];

        $data = array('isReadOnly' => false, "sku" => "FUT14WEB", "clientVersion" => 1, "nuc" => $nucleusId, "nucleusPersonaId" => $userAccount['userAccountInfo']['personas'][0]['personaId'], "nucleusPersonaDisplayName" => $userAccount['userAccountInfo']['personas'][0]['personaName'], "nucleusPersonaPlatform" => "360", "locale" => "en-GB", "method" => "authcode", "priorityLevel" => 4, "identification" => '{ "authCode": "" }');
        $data_string = json_encode($data);
        $data_string = '{ "isReadOnly": false, "sku": "FUT14WEB", "clientVersion": 1, "nuc": 2411412757, "nucleusPersonaId": 352196208, "nucleusPersonaDisplayName": "iPSQ", "nucleusPersonaPlatform": "360", "locale": "nl-NL", "method": "authcode", "priorityLevel":4, "identification": { "authCode": "" } }';

        $ch = curl_init($auth);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.76 Safari/537.36");
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate,sdch");
        curl_setopt($ch, CURLOPT_POST, 1);
  		curl_setopt($ch, CURLOPT_HEADER, 1);                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Easw-Session-Data-Nucleus-Id: '.$nucleusId,
			'X-UT-Embed-Error: true',
			// 'X-UT-Route: https://utas.fut.ea.com',
			// 'X-Requested-With: XMLHttpRequest',
			// 'Referer: http://www.easports.com/iframe/fut/?baseShowoffUrl=http%3A%2F%2Fwww.easports.com%2Fnl%2Ffifa%2Ffootball-club%2Fultimate-team%2Fshow-off&guest_app_uri=http%3A%2F%2Fwww.easports.com%2Fnl%2Ffifa%2Ffootball-club%2Fultimate-team&locale=nl_NL',                                                                                 
			'Content-Length: ' . strlen($data_string)
			)                                                                 
		);

		$response = json_decode(curl_exec($ch), true);

		// echo "<h3>Auth</h3>";
		echo "<pre>";
        print_r($response);
        echo "</pre>";

        curl_close($ch); 
    }
    
}
