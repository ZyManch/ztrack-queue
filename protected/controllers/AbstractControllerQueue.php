<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 06.06.2015
 * Time: 10:58
 */
abstract class AbstractControllerQueue {

    protected $_config = array();

    public function __construct($config) {
        if ($config) {
            $this->_config = $config;
        }
    }

    protected function _getBodyRequest() {
        $putdata = fopen("php://input", "r");
        $result = array();
        while ($data = fread($putdata, 1024)) {
            $result[] = $data;
        }
        return implode('',$result);
    }
}