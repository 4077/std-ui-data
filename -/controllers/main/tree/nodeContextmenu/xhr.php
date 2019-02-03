<?php namespace std\ui\data\controllers\main\tree\nodeContextmenu;

class Xhr extends \std\ui\data\controllers\InstanceController
{
    public $allow = self::XHR;

    public function performAction()
    {
        if ($this->dataHas('action, node_path')) {
            list($method, $mode) = array_pad(p2a($this->data['action']), 2, null);

            if ($method == 'add') {
                $this->add($mode);
            }

            if ($method == 'paste_aa') {
                $this->paste($mode, AA);
            }

            if ($method == 'paste_ra') {
                $this->paste($mode, RA);
            }

            if ($method == 'replace') {
                $this->replace($mode);
            }

            if ($method == 'unset') {
                $this->delete($mode);
            }

            if ($method == 'copy') {
                $this->copy($mode);
            }
        }
    }

    private function delete($mode)
    {
        $nodePathArray = p2a($this->data['node_path']);
        $data = $this->read();

        if ($mode == 'level') {
            ap($data, array_slice($nodePathArray, 0, -1), []);
        }

        if ($mode == 'node') {
            $node = &ap($data, array_slice($nodePathArray, 0, -1));

            unset($node[end($nodePathArray)]);
        }

        if ($mode == 'value') {
            ap($data, $nodePathArray, []);
        }

        $this->write($data);

        $this->reload();
        $this->hideContextmenu();
    }

    private function copy($mode)
    {
        if (in($mode, 'level, node, value, index')) {
            $nodePath = $this->data['node_path'];

            $nodePathArray = p2a($nodePath);
            $nodeData = $this->read();

            if ($mode == 'level') {
                $data = ap($nodeData, path_slice($nodePath, 0, -1));
            }

            if ($mode == 'node') {
                $data = [end($nodePathArray) => ap($nodeData, $nodePath)];
            }

            if ($mode == 'value') {
                $data = ap($nodeData, $nodePath);
            }

            if ($mode == 'index') {
                $data = end($nodePathArray);
            }

            if (isset($data)) {
                if (is_array($data)) {
                    $this->c('buffer:addArray', $data);
                }

                if (is_scalar($data)) {
                    $this->c('buffer:addScalar', ['value' => $data]);
                }
            }

            $this->hideContextmenu();
        }
    }

    private function paste($mode, $mergeMode)
    {
        $nodePath = $this->data['node_path'];
        $bufferData = $this->c('buffer:get');

        if ($mode == 'append') {
            $this->append($nodePath, $bufferData, $mergeMode);
        }

        if ($mode == 'prepend') {
            $this->prepend($nodePath, $bufferData, $mergeMode);
        }

        if (in($mode, 'before, after')) {
            $this->insert($nodePath, $bufferData, $mode, $mergeMode);
        }

        $this->reload();
        $this->hideContextmenu();
    }

    private function append($nodePath, $input, $mergeMode)
    {
        $node = $this->readNode($nodePath);

        if (is_array($node)) {
            if (is_scalar($input)) {
                $node[] = $input;
            }

            if (is_array($input)) {
                $ignoreKeys = $this->s('<:ignore_keys');

                foreach ($input as $index => $value) {
                    if ($ignoreKeys) {
                        $node[] = $value;
                    } else {
                        if ($mergeMode == RA || ($mergeMode == AA && !isset($node[$index]))) {
                            $node[$index] = $value;
                        }
                    }
                }
            }

            $this->writeNode($nodePath, $node);
        }

        $this->reload();
        $this->hideContextmenu();
    }

    private function isAllKeysNumeric($input)
    {
        foreach ($input as $key => $value) {
            if (!is_numeric($key)) {
                return false;
            }
        }

        return true;
    }

