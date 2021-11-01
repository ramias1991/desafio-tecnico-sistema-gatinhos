<?php

namespace App;

class ResponseDTO
{
    protected $status, $data, $msg, $codeStatus;

    public function setResponse($status, $data, $msg, $codeStatus)
    {
        $this->status = $status;
        $this->data = $data;
        $this->msg = $msg;
        $this->codeStatus = $codeStatus;
    }

    public function getResponse()
    {
        return [
            'status' => $this->status,
            'data' => $this->data,
            'msg' => $this->msg,
            'codeStatus' => $this->codeStatus
        ];
    }
}
