<?php
namespace api;

class returns{

    private $status;
    private $data;
    private $msg;
    public function __construct(jsonS $obj)
    {
        $this->status = $obj->status;
        $this->msg = $obj->msg;
        $this->data = $obj->data;
    }

    public function send(){
        if(!empty($this->data)){
            $this->status = 1;
        }

        echo json_encode(['status'=>$this->status,'msg'=>$this->msg,'data'=>$this->data]);
        exit;
    }
}