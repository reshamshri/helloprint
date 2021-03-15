<?php


namespace Helloprint\Broker;
use Helloprint\Kafka\CanProduce;
use Helloprint\Kafka\Producible;
use Helloprint\Logger\Log;
use Helloprint\Models\Exceptions\ModelNotFoundException;
use Helloprint\Models\Request as RequestModel;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Controller
 * @package Helloprint\Broker
 */
class Controller implements CanProduce
{
    use Producible;

    private RequestModel $model;
    private Log $logger;
    public const DEFAULT_TOPIC = 'TopicA';


    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->model = new RequestModel();
        $this->logger = new Log('Broker');
    }

    /**
     * @param Request $request
     * @return string
     */
    public function processMessage(Request $request ): string
    {
        $requestModel = $this->saveMessage($request);
        $payload = json_encode(array('uuid' => $requestModel->uuid, 'message' => $requestModel->message));

        $this->produce($payload);

        return $requestModel->token;
    }

    /**
     * @param Request $request
     * @return string
     * @throws ModelNotFoundException
     */
    public function getMessageByToken(Request $request ): string
    {
        $token = $request->get('token');
        $result = $this->model->where(['token' => $token]);

        if(empty($result)) {
            throw new ModelNotFoundException('Not a valid token');
        }

        return $result[0]["message"];
    }

    /**
     * @param Request $request
     * @return $this
     */
    private function saveMessage(Request $request): RequestModel
    {
        $this->model->token = getToken();
        //TODO:check if the token is not duplicated else regenerate a new token

        $this->model->message = $request->get('message','Hi');
        $this->model->save();

        return $this->model;
    }

    public function getTopic(): string
    {
        return self::DEFAULT_TOPIC;
    }
}
