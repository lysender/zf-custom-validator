<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->view->headTitle('Main');
        
        $validator = new Dc_Validate;
        $validator->addValidator('name', new Dc_Validate_Name);
        $validator->addValidator('username', new Dc_Validate_Name(
            array(
                'key' => 'username',
                'min' => 4,
                'max' => 16,
                'encoding' => 'utf-8'
            )
        ));
        $validator->addValidator('order_count', new Dc_Validate_OrderCount);
        $validator->addValidator('order_count_other', new Dc_Validate_OrderCount(
            array(
                'key' => 'order_count_other',
                'min' => 1,
                'max' => 10
            )
        ));
        
        
        $data = array(
                    'name' => '',
                    'username' => 'dc',
                    'order_count' => 'abc',
                    'order_count_other' => 100
                );
        if ($validator->isValid($data))
        {
            echo '<h2>Valid!';
        }
        else
        {
            $msg = $validator->getMessages();
            var_dump(Dc_Validate::mergeMessages($msg));
            
            echo '<hr />';
            echo '<h2>First message</h2>';
            var_dump(Dc_Validate::getFirstMessage($msg));
        }
    }
}

