<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Notification;
use Auth;
class Notifications extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run($id)
    {
        //
        $notifications = Notification::where('user_id', Auth::user()->id)->latest()->get()->count();
        return view('widgets.notifications', [
            'config' => $this->config,
            'count' => $notifications
        ]);
    }
}
