<?php

namespace SseClient;

class Client
{
    const END_OF_MESSAGE = "/\r\n\r\n|\n\n|\r\r/";

    private $buffer = '';
    private $callback;

    public function __construct($callback) {
        $this->callback = $callback;
    }

    public function ingest($data) {
        $datalen = strlen($data);
        for ($pos=0;$pos<=$datalen;$pos++){
            $this->buffer .= substr($data, $pos, 1);
            if (preg_match(self::END_OF_MESSAGE,   $this->buffer)) {
                $parts = preg_split(self::END_OF_MESSAGE, $this->buffer, 2);

                $rawMessage = $parts[0];
                $remaining = $parts[1];

                $this->buffer = $remaining;
                $callback = $this->callback;
                $callback(Event::parse($rawMessage));
            }
        }
        return $datalen;
    }
}