    private function prepend($nodePath, $input, $mergeMode)
    {
        $node = $this->readNode($nodePath);

        if (is_array($node)) {
            if (is_scalar($input)) {
                array_unshift($node, $input);
            }

            if (is_array($input)) {
                $nodeOutput = [];

                $ignoreKeys = $this->s('<:ignore_keys');

                foreach ($input as $index => $value) {
                    if ($ignoreKeys) {
                        $nodeOutput[] = $value;
                    } else {
                        if ($mergeMode == RA || ($mergeMode == AA && !isset($node[$index]))) {
                            $nodeOutput[$index] = $value;
                        }
                    }
                }

                foreach ($node as $index => $value) {
                    if ($ignoreKeys) {
                        $nodeOutput[] = $value;
                    } else {
                        if (!isset($nodeOutput[$index])) {
                            $nodeOutput[$index] = $value;
                        }
                    }
                }

                $node = $nodeOutput;
            }

            $this->writeNode($nodePath, $node);
        }
    }

    private function insert($nodePath, $input, $mode, $mergeMode)
    {
        $beforeIndex = path_slice($nodePath, -1);
        $parentNodePath = path_slice($nodePath, 0, -1);

        $node = $this->readNode($parentNodePath);

        if (is_array($node)) {
            $nodeOutput = [];

            $ignoreKeys = $this->s('<:ignore_keys');

            foreach ($node as $index => $data) {
                if ($mode == 'after') {
                    if ($ignoreKeys) {
                        $nodeOutput[] = $data;
                    } else {
                        $nodeOutput[$index] = $data;
                    }
                }

                if ($index == $beforeIndex) {
                    if (is_array($input)) {
                        foreach ($input as $inputIndex => $inputData) {
                            if ($ignoreKeys) {
                                $nodeOutput[] = $inputData;
                            } else {
                                if ($mergeMode == RA || ($mergeMode == AA && !isset($node[$inputIndex]))) {
                                    $nodeOutput[$inputIndex] = $inputData;
                                }
                            }
                        }
                    }

                    if (is_scalar($input)) {
                        $nodeOutput[] = $input;
                    }
                }

                if ($mode == 'before') {
                    if ($ignoreKeys) {
                        $nodeOutput[] = $data;
                    } else {
                        $nodeOutput[$index] = $data;
                    }
                }
            }

            $node = $nodeOutput;

            $this->writeNode($parentNodePath, $node);
        }

        return $node;
    }

    private function replace($mode)
    {
        $nodePath = $this->data['node_path'];
        $bufferData = $this->c('buffer:get');

        $data = $this->read();

        if ($mode == 'index') {
            if (is_scalar($bufferData)) {
                $this->data('value', $bufferData);
                $this->updateIndex();
            }
        } else {
            if ($mode == 'level') {
                ap($data, path_slice($nodePath, 0, -1), $bufferData);
            }

            if ($mode == 'node') {
                $node = &ap($data, path_slice($nodePath, 0, -1));

                $replacingIndex = path_slice($nodePath, -1);

                $unset = true;

                $nodeOutput = [];
                foreach ($node as $index => $value) {
                    $nodeOutput[$index] = $value;

                    if ($index == $replacingIndex) {
                        if (is_array($bufferData)) {
                            foreach ($bufferData as $bufferDataIndex => $bufferDataValue) {
                                $nodeOutput[$bufferDataIndex] = $bufferDataValue;

                                if ($bufferDataIndex == $replacingIndex) {
                                    $unset = false;
                                }
                            }
                        }

                        if (is_scalar($bufferData)) {
                            $nodeOutput[] = $bufferData;
                        }
                    }
                }

                if ($unset) {
                    unset($nodeOutput[$replacingIndex]);
                }

                $node = $nodeOutput;
            }

            if ($mode == 'value') {
                ap($data, $nodePath, $bufferData);
            }

            $this->write($data);
        }

        $this->reload();
        $this->hideContextmenu();
    }

    private function add($mode)
    {
        $nodePath = $this->data['node_path'];

        if ($mode == 'append') {
            $this->append($nodePath, '', AA);
        }

        if ($mode == 'prepend') {
            $this->prepend($nodePath, '', AA);
        }

        if (in($mode, 'before, after')) {
            $this->insert($nodePath, '', $mode, AA);
        }

        $this->reload();
        $this->hideContextmenu();
    }

