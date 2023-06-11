<?php

namespace Fowitech\Sms\Drivers;

use Exception;

class Jetsms extends Driver
{
    private $baseUrl = 'https://www.api.jetsms.com.tr/SMS-Web/xmlsms/';

    public function __construct($options = [])
    {
        $this->sender = config('sms.jetsms.sender');
        $this->username = config('sms.jetsms.username');
        $this->password = config('sms.jetsms.password');
        $this->client = $this->getInstance();
    }

    public function send($options = [])
    {

	$xml = '<?xml version="1.0" encoding="iso-8859-9"?><message-context type="smmgsd"><username>'.$this->username.'</username><password>'.$this->password.'</password><outbox-name>'.$this->sender.'</outbox-name><reference>referance</reference><start-date></start-date><expire-date></expire-date><text>'.$this->text.'</text><message><gsmnos>'.$this->recipients[0].'</gsmnos></message></message-context>';

        try {
            $response = $this->client->request('POST', $this->baseUrl, [
                'timeout' => 100,
                'verify' => false,
                'headers' => [
                    'Content-Type' => 'text/xml; charset=UTF8'
                ],
                'body' => $xml
            ]);

            $contents = explode(' ', $response->getBody()->getContents());
            if ($contents[0] == 00) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $exception) {
            return false;
        }
    }
}
