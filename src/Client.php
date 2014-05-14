<?php
/**
 * WsSoap\Client
 *
 * Extends the PHP SoapClient class to add WSSE security headers.
 * Based on: http://www.php.net/manual/en/soapclient.soapclient.php#97273
 */

namespace WsSoap;

class Client extends \SoapClient {
    private $wsUsername;
    private $wsPassword;
    private $wsse;

    public function __construct($wsdl, array $options) {
        if (empty($options['wsUsername']) || empty($options['wsPassword']))
            throw new \InvalidArgumentException('wsUsername and wsPassword must be supplied in the options array.');

        $this->wsUsername = $options['wsUsername'];
        $this->wsPassword = $options['wsPassword'];
        $this->wsse = empty($options['wsse']) ? 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd' : $options['wsse'];

        parent::__construct($wsdl, $options);
        $this->addWsHeader();
    }

    private function addWsHeader() {
        $soapVarUser = new \SoapVar($this->wsUsername, XSD_STRING, null, $this->wsse, null, $this->wsse);
        $soapVarPass = new \SoapVar($this->wsPassword, XSD_STRING, null, $this->wsse, null, $this->wsse);

        $wsseAuth = new WSSEAuth($soapVarUser, $soapVarPass);
        $soapVarWsseAuth = new \SoapVar($wsseAuth, SOAP_ENC_OBJECT, null, $this->wsse, 'UsernameToken', $this->wsse);
        $wsseToken = new WSSEToken($soapVarWsseAuth);
        $soapVarWsseToken = new \SoapVar($wsseToken, SOAP_ENC_OBJECT, null, $this->wsse, 'UsernameToken', $this->wsse);
        $soapVarHeaderVal = new \SoapVar($soapVarWsseToken, SOAP_ENC_OBJECT, null, $this->wsse, 'Security', $this->wsse);

        $wsHeader = new \SoapHeader($this->wsse, 'Security', $soapVarHeaderVal, true);
        $this->__setSoapHeaders(array($wsHeader));
    }
}

class WSSEAuth {
    private $Username;
    private $Password;

    function __construct($username, $password) { 
        $this->Username = $username;
        $this->Password = $password;
    }
}

class WSSEToken {
    private $UsernameToken;

    function __construct ($innerVal) { 
        $this->UsernameToken = $innerVal;
    }
}
