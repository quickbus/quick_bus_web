<?php
App::uses('AppController', 'Controller');
/**
 * RealTimePositions Controller
 *
 * @property RealTimePosition $RealTimePosition
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class RealTimePositionsController extends AppController {

 /**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

    public function upload()
    {
        if ($this->request->is('post'))
        {
            $returnValue = $this->RealTimePosition->saveRealTimePosition($this->request->data['RealTimePosition'], $this->Auth->user('id'));
            $this->set('returnValue', $returnValue);
        }
        else
        {
            $this->set('returnValue', 99);
        }
        
        $notifyPhones = $this->RealTimePosition->ViewUserNotifyPhone->find('all', array(
            'conditions' => array('ViewUserNotifyPhone.user_id' => $this->Auth->user('id')),
            'fields' => array('ViewUserNotifyPhone.*')
            ));
        
        if (count($notifyPhones) > 0)
        {
            $UserNotifyPhoneHistoryRecords = array();
            
            foreach ($notifyPhones as $phone)
            {
                array_push($UserNotifyPhoneHistoryRecords, $phone['ViewUserNotifyPhone']);
            }
            
            $this->RealTimePosition->UserNotifyPhoneHistory->create();
            
            if (!$this->RealTimePosition->UserNotifyPhoneHistory->saveMany($UserNotifyPhoneHistoryRecords))
            {
                $this->set('returnValue', 4);
            }
        }
        
        $this->render('/RealTimePositions/upload', 'ajax');
    }
    
    public function test_upload()
    {
        
    }
}
