<?php namespace std\ui\data\controllers\main;

class Tree extends \std\ui\data\controllers\InstanceController
{
    private $tree;

    public function view()
    {
        $this->tree = $this->read();

        $this->css()->import('>themes/gray');

        $this->widget(':|', [
            'paths' => [
                'togglePath'        => $this->_p('>xhr:togglePath|'),
                'contextmenu'       => $this->_p('>xhr:contextmenu|'),
                'toggleBoolValue'   => $this->_p('>xhr:toggleBoolValue|'),
                'updateStringValue' => $this->_p('>xhr:updateStringValue|'),
            ]
        ]);

        return $this->treeView();
    }

    private function treeView()
    {
        return $this->treeViewRecursion();
    }

    private $path = [];

    private $level = 0;

    private function treeViewRecursion()
    {
        $v = $this->v('|');

        $subnodes = ap($this->tree, $this->path);

        if ($subnodes) {
            $i = 0;

            foreach ($subnodes as $index => $value) {
                $nodePath = a2p($this->path);

                $hasNested = is_array($value);

                $path = path($nodePath, $index);

                $v->assign('node', [
                    'LEVEL'       => $this->level,
                    'PATH'        => $path,
                    'LEVEL_CLASS' => 'level' . ($this->level % 2 + 1),
                    'EVEN_CLASS'  => $i % 2 == 0 ? 'even' : '',
                    'CLASS'       => $this->getValueClass($value),
                    'INDEX'       => $index
                ]);

                if ($hasNested) {
                    $nestedExpand = $this->expand ? !in($path, $this->toggledPaths) : in($path, $this->toggledPaths);

                    if ($nestedExpand) {
                        if ($value) {
                            $this->level++;
                            $this->path[] = $index;

                            $v->assign('node/value', [
                                'CONTENT' => $this->treeViewRecursion()
                            ]);

                            array_pop($this->path);
                            $this->level--;
                        }

                        $v->append('node', [
                            'CONTENT' => ''
                        ]);
                    }

                    $v->append('node', [
                        'EXPAND_CLASS' => $nestedExpand ? 'expand' : ''
                    ]);

                    $v->assign('node/expand_icon');
                } else {
                    if (is_bool($value)) {
                        $value = $value ? 'true' : 'false';
                    }

                    if (is_null($value)) {
                        $value = 'null';
                    }

                    $v->append('node', [
                        'CONTENT' => $value
                    ]);
                }

                $this->c('\std\ui sortable:bind', [
                    'selector'       => $this->_selector('|') . " > table",
                    'items_id_attr'  => 'node_path',
                    'path'           => '>xhr:rearrange|',
                    'plugin_options' => [
                        'distance' => 20,
                        'axis'     => 'y',
                        'items'    => '> tbody > tr.sortable'
                    ]
                ]);

                $i++;
            }
        } else {
            $v->assign('ADD_BUTTON', $this->c('\std\ui button:view', [
                'path'    => '>xhr:add|',
                'class'   => 'add_button',
                'content' => 'add'
            ]));
        }

        return $v;
    }

    private function getValueClass($value)
    {
        $class = [];

        if (is_array($value)) {
            $class[] = $value ? 'array' : 'array empty';
        } else {
            if (is_scalar($value)) {
                $class[] = 'scalar';

                is_bool($value) && $class[] = 'bool ' . ($value ? 'true' : 'false');
                is_string($value) && $class[] = 'string';
                is_numeric($value) && $class[] = 'number';
            }

            if (is_null($value)) {
                $class[] = 'null';
            }
        }

        return implode(' ', $class);
    }
}
