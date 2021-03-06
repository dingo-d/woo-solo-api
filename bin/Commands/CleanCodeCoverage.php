<?php

/**
 * File holding the command for cleaning code coverage
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

/**
 * Class CleanCodeCoverage
 *
 * Symfony command generator class used for cleaning code coverage.
 *
 * @package MadeByDenis\Commands
 * @since 2.0.0
 */
class CleanCodeCoverage extends Command
{

	/**
	 * Name of the input argument
	 *
	 * @var string
	 */
	private const ABS_PATH_ARG = 'absolute-part';

	/**
	 * Name of the serialized coverage file
	 *
	 * @var string
	 */
	private const SERIALIZED_COV = 'coverage.serialized';

	/**
	 * Name of the xml coverage file
	 *
	 * @var string
	 */
	private const XML_COV = 'coverage.xml';

	/**
	 * Command name property
	 *
	 * @var string Command name.
	 */
	protected static $defaultName = 'clean-coverage';

	/**
	 * Configures the current command
	 *
	 * @inheritDoc
	 */
	protected function configure(): void
	{
		$this
			->setDescription('Cleans the generated code coverage from absolute paths')
			->setHelp( // phpcs:ignore Generic.Files.LineLength.TooLong
				'This command will check if the code coverage is generated. If it is it will clean the absolute paths of the coverage, based on the proposed relative path to remove. Code coverage has to be generated by PHPUnit.' // phpcs:ignore Generic.Files.LineLength.TooLong
			)
			->addArgument(
				self::ABS_PATH_ARG,
				InputArgument::REQUIRED,
				'Pass the part of the absolute path to remove from the coverage files. Example: /Users/denis.zoljom/Sites/personal/plugins-testing/'
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

		// Check if coverage exists.
		$serializedCoveragePath = dirname(__FILE__, 3) . '/tests/_output/' . self::SERIALIZED_COV;
		$xmlCoveragePath = dirname(__FILE__, 3) . '/tests/_output/' . self::XML_COV;

		if (!file_exists($serializedCoveragePath)) {
			throw new RuntimeException('coverage.serialized doesn\'t exist. Please generate code coverage first.');
		}

		if (!file_exists($xmlCoveragePath)) {
			throw new RuntimeException('coverage.xml doesn\'t exist. Please generate code coverage first.');
		}

		/**
		 * Part of the paths to find and delete from the coverage
		 *
		 * @var string
		 */
		$partToDelete = $input->getArgument(self::ABS_PATH_ARG);

		if (empty($partToDelete)) {
			throw new RuntimeException('You must specify the part of the absolute path!');
		}

		// Read the template contents, and replace the placeholders with provided variables.
		$serializedCoverage = file_get_contents($serializedCoveragePath);
		$xmlCoverage = file_get_contents($xmlCoveragePath);

		if ($serializedCoverage === false) {
			throw new RuntimeException('The file "coverage.serialized" seems to be missing or is empty.');
		}

		if ($xmlCoverage === false) {
			throw new RuntimeException('The file "coverage.xml" seems to be missing or is empty.');
		}

		// Search-replace the content for the path to remove.
		$replacedSerializedString = str_replace($partToDelete, '', $serializedCoverage);
		$replacedXmlString = str_replace($partToDelete, '', $xmlCoverage);

		$this->writeToFile(self::SERIALIZED_COV, $serializedCoveragePath, $replacedSerializedString, $io);
		$this->writeToFile(self::XML_COV, $xmlCoveragePath, $replacedXmlString, $io);

		return 0;
	}

	/**
	 * Write to file
	 *
	 * @param string $fileName Name of the file.
	 * @param string $filePath Full path of the file.
	 * @param string $fileContents replaced contents.
	 * @param SymfonyStyle $io Symfony Style dependency.
	 *
	 * @return void
	 */
	private function writeToFile(string $fileName, string $filePath, string $fileContents, SymfonyStyle $io): void
	{
		$fp = fopen($filePath, 'wb'); // phpcs:ignore WordPress.WP.AlternativeFunctions

		if ($fp !== false) {
			fwrite($fp, $fileContents); // phpcs:ignore WordPress.WP.AlternativeFunctions
			fclose($fp); // phpcs:ignore WordPress.WP.AlternativeFunctions
		} else {
			$io->error("File {$fileName} couldn't be written to. There was an error.");
		}

		$io->success("File {$fileName} successfully updated.");
	}
}
