<?php

namespace MakinMind\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MakinMindUserBundle extends Bundle
{
	public function getParent() {
		return 'FOSUserBundle';
	}
}
