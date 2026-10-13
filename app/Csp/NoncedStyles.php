<?php

declare(strict_types=1);

namespace OWC\MijnOmgeving\Csp;

use ReflectionClass;
use WP_HTML_Tag_Processor;
use WP_Styles;

/**
 * WordPress has no filter to add attributes to inline <style> tags printed by WP_Styles,
 * so this subclass adds the CSP nonce to the tags it prints.
 */
class NoncedStyles extends WP_Styles
{
	public static function fromStyles(WP_Styles $styles): self
	{
		$instance = (new ReflectionClass(self::class))->newInstanceWithoutConstructor();

		// Copy state (including private properties of WP_Dependencies) from the original instance.
		for ($class = new ReflectionClass($styles); $class; $class = $class->getParentClass()) {
			foreach ($class->getProperties() as $property) {
				if ($property->isStatic()) {
					continue;
				}

				$property->setAccessible(true);
				$property->setValue($instance, $property->getValue($styles));
			}
		}

		return $instance;
	}

	public function do_item($handle, $group = false)
	{
		ob_start();
		$result = parent::do_item($handle, $group);
		echo $this->addNonce((string) ob_get_clean());

		return $result;
	}

	public function print_inline_style($handle, $display = true)
	{
		if (! $display) {
			return parent::print_inline_style($handle, false);
		}

		ob_start();
		$result = parent::print_inline_style($handle, true);
		echo $this->addNonce((string) ob_get_clean());

		return $result;
	}

	private function addNonce(string $html): string
	{
		$processor = new WP_HTML_Tag_Processor($html);

		while ($processor->next_tag('style')) {
			$processor->set_attribute('nonce', csp_nonce());
		}

		return $processor->get_updated_html();
	}
}
