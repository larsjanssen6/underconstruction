<?php

namespace LarsJanssen\UnderConstruction;

use Exception;

class TransFormer
{
    public function transform($replace, $message)
    {
        if (strpos($message, '%i') === false) {
            throw new Exception('Make sure %i is in the sense');
        }

        if(1 === preg_match('~[0-9]~', $message)) {
            throw new Exception('Make sure the sense does not contain numbers');
        }

        return str_replace('%i', $replace, $message);
    }
}
