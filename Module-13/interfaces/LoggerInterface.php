<?php

interface LoggerInterface {

    public function logMessage($str);

    public function lastMessages($number);

}
