<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 06.06.2015
 * Time: 14:12
 */
abstract class AbstractCommand {

    protected $_config;
    protected $_params;

    public function __construct($config, $params) {
        $this->_config = $config;
        $this->_params = $params;
    }
}