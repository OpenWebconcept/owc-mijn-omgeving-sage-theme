<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Hooks;

use Pronamic\WordPress\Pay\Payments\Payment;
use Yard\Hook\Filter;

class Pronamic
{
	#[Filter('pronamic_pay_worldline_direct_hosted_checkout_descriptor')]
	public function setPaymentDescription(string $description, Payment $payment): string
	{
		$paymentDescription = $payment->get_description();

		if (! is_string($paymentDescription) || 0 === strlen(trim($paymentDescription))) {
			return $description;
		}

		return $paymentDescription;
	}
}
