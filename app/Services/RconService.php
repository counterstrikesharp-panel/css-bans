<?php

namespace App\Services;

use xPaw\SourceQuery\SourceQuery;

class RconService
{
    protected $rcon;

    public function __construct()
    {
        // Constructor logic, if any
    }

    public function connect($host, $port, $timeout = 3, $engine = SourceQuery::SOURCE)
    {
        $this->rcon = new SourceQuery();
        $this->rcon->Connect($host, $port, $timeout, $engine);
    }

    public function disconnect()
    {
        if ($this->rcon) {
            $this->rcon->Disconnect();
            unset($this->rcon);
        }
    }

    public function ping()
    {
        return $this->rcon->Ping();
    }

    public function getInfo()
    {
        return $this->rcon->GetInfo();
    }

    public function getPlayers()
    {
        return $this->rcon->GetPlayers();
    }

    public function getRules()
    {
        return $this->rcon->GetRules();
    }

    public function setRconPassword($password)
    {
        return $this->rcon->SetRconPassword($password);
    }

    public function rcon($command)
    {
        return $this->rcon->Rcon($command);
    }

    // Additional methods as needed...
}
