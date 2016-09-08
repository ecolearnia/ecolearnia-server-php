<?php
namespace App\Ecofy\Support;

use DateTime;

class ServiceException extends Exception
{
    /** @type Object { component, domain, reason } */
    private $details;
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
	}

    public setDetails($component, $reason, $domain = 'global')
    {
        $this->details = [
            'component' => $component,
            'reason' => $reason,
            'domain' => $domain
        ]
    }

}
