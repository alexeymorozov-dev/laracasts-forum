<?php

namespace App\Utilities;

use Exception;

class Spam
{
    public function detect($body)
    {
        $this->detectInvalidKeywords($body);

        return false;
    }

    public function detectInvalidKeywords($body)
    {
        $invalidKeywords = [
            'yahoo customer support'
        ];

        foreach ($invalidKeywords as $keyword) {
            if (strpos($body, $keyword) !== false) {
                throw new Exception('Your reply contains spam.');
            }
        }
    }
}
