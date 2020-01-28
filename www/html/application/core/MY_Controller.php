<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * @property CI_Output output
 * Class MY_Controller
 */
class MY_Controller extends CI_Controller
{
    protected $data = [];

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    protected function jsonResponse($payload, int $status = 200, bool $alreadyEncoded = false): bool
    {
        $this->output->set_status_header($status);
        $this->output->set_content_type("application/json");
        $this->output->set_output(
            !$alreadyEncoded ? json_encode($payload) : $payload
        );
        return true;
    }

    protected function ajaxResponse($data = [], string $message = "", int $errorCode = 0): bool
    {
        return $this->jsonResponse([
            "data" => $data,
            "message" => $message,
            "errorCode" => $errorCode
        ], $errorCode === 0 ? 200 : $errorCode);
    }

    protected function receiveJSON(bool $assoc = false, bool $strip_tags = true)
    {
        $json = file_get_contents('php://input');
        if ($strip_tags) {
            $json = strip_tags($json, '<br><p><h1><h2><h3><h4><h5><h6><span>');
        }
        return json_decode($json, $assoc);
    }
}
