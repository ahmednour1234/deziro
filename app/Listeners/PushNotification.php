<?php

namespace App\Listeners;

use App\Http\Resources\Boost;
use App\Http\Resources\Cart;
use App\Http\Resources\GroupedOrder;
use App\Http\Resources\Order;
use App\Http\Resources\Product;
use App\Models\Notification;
use App\Models\Order as ModelsOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class PushNotification
{

    public function afterShippedOrder($order)
    {
        $user = $order->user;

        if ($user) {
            $data = [
                'notifiable_type' => 'Order',
                'notifiable_id' => $order->id,
                'notifiable' => new Order($order),
                'action' => 'ShippedOrder',
                'user_id' => $user->id,
                'fcm_token' => $user->fcm_token,
                'title' => 'Order Shipped',
                'description' => 'Your order has been shipped and is on its way to you.',
            ];


            $data['id'] = $this->storeNotification($data);
            if ($user->fcm_token) {
                $this->send($data);
            }

            Log::info('1.1');

        }
    }

    public function afterDeliveredOrder($order)
{
    $user = $order->user;

    if ($user) {
        $data = [
            'notifiable_type' => 'Order',
            'notifiable_id' => $order->id,
            'notifiable' => new Order($order),
            'action' => 'DeliveredOrder', // You may want to create a 'DeliveredOrder' action
            'user_id' => $user->id,
            'fcm_token' => $user->fcm_token,
            'title' => 'Order Delivered',
            'description' => 'Your order has been successfully delivered. Enjoy your meal!',
        ];

        $data['id'] = $this->storeNotification($data);
        if ($user->fcm_token) {
            $this->send($data);
        }
        Log::info('1.2');
    }
}



    public function afterCanceledOrder($order)
    {

        $user = $order->user;

        if ($user) {
            $data = [
                'notifiable_type' => 'Order',
                'notifiable_id' => $order->id,
                'notifiable' => new Order($order),
                'action' => 'CanceledOrder',
                'user_id' => $user->id,
                'fcm_token' => $user->fcm_token,
                'title' => 'Order Canceled',
                'description' => 'Your order has been successfully canceled. If you have any questions or need assistance, please feel free to contact our support team.',
            ];

            $data['id'] = $this->storeNotification($data);
            if ($user->fcm_token ) {
                $this->send($data);
            }
            Log::info('1.3');
        }

    }
    public function send($data)
    {
        $this->pushNotification(
            is_array($data['fcm_token']) ? $data['fcm_token'] : [$data['fcm_token']],
            $data['title'],
            $data['description'],
            [
                'data' => json_encode([
                    'id' => $data['id'],
                    'notifiable_type' => $data['notifiable_type'],
                    'notifiable' => $data['notifiable'],
                    'user_id' => isset($data['user_id']) ? $data['user_id'] : null,
                ])
            ]

        );
        Log::info('Notification sended');
    }

    public function afterNotificationAdded($request)
    {
        $user_id = $request->user_id;

        $notification = new Notification([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => ($user_id == 'all' || $user_id == 'stores' || $user_id == 'individual') ? null : $user_id,
            'notifiable_type' => 'General',
            'action' => 'GeneralNotification',
            'topic' => ($user_id == 'all' || $user_id == 'stores' || $user_id == 'individual') ? $user_id : null
        ]);


        $notification->notifiable_id = $notification->id;
        $notification->save();

        $data = [
            'notifiable_type' => 'GeneralNotification',
            'notifiable_id' => $notification->id,
            'notifiable' => $notification,
            'action' => 'GeneralNotification',
            'user_id' => $user_id,
            'title' => $request->title,
            'description' => $request->description,
        ];


        if ($user_id == 'all' || $user_id == 'stores' || $user_id == 'individual') {

            $this->pushNotificationByTopic(
                $user_id,
                $data['title'],
                $data['description'],
                [
                    'data' => json_encode([
                        'notifiable_type' => $data['notifiable_type'],
                        'notifiable' => $data['notifiable'],
                    ])
                ]
            );
        } else {
            // $user = User::join('notification_settings', 'notification_settings.user_id', 'users.id')
            //     ->select('users.*')
            //     ->where('push_enabled', 1)
            //     ->where('status', 'active')
            //     ->whereNotNull('fcm_token')
            //     ->where('users.id', $user_id)
            //     ->first();
            // if ($user) {
                $user = User::where('id',$user_id)->first();
                $this->pushNotification(
                    [$user->fcm_token],
                    $data['title'],
                    $data['description'],
                    [
                        'data' => json_encode([
                            'notifiable_type' => $data['notifiable_type'],
                            'notifiable' => $data['notifiable'],
                        ])
                    ]
                );
            // }
        }
    }



    public function storeNotification($data)
    {

        $notification = new Notification();
        $notification->title = $data['title'];
        $notification->description = $data['description'];
        $notification->user_id =  $data['user_id'];
        $notification->notifiable_type = $data['notifiable_type'];
        $notification->notifiable_id =  $data['notifiable_id'];
        $notification->action =  $data['action'];

        $notification->save();
        return $notification->id;
        // logger('Notification Id: ' . $notification->id);
    }

    public function pushNotification($recipients, $title, $body, $payload = [])
    {

        $serverAuthKey = env('SERVER_AUTH_KEY');

        $headers = [
            'Authorization: key=' . $serverAuthKey,
            'Content-Type: application/json',
            'priority' => 'high',
            'sound' => "default",
            'click_action' => "FLUTTER_NOTIFICATION_CLICK"
        ];

        $data = [
            "registration_ids" => $recipients,
            "notification" => [
                "title" => $title,
                "body" => $body,
                "sound" => "default",
                'priority' => 'high',
            ],
            "data" => array_merge($payload, ['click_action' => "FLUTTER_NOTIFICATION_CLICK"])
        ];
        logger($data['registration_ids']);
        logger($data['notification']);

        $dataString = json_encode($data);

        try {

            $url = 'https://fcm.googleapis.com/fcm/send';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $result = curl_exec($ch);

            logger($result);
            curl_close($ch);
            return $result;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public function pushNotificationByTopic($topic, $title, $body, $payload = [])
    {

        $serverAuthKey = env('SERVER_AUTH_KEY');

        $headers = [
            'Authorization: key=' . $serverAuthKey,
            'Content-Type: application/json',
            'priority' => 'high',
            'sound' => "default",
            'click_action' => "FLUTTER_NOTIFICATION_CLICK"
        ];

        $data = [
            "to" => "/topics/{$topic}",
            "notification" => [
                "title" => $title,
                "body" => $body,
                "sound" => "default",
                'priority' => 'high',
            ],
            "data" => array_merge($payload, ['click_action' => "FLUTTER_NOTIFICATION_CLICK"])
        ];

        $dataString = json_encode($data);

        try {
            $url = 'https://fcm.googleapis.com/fcm/send';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $result = curl_exec($ch);

            logger($result);
            curl_close($ch);
            return $result;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
