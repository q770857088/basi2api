<?php
namespace api;

class jsonS{
    public $status;
    public $msg;
    public $data;

    /**
     * jsonS constructor.
     * @param string $data
     * @param string $msg
     * @param int $status
     */
    public function __construct($data='',$msg='bs2',$status=0)
    {
        $this->data = $data;
        $this->msg = $msg;
        $this->status = $status;
        if(!empty($data)){
            $this->status = 1;
        }
    }
}