<?php

use Pusher\Pusher;
use Pusher\PusherException;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class NotificationModel
 * @property Mailer $mailer
 */
class NotificationModel extends App_Model
{
    protected $table = 'notifications';

	const TYPE_CHAT_PUSH = 'CHAT_PUSH';
	const TYPE_WEB_PUSH = 'WEB_PUSH';
	const TYPE_MAIL_PUSH = 'MAIL_PUSH';

    const SUBSCRIBE_REQUISITION = 'requisition';
    const SUBSCRIBE_OFFER = 'offer';
    const SUBSCRIBE_ORDER = 'order';

    const EVENT_REQUISITION_MUTATION = 'requisition-mutation';
    const EVENT_REQUISITION_VALIDATION = 'requisition-validation';
    const EVENT_REQUISITION_PROCEED = 'requisition-proceed';
    const EVENT_OFFER_MUTATION = 'offer-mutation';
    const EVENT_ORDER_MUTATION = 'order-mutation';

	/**
	 * Broadcast notification to users.
	 *
	 * TYPE_WEB_PUSH:
	 * --------------
	 * push web notification via web socket, following is the example of simple minimal payload:
	 *
	 * $data = [
	 *      'id_user' => $supervisor['id_user'],
	 *      'id_related' => $id,
	 *      'channel' => NotificationModel::SUBSCRIBE_ACTIVITY,
	 *      'event' => NotificationModel::EVENT_ACTIVITY_REPORTING,
	 *      'payload' => [
	 *          'message' => "Please review activity {$label}",
	 *          'url' => site_url('activity/activity-report/view/' . $id),
	 *          'time' => format_date('now', 'Y-m-d H:i:s'),
	 *          'description' => $message
	 *      ]
	 * ];
	 * $this->notification->broadcast($data);
	 *
	 *
	 * TYPE_CHAT_PUSH:
	 * ----------------
	 * push notification to realtime chat with api example of the data bellow:
	 * Payload data depends on CHAT API that you used!
	 *
	 * $data = [
	 *      'url' => 'status',
	 *      'method' => 'GET',
	 *      'payload' => [
	 *          'message' => "Please review activity",
	 *          'url' => site_url('activity/activity-report/view/1'),
	 *          'time' => format_date('now', 'Y-m-d H:i:s'),
	 *          'description' => 'Message detail ' . php_sapi_name()
	 *      ]
	 * ];
	 *
	 *
	 * TYPE_EMAIL_PUSH:
	 * ----------------
	 * send email to client, with template message and attachment, following the example:
	 *
	 * $data = [
	 *      'to' => 'angga.aw92@gmail.com',
	 *      'subject' => "User update information",
	 *      'template' => 'email/basic',
	 *      'option' => [
	 *          'cc' => ['angga@mail.com', 'ari@mail.com'],
	 *          'attachment' => '/path/to/file'
	 *      ],
	 *      'payload' => [
	 *          'name' => 'Angga Ari Wijaya',
	 *          'username' => 'angga.ari',
	 *          'employees' => []
	 *      ]
	 * ];
	 *
	 * @param $data
	 * @param string $type
	 * @return array|bool
	 */
	public function broadcast($data, $type = self::TYPE_WEB_PUSH)
	{
		$payload = $data['payload'];

		switch ($type) {
			case self::TYPE_WEB_PUSH:
				$this->create([
					'id_user' => $data['id_user'],
					'id_related' => $data['id_related'],
					'channel' => $data['channel'],
					'event' => $data['event'],
					'data' => json_encode($data['payload'])
				]);
				try {
					$pusher = new Pusher(
						env('PUSHER_APP_KEY'),
						env('PUSHER_APP_SECRET'),
						env('PUSHER_APP_ID'),
						['cluster' => env('PUSHER_APP_CLUSTER'), 'encrypted' => false]
					);
					$pusher->trigger($data['channel'] . '-' . $data['id_user'], $data['event'], $data['payload']);
				} catch (PusherException $e) {
					return $e->getMessage();
				}
				break;
			case self::TYPE_CHAT_PUSH:
				$baseUri = get_if_exist($data, 'base_uri', env('CHAT_API_URL'));
				$url = get_if_exist($data, 'url', '/');
				$method = get_if_exist($data, 'method', 'GET');
				if (!key_exists('token', $payload)) {
					$payload['token'] = env('CHAT_API_TOKEN');
				}
				if (env('APP_ENVIRONMENT') == 'development' && !empty(env('CHAT_API_SANDBOX_NUMBER'))) {
					$payload['chatId'] = detect_chat_id(env('CHAT_API_SANDBOX_NUMBER'));
				}

				try {
					$client = new GuzzleHttp\Client([
						'base_uri' => $baseUri,
						'verify' => boolval(env('CHAT_API_SECURE'))
					]);
					$response = $client->request($method, $url, ['query' => $payload]);
					return json_decode($response->getBody(), true);
				} catch (Exception $e) {
					return ['error' => $e->getMessage()];
				}
			case self::TYPE_MAIL_PUSH:
				$this->load->model('modules/Mailer', 'mailer');
				$emailTo = $data['to'];
				$subject = $data['subject'];
				$template = $data['template'];
				$data = $payload;
				$option = get_if_exist($data, 'option', []);
				return $this->mailer->send($emailTo, $subject, $template, $data, $option);
		}
		return true;
	}

    /**
     * Get parsed data notifications by user.
     *
     * @param $userId
     * @return array
     */
    public function getByUser($userId)
    {
        $this->db->from($this->table)
            ->where('id_user', $userId)
            ->limit(100)
            ->order_by('created_at', 'desc');

        $notifications = $this->db->get()->result_array();

        foreach ($notifications as &$notification) {
            $notification['data'] = (array) json_decode($notification['data']);
        }

        return $notifications;
    }

    /**
     * Get sticky navbar notification.
     *
     * @param null $userId
     * @return array
     */
    public static function getUnreadNotification($userId = null)
    {
        if ($userId == null) {
            $userId = UserModel::loginData('id');
        }

        $CI = get_instance();
        $CI->db->from('notifications')
            ->where([
                'id_user' => $userId,
                'is_read' => false,
                'created_at>=DATE(NOW()) - INTERVAL 7 DAY' => null
            ])
            ->order_by('created_at', 'desc')
            ->limit(3);

        $notifications = $CI->db->get()->result_array();

        foreach ($notifications as &$notification) {
            $notification['data'] = (array) json_decode($notification['data']);
        }

        return $notifications;
    }

    /**
     * Parse notification content.
     *
     * @param $payload
     * @param string $url
     * @return mixed
     */
    public static function parseNotificationMessage($payload, $url = '')
    {
        $message = $payload->message;
        if (property_exists($payload, 'link_text')) {
            $links = $payload->link_text;
            foreach ($links as $link) {
                $message = str_replace($link->text, "<a class='font-weight-medium' href='{$link->url}'>{$link->text}</a>", $message);
            }
        } else if (!empty($url)) {
            $message = "<a href='{$url}'>{$message}</a>";
        }

        return $message;
    }
}
