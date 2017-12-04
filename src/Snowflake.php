<?php
/**
 * @authors Leon Peng (leon.peng@live.com)
 * @date    2016-08-24 16:58:02
 *
 * @version $Id$
 */
namespace Snowflake;

class Snowflake
{
    const EPOCH = 1512292761628;
    const NUMWORKERBITS = 10;
    const NUMSEQUENCEBITS = 12;
    const MAXWORKERID = 1023;
    const MAXSEQUENCE = 4095;

    private $_lastTimestamp;
    private $_sequence = 0;
    private $_workerId = 1;
    private static $instance = null;

    public static function getInstance($workerId)
    {
        if (is_null(self::$instance) || !isset(self::$instance[$workerId])) {
            self::$instance[$workerId] = new self($workerId);
        }

        return self::$instance[$workerId];
    }

    private function __construct($workerId)
    {
        if (($workerId < 0) || ($workerId > self::MAXWORKERID)) {
            $workerId = 1;
        }
        $this->_workerId = $workerId;
    }

    public function nextId()
    {
        $ts = $this->timestamp();
        if ($ts == $this->_lastTimestamp) {
            $this->_sequence = ($this->_sequence + 1) & self::MAXSEQUENCE;
            if ($this->_sequence == 0) {
                $ts = $this->waitNextMilli($ts);
            }
        } else {
            $this->_sequence = 0;
        }
        // Clock moved backwards!!!
        if ($ts < $this->_lastTimestamp) {
            return 0;
        }
        $this->_lastTimestamp = $ts;

        $nextId = ($this->_lastTimestamp << (self::NUMWORKERBITS + self::NUMSEQUENCEBITS))
            | ($this->_workerId << self::NUMSEQUENCEBITS)
            | $this->_sequence;
        return $nextId;
    }

    private function waitNextMilli($ts)
    {
        $timestamp = $this->timestamp();
        while ($timestamp <= $ts) {
            $timestamp = $this->timestamp();
        }
        return $timestamp;
    }

    private function timestamp()
    {
        return $this->millitime() - self::EPOCH;
    }

    private function millitime()
    {
        $times = explode(' ', microtime());
        return sprintf('%d%03d', $times[1], $times[0] * 1000);
    }

    private function __clone() {}
}
