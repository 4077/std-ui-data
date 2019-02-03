<?php namespace std\ui\data\controllers;

class InstanceController extends \Controller
{
    protected $s;

    public $readCall;

    public $writeCall;

    public $expand;

    public $toggledPaths;

    public $contextmenuZIndex;

    public function __create()
    {
        $this->s = &$this->s('~|');

        if (!$this->s) {
            $this->instanceSessionSetDefaults();
        }

        $this->instanceSessionUpdate(unmap($this->data, 'default'));
        $this->initFromInstanceSession();
    }

    private function instanceSessionSetDefaults()
    {
        ap($this->s, false, [
            'read_call'          => [],
            'write_call'         => [],
            'toggled_paths'      => [],
            'expand'             => false,
            'contextmenu_zindex' => 15000
        ]);

        $this->instanceSessionUpdate($this->data('default'));
    }

    private function instanceSessionUpdate($data)
    {
        remap($this->s, $data, 'read_call, write_call, toggled_paths, expand, contextmenu_zindex');

        if (!empty($data['read_call'])) {
            $this->s['read_call'] = $this->_caller()->_abs($data['read_call']);
        }

        if (!empty($data['write_call'])) {
            $this->s['write_call'] = $this->_caller()->_abs($data['write_call']);
        }
    }

    private function initFromInstanceSession()
    {
        $s = $this->s('~|');

        \ewma\Data\Data::extract($this, $s, '
            readCall            read_call,
            writeCall           write_call,
            expand              expand,
            toggledPaths        toggled_paths,
            contextmenuZIndex   contextmenu_zindex
        ');
    }

    protected function read()
    {
        if ($this->readCall) {
            return $this->_call($this->readCall)->perform();
        } else {
            return [];
        }
    }

    protected function write($input)
    {
        $this->_call($this->writeCall)->ra(['data' => $input])->perform();
    }

    protected function readNode($path)
    {
        $data = $this->read();

        return ap($data, $path);
    }

    protected function writeNode($path, $value)
    {
        $data = $this->read();

        ap($data, $path, $value);

        $this->write($data);
    }
}
