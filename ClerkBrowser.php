<?php
class ClerkBrowser
{
    private $headers = array(
        'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language: en-us,en;q=0.5',
        //'Accept-Encoding: gzip,deflate',
        'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
        'Keep-Alive: 115',
        'Connection: keep-alive',
    );

    public static $hideDevMode = true;
    public static $browser = array();

    public $lastPageRequested = '';
    public $lastPageResponse = '';
    public $lastResponseCode = '';
    public $lastErrorCode = '';
    public $lastErrorMsg = '';
    public $autoFollowRedirection = true;
    public $lastReponseHeaders = array();

    public function reset($p_name=false)
    {
        if($p_name)
        {
            unset(ClerkBrowser::$browser[$p_name]);
        }
        else
        {
            ClerkBrowser::$browser = array();
        }
    }

    private function buildBrowser($p_name='default')
    {
        $this->lastReponseHeaders = array();
        $this->lastPageRequested = '';
        $this->lastPageResponse = '';
        $this->lastResponseCode = '';
        $this->lastErrorCode = '';
        $this->lastErrorMsg = '';
        if(!isset(self::$browser[$p_name]))
        {
            self::$browser[$p_name] = tempnam('/tmp','pipes_cookie_');
        }
    }

    public function getSessionId($p_name='default')
    {
        $session_id = false;

        //get session id from cookie
        $text = $this->getCookie($p_name);
        $text = str_replace("\r\n","\n",$text);
        $lines = explode("\n", $text);
        $trigger = false;
        foreach($lines as $line)
        {
            switch(substr($line,0,1))
            {
                case ' ':
                    break;
                case '#':
                    break;
                default:
                    $tabs = explode("\t", $line);
                    foreach($tabs as $tab)
                    {
                        if($trigger)
                        {
                            $session_id = trim($tab);
                            $trigger = false;
                        }
                        if(trim($tab)=='CraftSessionId')
                        {
                            $trigger = true;
                        }
                    }
                    break;
            }
        }
        return $session_id;
    }

    public function isError()
    {
        $ret = false;
        if($this->lastResponseCode > 499)
        {
            $ret = true;
        }
        return $ret;
    }
    
    public function followRedirection()
    {
        $ret = false;

        //take last Location header if 301 or 302 and return that data
        if($this->lastResponseCode==301 || $this->lastResponseCode==302)
        {
            if($next_url = $this->getResponseHeader('Location'))
            {
                $ret = $this->get($next_url);
            }
        }

        return $ret;
    }

    public function getCookie($p_name='default')
    {
        if(isset(self::$browser[$p_name]))
        {
            return file_get_contents(self::$browser[$p_name]);
        }
        else
        {
            return false;
        }
    }

    public function getResponseHeader($p_header_name)
    {
        $ret = null;
        if(isset($this->lastReponseHeaders[$p_header_name]))
        {
            $ret = $this->lastReponseHeaders[$p_header_name];
        }

        return $ret;
    }

    //TODO make use of session somehow
    public function get($p_uri, $p_name='default', $p_json=false, $p_session=null, $p_headers=array())
    {
        $this->buildBrowser($p_name);

        $url = $p_uri;
        if(!stristr($url, 'http:') && !stristr($url, 'https:'))
        {
            $url = 'https://lcr.churchofjesuschrist.org'.$url;
        }
        if(!stristr($url, 'http:') && !stristr($url, 'https:'))
        {
            if(substr($url, 0, 2)!='//')
            {
                $url = "//".$url;
            }
            $url = "http:".$url;
        }
        $this->lastPageRequested = $url;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array_merge($this->headers, $p_headers));
        //curl_setopt($curl, CURLOPT_USERAGENT, 'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12');
        curl_setopt($curl, CURLOPT_COOKIEJAR, self::$browser[$p_name]);
        curl_setopt($curl, CURLOPT_COOKIEFILE, self::$browser[$p_name]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if($this->autoFollowRedirection)
        {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        }
        else
        {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        $raw_response = curl_exec($curl);

        $this->lastResponseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $this->lastPageResponse = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);

        $this->lastErrorMsg = '';
        $this->lastErrorCode = '';
        if($this->lastResponseCode > 299)
        {
            $this->lastErrorMsg = curl_error($curl);
            $this->lastErrorCode = curl_errno($curl);
        }

        $headers = substr($raw_response, 0, $header_size);
        $headers_arr = explode("\r\n", $headers);
        foreach($headers_arr as $header_txt)
        {
            if(!stristr($header_txt, ':'))
            {
                continue;
            }
            $header = '';
            $value = '';
            $header_pieces = explode(':', $header_txt);
            if(count($header_pieces)==2)
            {
                $header = trim($header_pieces[0]);
                $value = trim($header_pieces[1]);
            }
            elseif(count($header_pieces)==1)
            {
                $header = trim($header_pieces[0]);
            }
            else
            {
                $header = trim($header_pieces[0]);
                unset($header_pieces[0]);
                $value = trim(implode(':', $header_pieces));
            }
            $this->lastReponseHeaders[$header] = $value;
        }
        $content = substr($raw_response, $header_size);

        if(self::$hideDevMode && !$p_json)
        {
            $content = substr($content, 0, strpos($content, '</html>')+7);
        }
        return $content;
    }

