<?php

class Response {
    static function status($code) {
        http_response_code($code);
    }

    static function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    static function text($message) {
        header('Content-Type: application/json');
        $text = array(
            "message" => $message
        );
        echo json_encode($text);
    }
}

?>