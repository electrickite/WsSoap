WsSoap
======

Extends the PHP SOAP client to add basic WSSE support. The library makes as few 
modifications to the base SOAP client class as possible and attempts to make 
authenticating to a service as easy as possible.

Only a subset of the web services security standard is implemented. 
Specifically, unencrypted plain text password authentication.

Installation
------------

You can add the library to your project using [Composer][1] or by manually 
including the necessary files.

### Composer ###

First, if you haven't already installed Composer, install it with

    $ cd my-awesome-project
    $ curl -sS https://getcomposer.org/installer | php

Then create a `composer.json` file with the following contents:

    {
        "require": {
            "osucomm/ws-soap": "~0.1"
        }
    }

And install the libarary:

    $ php composer.phar install

Finally, you'll need to include Composer's autoloader somewhere in your project:

    require 'vendor/autoload.php';

If you prefer to use an autoloader other than Composer, note that WsSoap is 
PSR-4 compliant.

### Include Manually ###

Download or clone the library to a directory within your project and include  
`src/Client.php` somehwere in your project.

    require '/path/to/WsSoap/src/Client.php';

Use
---

Creating a WSSE SOAP client is as simple as

    $wsdl = "https://example.com/MY_WSDL.wsdl";
    $username = 'USER';
    $password = 'PASS';

    $client = new WsSoap\Client($wsdl, array(
        'wsUsername' => $username,
        'wsPassword' => $password,
    ));

In addition to the required `wsUsername` and `wsPassword` keys, the constructor 
options are identical to the native [PHP SoapClient][2].

Once the client is instantiated, it can be used in exactly the same way as 
SoapClient.


[1]: https://getcomposer.org
[2]: http://www.php.net/manual/en/class.soapclient.php
