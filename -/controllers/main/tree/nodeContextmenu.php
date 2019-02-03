<?php namespace std\ui\data\controllers\main\tree;

class NodeContextmenu extends \std\ui\data\controllers\InstanceController
{
    public function reload()
    {
        $this->jquery('|')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v('|');
        $s = $this->s(false, [
            'ignore_keys' => false
        ]);

        $nodePath = $this->data('node_path');

        $index = path_slice($nodePath, -1);

        $v->assign([
                       'INDEX'                     => path_slice($nodePath, -1),
                       'IGNORE_KEYS_TOGGLE_BUTTON' => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleIgnoreKeys|',
                           'class'   => 'ignore_keys_toggle_button ' . ($s['ignore_keys'] ? 'enabled' : ''),
                           'content' => 'ignore keys'
                       ])
                   ]);

        if ($functions = dataSets()->get('std/dataEditor::functions')) {
            foreach ($functions as $fn) {
                $v->assign('fn', [
                    'NAME' => $fn
                ]);
            }
        }

        $this->css();

        $parentNode = $this->readNode(path_slice($nodePath, 0, -1));
        $usedIndexes = array_keys(unmap($parentNode, $index));

        $this->widget(':|', [
            'paths'       => [
                'performAction' => $this->_p('>xhr:performAction|'),
                'applyFn'       => $this->_p('>xhr:applyFn|'),
                'updateIndex'   => $this->_p('>xhr:updateIndex|'),
                'updateType'    => $this->_p('>xhr:updateType|'),
                'setBuffer'     => $this->_p('>xhr:setBuffer|')
            ],
            'usedIndexes' => $usedIndexes,
            'nodePath'    => $nodePath,
            'index'       => $index,
            'valueType'   => $this->getValueType($parentNode[$index]),
            'exportData'  => [
                'level' => j_($this->readNode(path_slice($nodePath, 0, -1))),
                'node'  => j_([$index => $this->readNode($nodePath)]),
                'value' => j_($this->readNode($nodePath)),
                'index' => j_($index)
            ]
        ]);

        return $v;
    }

    private function getValueType($value)
    {
        if (is_array($value)) {
            return 'array';
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif (is_null($value)) {
            return 'null';
        } else {
            return 'string';
        }
    }
}
