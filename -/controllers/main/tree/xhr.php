<?php namespace std\ui\data\controllers\main\tree;

class Xhr extends \std\ui\data\controllers\InstanceController
{
    public $allow = self::XHR;

    public function togglePath()
    {
        $s = &$this->s('~|');

        toggle($s['toggled_paths'], $this->data('path'));

        $this->c('<<:reload|');
    }

    public function add()
    {
        $data = $this->read();

        $data[] = '';

        $this->write($data);

        $this->c('<<:reload|');
    }

    public function toggleBoolValue()
    {
        $data = $this->read();

        $node = &ap($data, $this->data('path'));
        invert($node);

        $this->write($data);

        $this->c('<<:reload|');
    }

    public function updateStringValue()
    {
        $data = $this->read();

        $value = $this->data('value');

        $node = &ap($data, $this->data('path'));
        $node = $value;

        $this->write($data);

        $this->app->response->send($value);
    }

    public function rearrange()
    {
        if ($sequence = $this->data('sequence')) {
            $map = [];
            foreach ($sequence as $nodePath) {
                $map[] = path_slice($nodePath, -1);
            }

            $parentNodePath = path_slice($sequence[0], 0, -1);
            $parentNode = $this->readNode($parentNodePath);

            $parentNode = map($parentNode, $map);

            $this->writeNode($parentNodePath, $parentNode);

            $this->c('<<:reload|');
        }
    }

    public function contextmenu()
    {
        $this->c('\std\ui contextmenu:show', [
            'selector'     => $this->_selector('<:|') . " tr.index[node_path='" . $this->data('path') . "']",
            'content_call' => $this->_abs('@nodeContextmenu:view|', [
                'node_path' => $this->data('path')
            ]),
            'zindex'       => 15000,//$this->contextmenuZIndex // todo
        ]);
    }
}
