<?php

namespace Objects;
class GigiRateLimit
{
    private $core_limit = null;
    private $core_remaining = null;
    private $core_reset = null;

    private $search_limit = null;
    private $search_remaining = null;
    private $search_reset = null;

    private $graphql_limit = null;
    private $graphql_remaining = null;
    private $graphql_reset = null;

    private $rate_limit = null;
    private $rate_remaining = null;
    private $rate_reset = null;

    public function __construct($cl,$cr,$crt,$sl,$sr,$srt,$qql,$qqr,$qqrt,$rl,$rr,$rrt)
    {
        $this->core_limit = $cl;
        $this->core_remaining = $cr;
        $this->core_reset = $crt;

        $this->search_limit = $sl;
        $this->search_remaining = $sr;
        $this->search_reset = $srt;

        $this->graphql_limit = $qql;
        $this->graphql_remaining = $qqr;
        $this->graphql_reset = $qqrt;

        $this->rate_limit = $rl;
        $this->rate_remaining = $rr;
        $this->rate_reset = $rrt;
    }

    public function CoreLimit()
    {
        return isset($this->core_limit) ? $this->core_limit : 'Undefined';
    }

    public function CoreRemaining()
    {
        return isset($this->core_remaining) ? $this->core_remaining : 'Undefined';
    }

    public function CoreReset()
    {
        return isset($this->core_reset) ? gmdate("Y-m-d\TH:i:s\Z", $this->core_reset) : 'Undefined';
    }

    public function SearchLimit()
    {
        return isset($this->search_limit) ? $this->search_limit : 'Undefined';
    }

    public function SearchRemaining()
    {
        return isset($this->search_remaining) ? $this->search_remaining : 'Undefined';
    }
    
    public function SearchReset()
    {
        return isset($this->search_reset) ? gmdate("Y-m-d\TH:i:s\Z", $this->search_reset) : 'Undefined';
    }

    public function GraphQL_Limit()
    {
        return isset($this->graphql_limit) ? $this->graphql_limit : 'Undefined';
    }

    public function GraphQL_Remaining()
    {
        return isset($this->graphql_remaining) ? $this->graphql_remaining : 'Undefined';
    }
    
    public function GraphQL_Reset()
    {
        return isset($this->graphql_reset) ? gmdate("Y-m-d\TH:i:s\Z", $this->graphql_reset) : 'Undefined';
    }

    public function RateLimit()
    {
        return isset($this->rate_limit) ? $this->rate_limit : 'Undefined';
    }

    public function RateRemaining()
    {
        return isset($this->rate_remaining) ? $this->rate_remaining : 'Undefined';
    }

    public function RateReset()
    {
        return isset($this->rate_reset) ? gmdate("Y-m-d\TH:i:s\Z", $this->rate_reset) : 'Undefined';
    }
}
