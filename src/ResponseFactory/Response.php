<?php

namespace Phpcuisine\Bags\ResponseFactory;

class Response
{

    private $code;
    private $msg;
    private $data;
    private $enum;

    public function __construct()
    {
        $this->enum = new Enum();
    }

    public function __call($name, $arguments)
    {

        switch ($name) {

            case 'success':
                $this->_success($arguments);
                break;
            case 'error':
                $this->_error($arguments);
                break;
            case 'list':
                $this->_list($arguments);
                break;
        }

        return $this->returnJson();
    }


    public function setEnum($enum)
    {

        $this->enum = $enum;

        return $this;
    }


    /**
     * 用于请求成功后的消息提醒
     */
    private function _success($arguments)
    {

        $count = count($arguments);

        //如果只有一个参数
        if ($count == 1) {


            $data = $arguments[0];

            $this->_getSuccess($data);

            //判断该参数是不是状态码

        } else if ($count == 2) {

            list($first, $second) = $arguments;
            //两个参数，则定义，第一个参数为返回的消息体，第二个参数为数据
            $this->_getSuccess($first, $second);
        } else {

            throw new \Exception("success方法参数超出,不能大于两个参数", 1);

            return false;
        }
    }


    private function _getSuccess($first, $second = [])
    {
        if ($this->checkEnumCode($first)) {

            //是状态码的话，
            $this->code = $this->enum->getEnum($first);
            $this->msg = $this->enum->getMsg($first);
            $this->data = [
                "message" => $this->msg,
                "list" => $second
            ];
        } else {

            $this->code = $this->enum->getEnumFromName("DEFAULT");
            $this->msg = $this->enum->getMsg($this->code);
            $this->data = [
                "message" => $first,
                "list" => $second
            ];
        }
    }


    /**
     * 用于请求失败后的消息提醒
     */
    private function _error($arguments)
    {

        $count = count($arguments);

        //如果只有一个参数
        if ($count == 1) {


            $data = $arguments[0];

            $this->_getError($data);

            //判断该参数是不是状态码

        } else if ($count == 2) {

            list($first, $second) = $arguments;
            //两个参数，则定义，第一个参数为返回的消息体，第二个参数为数据
            $this->_getError($first, $second);
        } else {

            throw new \Exception("error方法参数超出,不能大于两个参数", 1);

            return false;
        }
    }


    private function _getError($first, $second = [])
    {
        if ($this->checkEnumCode($first)) {

            //是状态码的话，
            $this->code = $this->enum->getEnum($first);
            $this->msg = $this->enum->getMsg($first);
            $this->data = [
                "message" => $this->msg,
                "list" => $second
            ];
        } else {

            $this->code = $this->enum->getEnumFromName("ERROR");
            $this->msg = $this->enum->getMsg($this->code);
            $this->data = [
                "message" => $first,
                "list" => $second
            ];
        }
    }


    /**
     * 用于输出数据
     */
    private function _list($arguments)
    {

        $count = count($arguments);

        //如果只有一个参数
        if ($count == 1) {


            $data = $arguments[0];

            $this->code = $this->enum->getEnumFromName("DEFAULT");
            $this->msg = $this->enum->getMsg($this->code);
            $this->data = [
                "list" => $data
            ];
        } else if ($count == 2) {

            list($first, $second) = $arguments;
            //两个参数，则定义，第一个参数为状态码，第二个参数为数据
            $this->_getList($first, $second);
        } else {

            throw new \Exception("list方法参数超出,不能大于两个参数", 1);

            return false;
        }
    }


    private function _getList($first, $second = [])
    {
        if ($this->checkEnumCode($first)) {

            //是状态码的话，
            $this->code = $this->enum->getEnum($first);
            $this->msg = $this->enum->getMsg($first);
            $this->data = [
                "list" => $second
            ];
        } else {

            $this->code = $this->enum->getEnumFromName("DEFAULT");
            $this->msg = $first;
            $this->data = [
                "list" => $second
            ];
        }
    }

    /**
     * 判断是否是状态码
     */
    private function checkEnumCode($code)
    {

        return preg_match('/\d{4}/', $code);
    }


    private function returnJson()
    {

        return json_encode([
            "code" => $this->code,
            "msg" => $this->msg,
            "data" => $this->data,
            "time" => date("Y-m-d H:i:s")
        ]);
    }
}
