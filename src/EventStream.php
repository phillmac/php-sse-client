<?php
namespace SseClient;


use Psr\Http\Message\StreamInterface;

class EventStream implements StreamInterface
{
    private $sseClient;
    private $pos = 0;

    public function __construct(callable $callback)
    {
        $this->sseClient = new Client($callback);
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        throw new \RuntimeException('Cannot seek a EventStream');
    }

    public function isSeekable()
    {
        return false;
    }

    public function isReadable()
    {
        return false;
    }
    public function isWritable()
    {
        return false;
    }

    public function read($string)
    {
        throw new \RuntimeException('Cannot read from a EventStream');
    }

    public function write($data)
    {
        $this->pos += strlen($data);
        return $this->sseClient->ingest($data);
    }

    public function rewind()
    {
        $this->seek(0);
    }

    public function __toString()
    {
        return '';
    }

    public function eof()
    {
        return false;
    }

    public function tell()
    {
        return $this->pos;
    }

    public function close()
    {
        $this->detach();
    }

    public function detach()
    {
        $this->sseClient = null;
    }

    public function getSize()
    {
        return $this->pos;
    }

    public function getMetadata($key = null)
    {
        if (!$key) {
            return [];
        }
        return null;
    }

    public function getContents()
    {
        return null;
    }
}

