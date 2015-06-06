<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 06.06.2015
 * Time: 11:03
 */
class ErrorControllerQueue extends AbstractControllerQueue {

    public function actionGraylog() {
        $result = $this->_getBodyRequest();
        if (!$result) {
            throw new Exception('Body request is empty');
        }
        $json = json_decode($result,1);
        if (!$json) {
            throw new Exception('Body request is not json');
        }
        header('filepath:'.$this->_config['error_queue']);
        $queue = new QueueAccessor($this->_config['error_queue'], 'error');
        $queue->push($result);

    }

}