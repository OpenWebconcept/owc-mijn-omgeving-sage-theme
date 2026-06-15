<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LoginMenu extends Component
{
	public function render(): View|Closure|string
	{
		return view('components.login-menu', );
	}
}
