<?php

class WebUser extends CWebUser {

    private $_model = null;

    private function getModel() {
        if (!$this->isGuest && $this->_model === null) {
            $this->_model = User::model()->findByAttribute(['id' => $this->id]);
        }
        return $this->_model;
    }

}
