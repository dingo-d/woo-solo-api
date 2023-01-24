<?php

/**
 * Escaped view class file
 *
 * @package MadeByDenis\WooSoloApi\View
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\View;

use MadeByDenis\WooSoloApi\Exception\FailedToLoadView;
use MadeByDenis\WooSoloApi\Exception\InvalidUri;

/**
 * Escaped view
 *
 * This is a Decorator that decorates a given View with escaping meant for
 * HTML output.
 *
 * @package MadeByDenis\WooSoloApi\View
 * @since 2.0.0
 */
final class EscapedView implements View
{

	/**
	 * Tags that are allowed to be rendered.
	 *
	 * @var array<string, array<string, bool>>
	 */
	public const ALLOWED_TAGS = [
		'form' => [
			'id' => true,
			'class' => true,
			'action' => true,
			'method' => true,
		],
		'input' => [
			'id' => true,
			'class' => true,
			'type' => true,
			'name' => true,
			'value' => true,
			'checked' => true,
		],
		'select' => [
			'id' => true,
			'class' => true,
			'type' => true,
			'name' => true,
			'value' => true,
		],
		'option' => [
			'id' => true,
			'class' => true,
			'type' => true,
			'name' => true,
			'value' => true,
			'selected' => true,
		],
		'label' => [
			'for' => true,
		],
		'div' => [
			'class' => true,
		],
		'svg' => [
			'class' => true,
			'style' => true,
			'width' => true,
			'height' => true,
			'viewbox' => true,
			'xmlns' => true,
		],
		'g' => [
			'fill' => true,
			'fill-rule' => true,
			'transform' => true,
		],
		'path' => [
			'd' => true,
			'id' => true,
			'fill' => true,
			'style' => true,
			'stroke' => true,
			'stroke-width' => true,
		],
		'mask' => [
			'id' => true,
			'fill' => true,
		],
		'rect' => [
			'transform' => true,
			'fill' => true,
			'width' => true,
			'height' => true,
			'rx' => true,
			'ry' => true,
			'x' => true,
			'y' => true,
		],
		'xmlns' => [
			'xlink' => true,
		],
		'defs' => [],
		'span' => [
			'title' => true,
		],
		'br' => [],
	];

	/**
	 * View instance to decorate.
	 *
	 * @var View
	 */
	private View $view;

	/**
	 * Tags that are allowed to pass through the escaping function.
	 *
	 * @var array<string, array<string, bool>>
	 */
	private array $allowedTags;

	/**
	 * Instantiate a Escaped_View object.
	 *
	 * @param View $view View instance to decorate.
	 * @param array<string, array<string, bool>>|null $allowedTags Optional. Array of allowed tags to let
	 *                                through escaping functions. Set to sane
	 *                                defaults if none provided.
	 */
	public function __construct(View $view, ?array $allowedTags = null)
	{
		$this->view = $view;
		$this->allowedTags = null === $allowedTags ?
			$this->prepareAllowedTags(wp_kses_allowed_html('post')) :
			$allowedTags;
	}

	/**
	 * Render a given URI.
	 *
	 * @param array<mixed> $context Context in which to render.
	 *
	 * @return string Rendered HTML.
	 * @throws FailedToLoadView If the View URI could not be loaded.
	 */
	public function render(array $context = []): string
	{
		return wp_kses($this->view->render($context), $this->allowedTags);
	}

	/**
	 * Render a partial view.
	 *
	 * This can be used from within a currently rendered view, to include
	 * nested partials.
	 *
	 * The passed-in context is optional, and will fall back to the parent's
	 * context if omitted.
	 *
	 * @param string $uri URI of the partial to render.
	 * @param array<mixed>|null $context Context in which to render the partial.
	 *
	 * @return string Rendered HTML.
	 * @throws InvalidUri If the provided URI was not valid.
	 * @throws FailedToLoadView If the view could not be loaded.
	 */
	public function renderPartial(string $uri, ?array $context = null): string
	{
		return wp_kses(
			$this->view->renderPartial($uri, $context),
			$this->allowedTags
		);
	}

	/**
	 * Prepare an array of allowed tags by adding form elements to the existing
	 * array.
	 *
	 * This makes sure that the basic form elements always pass through the
	 * escaping functions.
	 *
	 * @param array<string, array<string, bool>> $allowedTags Allowed tags as fetched from the WordPress
	 *                           defaults.
	 *
	 * @return array<string, array<string, bool>> Modified tags array.
	 */
	private function prepareAllowedTags(array $allowedTags): array
	{
		return array_replace_recursive($allowedTags, self::ALLOWED_TAGS);
	}
}
