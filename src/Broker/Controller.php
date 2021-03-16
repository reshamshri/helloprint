<?php


namespace Helloprint\Broker;
use Helloprint\Kafka\CanProduce;
use Helloprint\Kafka\Producer;
use Helloprint\Kafka\Producible;
use Helloprint\Logger\Log;
use Helloprint\Models\ModelNotFoundException;
use Helloprint\Models\Request as RequestModel;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Controller
 * @package Helloprint\Broker
 */
class Controller implements CanProduce
{
    use Producible;

    /**
     * @var RequestModel
     */
    private RequestModel $model;
    /**
     * @var Log
     */
    private Log $logger;
    /**
     *
     */
    public const DEFAULT_TOPIC = 'TopicA';
    /**
     *
     */
    public const DEFAULT_MESSAGE = 'Hi';
    /**
     * @var Producer
     */
    private Producer $producer;


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

        $this->produce($this->getProducer(), $payload);

        return $requestModel->token;
    }

    /**
     * @param Producer $producer
     */
    public function setProducer(Producer $producer)
    {
        $this->producer = $producer;
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
     * @return RequestModel
     */
    private function saveMessage(Request $request): RequestModel
    {
        $this->model->token = getToken();
        $this->model->message = $request->get('message',self::DEFAULT_MESSAGE);
        $this->model->save();

        return $this->model;
    }

    /**
     * @return string
     */
    public function getTopicToProduce(): string
    {
        return self::DEFAULT_TOPIC;
    }

    /**
     * @return Producer
     */
    private function getProducer(): Producer
    {
        return $this->producer ?? producer();
    }
}