    public function ajax($p_uri, $p_params, $p_name='default')
    {
        return $this->post($p_uri, $p_params, $p_name, true);
    }

    //TODO post body, make use of session somehow
    public function post($p_uri, $p_params, $p_name='default', $p_ajax=false, $p_headers=array(), $p_session=null, $p_body=false)
    {
        $this->buildBrowser($p_name);

        $param_string = '';
        $glue = '';
        if(is_array($p_params))
        {
            foreach($p_params as $param=>$value)
            {
                $param_string = $param_string.$glue.$param."=".urlencode($value);
                $glue = '&';
            }
        }
        else
        {
            $param_string = $p_params;
        }
        $url = $p_uri;
        if(!stristr($url, 'http:') && !stristr($url, 'https:'))
        {
            $url = 'https://lcr.churchofjesuschrist.org'.$url;
        }
        if(!stristr($url, 'http:') && !stristr($url, 'https:'))
        {
            if(substr($url, 0, 2)!='//')
            {
                $url = "//".$url;
            }
            $url = "http:".$url;
        }

        $new_headers = array('Content-Type: application/x-www-form-urlencoded');
        if($p_ajax)
        {
            //$new_headers[] = "X-Requested-With: XMLHttpRequest";
            $new_headers = $p_headers;
        }
        else
        {
            $new_headers = array_merge($this->headers, $new_headers, $p_headers);
        }
        $this->lastPageRequested = $url;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        //curl_setopt($curl, CURLOPT_USERAGENT, 'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12');
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if($this->autoFollowRedirection)
        {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        }
        else
        {
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        }
        curl_setopt($curl, CURLOPT_COOKIEJAR, self::$browser[$p_name]);
        curl_setopt($curl, CURLOPT_COOKIEFILE, self::$browser[$p_name]);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $param_string);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $new_headers);
        
        $raw_response = curl_exec($curl);
        
        $this->lastResponseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $this->lastPageResponse = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
        
        $this->lastErrorMsg = '';
        $this->lastErrorCode = '';
        if($this->lastResponseCode > 299)
        {
            $this->lastErrorMsg = curl_error($curl);
            $this->lastErrorCode = curl_errno($curl);
        }
        
        $headers = substr($raw_response, 0, $header_size);
        $headers_arr = explode("\r\n", $headers);
        foreach($headers_arr as $header_txt)
        {
            if(!stristr($header_txt, ':'))
            {
                continue;
            }
            $header = '';
            $value = '';
            $header_pieces = explode(':', $header_txt);
            if(count($header_pieces)==2)
            {
                $header = trim($header_pieces[0]);
                $value = trim($header_pieces[1]);
            }
            elseif(count($header_pieces)==1)
            {
                $header = trim($header_pieces[0]);
            }
            else
            {
                $header = trim($header_pieces[0]);
                unset($header_pieces[0]);
                $value = trim(implode(':', $header_pieces));
            }
            $this->lastReponseHeaders[$header] = $value;
        }
        $content = substr($raw_response, $header_size);
        
        if(self::$hideDevMode)
        {
            if($strpo = strpos($content, 'console.groupCollapsed("Application Log");'))
            {
                $content = substr($content, 0, $strpo-134);
            }
        }
        return $content;
    }

    public function parseCanonicalURL($response)
    {
        preg_match('/(<link rel="canonical"){1}.+?(>){1}/', $response, $canonical);
        $canonical = $canonical[0];
        $canonical = substr($canonical, strpos($canonical, 'href=')+6);
        if(stristr($canonical, '"'))
        {
            $canonical = substr($canonical, 0, strpos($canonical, '"'));
        }
        elseif(stristr($canonical, "'"))
        {
            $canonical = substr($canonical, 0, strpos($canonical, "'"));
        }
        if(stristr($canonical, '//'))
        {
            $canonical = substr($canonical, strpos($canonical, '//')+2);
            $canonical = substr($canonical, strpos($canonical, '/')+1);
        }
        return $canonical;
    }
}