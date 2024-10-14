<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    public $type;
    public $message;
    public $title;
    
    public function __construct($type, $message, $title = null)
    {
        $this->type = $type;
        $this->message = $message;
        $this->title = $title ?? ucfirst($type) . '!';
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alert');
    }
}