    public function updateType()
    {
        if (in($this->data('type'), 'string, array, false, true, null')) {
            $type = $this->data['type'];

            if ($type == 'string') {
                $value = '';
            }

            if ($type == 'array') {
                $value = [];
            }

            if ($type == 'false') {
                $value = false;
            }

            if ($type == 'true') {
                $value = true;
            }

            if ($type == 'null') {
                $value = null;
            }

            $data = $this->read();

            $node = &ap($data, $this->data['node_path']);
            $node = $value;

            $this->write($data);

            $this->reload();
            $this->hideContextmenu();
        }
    }

    public function updateIndex()
    {
        if ($this->dataHas('value')) {
            $nodePath = $this->data['node_path'];
            $newIndex = $this->data['value'];

            $parentNodePath = path_slice($nodePath, 0, -1);
            $currentIndex = path_slice($nodePath, -1);

            $parentNode = $this->readNode($parentNodePath);

            if (!in($newIndex, array_keys($parentNode), true)) {
                $parentNode = $this->insert($nodePath, [$newIndex => $parentNode[$currentIndex]], 'before', RA);

                unset($parentNode[$currentIndex]);

                $this->writeNode($parentNodePath, $parentNode);

                $this->renameToggledPath($nodePath, $newIndex);
            }

            $this->reload();
            $this->hideContextmenu();
        }
    }

    public function toggleIgnoreKeys()
    {
        $ignoreKeys = &$this->s('<:ignore_keys');

        invert($ignoreKeys);

        $jqueryBuilder = $this->jquery($this->_selector('<:|') . " .ignore_keys_toggle_button");

        $ignoreKeys
            ? $jqueryBuilder->addClass("enabled")
            : $jqueryBuilder->removeClass("enabled");
    }

    private function renameToggledPath($nodePath, $newIndex)
    {
        $s = &$this->s('~|');

        $toggledPaths = [];

        foreach ($s['toggled_paths'] as $toggledPath) {
            if (0 === strpos($toggledPath, $nodePath . '/')) {
                $toggledPaths[] = str_replace(
                    $nodePath . '/',
                    path_slice($nodePath, 0, -1) . '/' . $newIndex . '/',
                    $toggledPath
                );
            } elseif ($toggledPath == $nodePath) {
                $toggledPaths[] = path_slice($nodePath, 0, -1) . '/' . $newIndex;
            } else {
                $toggledPaths[] = $toggledPath;
            }
        }

        $s['toggled_paths'] = $toggledPaths;
    }

    public function applyFn()
    {
        if ($this->dataHas('fn, node_path')) {
            $nodePath = $this->data['node_path'];

            if ($this->data('apply_to_level')) {
                $nodePath = path_slice($nodePath, 0, -1);
            }

            $fn = $this->data['fn'];

            $functions = dataSets()->get('std/dataEditor::functions');

            if (in($fn, $functions)) {
                $fnReflection = new \ReflectionFunction($fn);

                if ($fnReflection->getNumberOfRequiredParameters() == 1) {
                    $param = $fnReflection->getParameters()[0];

                    $node = $this->readNode($nodePath);

                    if ($param->canBePassedByValue()) {
                        $node = (array)call_user_func($fn, $node);
                    } else {
                        call_user_func_array($fn, [&$node]);
                    }

                    $this->writeNode($nodePath, $node);

                    $this->reload();
                    $this->hideContextmenu();
                }
            }
        }
    }

    private function reload()
    {
        $this->c('~:reload|');
    }

    private function hideContextmenu()
    {
        $this->widget('<:|')->hide();
    }

    public function setBuffer()
    {
        $data = _j($this->data('value'));

        if ($data) {
            if (is_array($data)) {
                $this->c('buffer:addArray', $data);
            }

            if (is_scalar($data)) {
                $this->c('buffer:addScalar', ['value' => $data]);
            }

            $this->widget('<:|', 'importStatus', ['updated' => true]);
        } else {
            $this->widget('<:|', 'importStatus', ['updated' => false]);
        }
    }
}
