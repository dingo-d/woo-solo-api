<?php

/**
 * File holding the command for preparing the plugin for upload to wordpress.org
 *
 * @package MadeByDenis\Commands
 * @since 2.0.0
 */

declare(strict_types=1);

namespace MadeByDenis\Commands;

use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class PreparePluginForUpload
 *
 * Symfony command generator class used for preparing the plugin for upload to wordpress.org
 *
 * @package MadeByDenis\Commands
 * @since 2.0.0
 */
class PreparePluginForUpload extends Command
{
	/**
	 * Plugin slug identifier
	 *
	 * @var string
	 */
	private const SLUG = 'slug';

	/**
	 * Plugin slug identifier
	 *
	 * @var string
	 */
	private const VERSION = 'version';

	/**
	 * Command name property
	 *
	 * @var string Command name.
	 */
	protected static $defaultName = 'prepare-plugin';

	/**
	 * Configures the current command
	 *
	 * @inheritDoc
	 */
	protected function configure(): void
	{
		$this
			->setDescription('Prepares the plugin for upload to wordpress.org')
			->setHelp('This command will generate a folder with all the files necessary for plugin upload.')
			->addArgument(
				self::SLUG,
				InputArgument::REQUIRED,
				'Add the plugin slug, e.g. woo-solo-api'
			)
			->addArgument(
				self::VERSION,
				InputArgument::REQUIRED,
				'Add the plugin version'
			);
	}

	/**
	 * Execute the current command
	 *
	 * @param InputInterface $input Input values.
	 * @param OutputInterface $output Output values.
	 *
	 * @return int
	 * @throws RuntimeException Validation exceptions.
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);
		$filesystem = new Filesystem();

		$slug = $input->getArgument(self::SLUG);
		$version = $input->getArgument(self::VERSION);

		// Create a folder <slug>/<version>
		$folderToCreate = dirname(__FILE__, 3) . "/{$slug}/{$version}";
		$source = dirname(__FILE__, 3) . DIRECTORY_SEPARATOR;
		// Remove node_modules folder. We don't need it, as we'll run clean install and removal later on.
		$filesystem->remove($source . '/node_modules');

		// Copy everything to that folder.
		try {
			if (!$filesystem->exists($folderToCreate)) {
				$filesystem->mirror($source, $folderToCreate);
			}
		} catch (IOExceptionInterface $exception) {
			echo "Error copying directory at {$exception->getPath()}.";
		}

		$io->write('Folder successfully copied.');

		// Run npm install and build, run composer install --no-ansi --no-dev --no-interaction --no-progress --optimize-autoloader
		$commands = 'npm install && npm run build && composer install --no-ansi --no-dev --no-interaction --no-progress --optimize-autoloader';

		$installProcess = new Process($commands, $folderToCreate);

		$installProcess->run();

		// executes after the command finishes
		if (!$installProcess->isSuccessful()) {
			throw new ProcessFailedException($installProcess);
		}

		$io->write('Packages successfully installed for the deployment package.');

		// Remove all the files from the .gitignore and .gitattributes, we don't need dev stuff in the release.
		$filesystem->remove($folderToCreate . "/{$slug}/{$version}");

		$excludeList = $this->getExclusionList($source . '.gitattributes');

		foreach ($excludeList as $item) {
			if (strpos($item, '/') === false) {
				$filesystem->remove($folderToCreate . '/' . $item);
			} else {
				$filesystem->remove($folderToCreate . $item);
			}
		}

		// Remove node_modules.
		$filesystem->remove($folderToCreate . '/node_modules');

		$io->write('Extra files and folders removed from the deployment package.');

		// Reinstall the npm packages so that we may continue with the development later on.
		$reInstallProcess = new Process('npm install && npm run build', $source);

		$reInstallProcess->run();

		// executes after the command finishes
		if (!$reInstallProcess->isSuccessful()) {
			throw new ProcessFailedException($reInstallProcess);
		}

		$io->write('JS packages successfully reinstalled for the development process.');

		$io->success("Plugin ready for upload.");

		return 0;
	}

	/**
	 * Read a .gitattributes file and get the list of files to remove
	 *
	 * @param string $file File to read.
	 *
	 * @return array List of items to remove from final build.
	 */
	private function getExclusionList(string $file): array
	{
		return array_values(
			array_filter(
				array_map(function($element) {
					if (strpos($element, 'export-ignore') > 0 ) {
						return str_replace(' export-ignore', '', $element);
					}

					return '';
				}, file($file))
			)
		);
	}
}
