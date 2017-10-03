<?php

namespace LarsJanssen\UnderConstruction;

use Exception;

class TransFormer
{
    public function start($replace, $message)
    {
        if (strpos($message, '%i') === false) {
            throw new Exception('Make sure %i is in the sense');
        }

        return str_replace('%i', $replace, $message);
    }
}
