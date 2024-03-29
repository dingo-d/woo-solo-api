<?php

/**
 * Base view class file
 *
 * @package MadeByDenis\WooSoloApi\View
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\WooSoloApi\View;

use Exception;
use MadeByDenis\WooSoloApi\Exception\FailedToLoadView;
use MadeByDenis\WooSoloApi\Exception\InvalidUri;

/**
 * Base view template
 *
 * Basic View class to abstract away the PHP view rendering.
 *
 * @package MadeByDenis\WooSoloApi\View
 * @since 2.0.0
 */
class BaseView implements View
{
	/**
	 * Extension to use for view files.
	 */
	protected const VIEW_EXTENSION = 'php';

	/**
	 * Contexts to use for escaping.
	 */
	protected const CONTEXT_HTML = 'html';
	protected const CONTEXT_JAVASCRIPT = 'js';

	/**
	 * URI to the view file to render.
	 *
	 * @var string
	 */
	protected string $uri;

	/**
	 * Internal storage for passed-in context.
	 *
	 * @var array<mixed>
	 */
	protected array $internalContext = [];

	/**
	 * Instantiate a View object.
	 *
	 * @param string $uri URI to the view file to render.
	 *
	 * @throws InvalidUri If an invalid URI was passed into the View.
	 */
	final public function __construct(string $uri)
	{
		$this->uri = $this->validate($uri);
	}

	/**
	 * Render a given URI.
	 *
	 * @param array<mixed> $context Context in which to render.
	 *
	 * @return string           Rendered HTML.
	 * @throws FailedToLoadView If the View URI could not be loaded.
	 */
	public function render(array $context = []): string
	{
		// Add context to the current instance to make it available within the rendered view.
		foreach ($context as $key => $value) {
			$this->$key = $value;
		}

		// Add entire context as array to the current instance to pass onto partial views.
		$this->internalContext = $context;

		/**
		 * Save current buffering level so we can backtrack in case of an error.
		 * This is needed because the view itself might also add an unknown number of output buffering levels.
		 */
		$buffer_level = ob_get_level();
		ob_start();

		try {
			include $this->uri;
		} catch (Exception $exception) {
			// Remove whatever levels were added up until now.
			while (ob_get_level() > $buffer_level) {
				ob_end_clean();
			}

			throw FailedToLoadView::viewException(
				$this->uri,
				$exception
			);
		}

		return (string)ob_get_clean();
	}

	/**
	 * Render a partial view.
	 *
	 * This can be used from within a currently rendered view, to include nested partials.
	 *
	 * The passed-in context is optional, and will fall back to the parent's
	 * context if omitted. Used for reusing parts of views on multiple pages.
	 *
	 * @param string $uri URI of the partial to render.
	 * @param array<mixed>|null $context Context in which to render the partial.
	 *
	 * @return string           Rendered HTML.
	 * @throws InvalidUri       If the provided URI was not valid.
	 * @throws FailedToLoadView If the view could not be loaded.
	 */
	public function renderPartial(string $uri, ?array $context = null): string
	{
		$view = new static($uri);

		return $view->render($context ?: $this->internalContext);
	}

	/**
	 * Validate an URI.
	 *
	 * @param string $uri URI to validate.
	 *
	 * @return string      Validated URI.
	 * @throws InvalidUri If an invalid URI was passed into the View.
	 */
	protected function validate(string $uri): string
	{
		$uri = $this->checkExtension($uri, static::VIEW_EXTENSION);
		$uri = trailingslashit(dirname(__DIR__, 2)) . $uri;

		if (!is_readable($uri)) {
			throw InvalidUri::fromUri($uri);
		}

		return $uri;
	}

	/**
	 * Check that the URI has the correct extension.
	 *
	 * Optionally adds the extension if none was detected.
	 *
	 * @param string $uri URI to check the extension of.
	 * @param string $extension Extension to use.
	 *
	 * @return string URI with correct extension.
	 */
	protected function checkExtension(string $uri, string $extension): string
	{
		$detected_extension = pathinfo($uri, PATHINFO_EXTENSION);

		if ($extension !== $detected_extension) {
			$uri .= '.' . $extension;
		}

		return $uri;
	}
}
