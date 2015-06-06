<?php
/**
 * Created by PhpStorm.
 * User: ZyManch
 * Date: 06.06.2015
 * Time: 11:03
 */
class ErrorControllerQueue extends AbstractControllerQueue {

    const QUEUE_NAME = 'error';

    const ENGINE_GRAYLOG = 'graylog';
    const ENGINE_ROLLBAR = 'rollbar';

    public function actionGraylog() {
        $result = $this->_getBodyRequest();
        if (!isset($_GET['token']) || !$_GET['token']) {
            throw new Exception('Token missed');
        }
        if (!$result) {
            throw new Exception('Body request is empty');
        }
        $json = json_decode($result,1);
        if (!$json) {
            throw new Exception('Body request is not json');
        }
        $queue = new QueueAccessor($this->_config['error_queue'], ErrorControllerQueue::QUEUE_NAME);
        $queue->push(array('engine'=>self::ENGINE_GRAYLOG,'token' => $_GET['token'],'result' => $result));

    }

    public function actionRollbar() {
        $result = $this->_getBodyRequest();
        if (!isset($_GET['token']) || !$_GET['token']) {
            throw new Exception('Token missed');
        }
        if (!$result) {
            throw new Exception('Body request is empty');
        }
        $json = json_decode($result,1);
        if (!$json) {
            throw new Exception('Body request is not json');
        }
        $queue = new QueueAccessor($this->_config['error_queue'], ErrorControllerQueue::QUEUE_NAME);
        $queue->push(array('engine'=>self::ENGINE_ROLLBAR,'token' => $_GET['token'],'result' => $result));

    }

}