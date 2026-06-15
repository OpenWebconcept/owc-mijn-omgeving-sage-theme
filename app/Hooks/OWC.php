<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Hooks;

use Exception;
use Illuminate\Support\Facades\Log;
use Sentry;
use Yard\Hook\Action;

class OWC
{
	#[Action('gfamp::exception/intercept')]
	#[Action('jcc::exception/intercept')]
	#[Action('kvkpg::exception/intercept')]
	#[Action('owc_gravityforms_digid_exception_intercept')]
	#[Action('owc_gravityforms_eherkenning_exception_intercept')]
	#[Action('owc_gravityforms_eidas_exception_intercept')]
	#[Action('owc_signicat_openid_exception_intercept')]
	#[Action('owcdatab::exception/intercept')]
	#[Action('owcms::exception/intercept')]
	#[Action('oz::exception/intercept')]
	#[Action('pg::exception/intercept')]
	public function interceptException(Exception $exception)
	{
		Sentry\captureException($exception);
		Log::warning('Captured exception for Sentry: ' . $exception->getMessage());
	}

	#[Action('owc_signicat_openid_log_intercept')]
	public function interceptLog(string $level, string $message, array $context)
	{
		Log::log($level, $message, $context);
	}

	#[Action('gfamp::info/intercept')]
	#[Action('kvkpg::info/intercept')]
	#[Action('owc_gravityforms_digid_info_intercept')]
	#[Action('owc_gravityforms_eherkenning_log_intercept')]
	#[Action('owc_gravityforms_eidas_info_intercept')]
	#[Action('owcdatab::info/intercept')]
	public function interceptLogInfo(string $message, array $context)
	{
		Log::info($message, $context);
	}

	#[Action('jcc::error/intercept')]
	public function interceptLogError(string $message, array $context)
	{
		Log::error($message, $context);
	}
}
