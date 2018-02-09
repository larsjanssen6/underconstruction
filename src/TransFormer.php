<?php

namespace LarsJanssen\UnderConstruction;

use Exception;

class TransFormer
{
    /**
     * @param $replace
     * @param $message
     * @return mixed
     * @throws Exception
     */
    public function transform($replace, $message)
    {
        if (strpos($message, '%i') === false) {
            throw new Exception('Make sure %i in your config file is in the sense');
        }

        if (1 === preg_match('~[0-9]~', $message)) {
            throw new Exception('Make sure the sense in your config file does not contain numbers');
        }

        return str_replace('%i', $replace, $message);
    }
}
