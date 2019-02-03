<?php namespace std\ui\data\controllers;

class Buffer extends \Controller
{
    public function addArray()
    {
        $this->add($this->data);
    }

    public function addScalar()
    {
        $this->add($this->data['value']);
    }

    private function add($value)
    {
        $s = &$this->s();

        array_unshift($s, $value);

        if (count($s) > 5) {
            array_pop($s);
        }
    }

    public function get($number = 0)
    {
        return $this->s(':' . $number);
    }
}
