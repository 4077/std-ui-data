<?php namespace std\ui\data\controllers\main;

class Svc extends \Controller
{
    public $singleton = true;

    private $dialogsContainerAdded = false;

    /**
     * Защита от рекурсии
     */
    public function addDialogsContainer()
    {
        if (!$this->dialogsContainerAdded) {
            // 1
            $this->dialogsContainerAdded = true;

            // 2
            $this->c('\std\ui\dialogs~:addContainer:std/dataEditor');
        }
    }
}
