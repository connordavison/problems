<?php

class Map
{
    protected $keys = array();
    protected $values = array();
    protected $strict = false;

    /**
     * @param mixed $k
     * @return bool True if this map has key $k.
     */
    public function has($k)
    {
        return $this->index($k) !== false;
    }

    /**
     * Return the index of key $k, or false if $k is not found in keylist.
     *
     * @param mixed $k
     * @return int|bool The index of $k or falseon failure.
     */
    public function index($k)
    {
        foreach ($this->keys as $index => $key) {
            if ($this->equals($key, $k)) {
                return $index;
            }
        }

        return false;
    }

    /**
     * Get the keys in this map.
     *
     * @return mixed[]
     */
    public function keys()
    {
        return $this->keys;
    }

    /**
     * Insert a value at key.
     *
     * @param mixed $k
     * @param mixed $v
     * @return void
     */
    public function put($k, $v)
    {
        if ($i = $this->index($k)) {
            $this->values[$i] = $v;
        } else {
            $this->keys[] = $k;
            $this->values[] = $v;
        }
    }

    /**
     * Retrieve value at key.
     *
     * @param mixed $k
     * @param mixed $v
     * @return mixed The value at $k or null on failure.
     */
    public function get($k)
    {
        if ($i = $this->index($k)) {
            return $this->values[$i];
        }

        return null;
    }

    /**
     * Compare two keys.
     *
     * @param mixed $a
     * @param mixed $b
     * @see $strict
     * @return bool True if equal.
     */
    public function equals($a, $b) {
        if ($this->strict) {
            return $a === $b;
        } else {
            return $a == $b;
        }
    }
}

/**
 * Calculate the expected number of trials required to have seen each item
 * $n in $n, $s times.
 *
 * @param $n The items.
 * @param $s The number of sets.
 * @return double
 */
function solve($n, $s)
{
    $state = array_fill(0, $n, 0);

    $completed_substates = new Map;

    return branch($n, $s, $state, $completed_substates, 0);
}

/**
 * Calculate the expected number of trials required from a particular state
 * to have seen each item $n in $n
 *
 * @param $n The items.
 * @param S The number of sets.
 * @param state The state such that the state[i] is the number of item i
 * already received.
 * @return double The expected number of trials from this frame and all
 * subframes.
 */
function branch($n, $s, $state, $completed_substates)
{
    $remaining = 0;
    $expecteds = 0;

    $substates = [];
    $counts = [];

    for ($i = 0; $i < $n; $i++) {
        if ($state[$i] < $s) {
            $remaining++;

            $substate = $state;
            $substate[$i]++;

            sort($substate);

            $key = array_search($substate, $substates);

            if (false !== $key) {
                $counts[$key]++;
            } else {
                $substates[] = $substate;
                $counts[] = 1;
            }
        }
    }

    foreach ($substates as $i => $substate) {
        $subvalue = $completed_substates->get($substate);

        if ($subvalue === null) {
            $subvalue = branch($n, $s, $substate, $completed_substates);
            $completed_substates->put($substate, $subvalue);
        }

        $expecteds += $counts[$i] * $subvalue;
    }

    if (0 == $remaining) {
        return 0;
    } else {
        return ($expecteds + $n) / $remaining;
    }
}

$resultsets = [];

$n_max = $argv[1];
$s_max = $argv[2];

for ($i = 1; $i <= $n_max; $i++) {
    $resultset = [];

    for ($j = 1; $j <= $s_max; $j++) {
        $percent_complete = 100 * (($i - 1) * $n_max +  $j) / ($n_max * $s_max);
        $percent_complete = number_format($percent_complete, 2);

        echo "(N=$i,S=$j) $percent_complete%\r";
        $resultset[$j] = solve($i, $j);
    }

    $resultsets[$i] = $resultset;
}
echo "\n";

foreach ($resultsets as $resultset) {
    echo implode(", ", $resultset) . "\n";
}