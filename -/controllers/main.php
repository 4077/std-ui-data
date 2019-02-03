<?php namespace std\ui\data\controllers;

class Main extends InstanceController
{
    public function reload()
    {
        $this->jquery('|')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v('|');

        $this->c('>svc:addDialogsContainer');

        $v->assign([
                       'CONTENT' => $this->c('>tree:view|')
                   ]);

        $this->css();

        return $v;
    }
}
