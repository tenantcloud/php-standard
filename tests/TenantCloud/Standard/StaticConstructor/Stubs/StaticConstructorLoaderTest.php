<?php

namespace Tests\TenantCloud\Standard\StaticConstructor\Stubs;

use Composer\Autoload\ClassLoader;
use Mockery;
use Mockery\Exception\BadMethodCallException;
use Mockery\MethodCall;
use Mockery\ReceivedMethodCalls;
use PHPUnit\Framework\Constraint\Exception as ConstraintException;
use PHPUnit\Framework\Constraint\ExceptionCode;
use PHPUnit\Framework\Constraint\ExceptionMessage;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use Prophecy\Exception\Prediction\AggregateException;
use function spl_autoload_functions;
use TenantCloud\Standard\StaticConstructor\StaticConstructorInvalidUsageException;
use TenantCloud\Standard\StaticConstructor\StaticConstructorLoader;

trait State
{
	private array $oldAutoloadFunctions;

	protected function saveAutoLoaders(): void
	{
		$this->oldAutoloadFunctions = spl_autoload_functions();
	}

	protected function restoreAutoLoaders(): void
	{
		$this->clearAutoLoaders();

		foreach ($this->oldAutoloadFunctions as $autoloadFunction) {
			spl_autoload_register($autoloadFunction);
		}
	}

	protected function clearAutoLoaders(): void
	{
		foreach (spl_autoload_functions() as $autoloadFunction) {
			spl_autoload_unregister($autoloadFunction);
		}
	}
}

uses(State::class);

beforeEach(function () {
	class_exists(ClassLoader::class);
	$this->saveAutoloaders();
	class_exists(StaticConstructorInvalidUsageException::class);
	class_exists(StaticConstructorLoader::class);
	class_exists(Exception::class);
	class_exists(Exception::class);
	class_exists(ConstraintException::class);
	class_exists(AggregateException::class);
	class_exists(ExceptionMessage::class);
	class_exists(ExceptionCode::class);
	class_exists(ExpectationFailedException::class);
	class_exists(ReceivedMethodCalls::class);
	class_exists(MethodCall::class);
	class_exists(BadMethodCallException::class);
});

afterEach(function () {
	$this->restoreAutoloaders();
});

test('throws when zero autoloaders are registered', function () {
	$classLoader = Mockery::mock(ClassLoader::class);

	$this->clearAutoloaders();

	new StaticConstructorLoader($classLoader);
})->expectExceptionObject(new StaticConstructorInvalidUsageException('Composer\Autoload\ClassLoader was not found in registered autoloaders'));

test('throws when some autoloaders are registered but composer is not', function () {
	$classLoader = Mockery::mock(ClassLoader::class);

	spl_autoload_register(function () {
	});

	new StaticConstructorLoader($classLoader);
})->expectExceptionObject(new StaticConstructorInvalidUsageException('Composer\Autoload\ClassLoader was not found in registered autoloaders'));

test('throws when static constructor loader is already registered', function () {
	$classLoader = Mockery::mock(ClassLoader::class);

	/** @var StaticConstructorLoader $staticConstructorLoader */
	$staticConstructorLoader = unserialize('O:62:"TenantCloud\Standard\StaticConstructor\StaticConstructorLoader":1:{s:73:"TenantCloud\Standard\StaticConstructor\StaticConstructorLoaderclassLoader";N;}');

	spl_autoload_register(
		[$staticConstructorLoader, 'loadClass'],
	);

	new StaticConstructorLoader($classLoader);
})->expectExceptionObject(new StaticConstructorInvalidUsageException('TenantCloud\Standard\StaticConstructor\StaticConstructorLoader already registered'));

test('injects itself in place of a composer autoloader', function () {
	$classLoader = Mockery::mock(ClassLoader::class);

	foreach (spl_autoload_functions() as $function) {
		spl_autoload_unregister($function);
	}

	spl_autoload_register($firstCallback = function () {
	});
	spl_autoload_register([$classLoader, 'loadClass']);
	spl_autoload_register($lastCallback = function () {
	});

	$staticConstructorLoader = new StaticConstructorLoader($classLoader);
	$autoLoaders = spl_autoload_functions();

	$this->restoreAutoloaders();

	expect($autoLoaders)->toBe(
		[
			$firstCallback,
			[$staticConstructorLoader, 'loadClass'],
			$lastCallback,
		]
	);
});

test('calls static constructor', function ($class) {
	$composerClassLoader = array_filter(
		spl_autoload_functions(),
		static fn ($v) => is_array($v) && $v[0] instanceof ClassLoader
	)[0][0];

	new StaticConstructorLoader($composerClassLoader);
	class_exists($class);

	expect($class::$instance)->toBeInstanceOf($class);
})->with([
	'regular class' => SingletonStub::class,
]);

test('ignores non-existent classes', function () {
	$composerClassLoader = array_filter(
		spl_autoload_functions(),
		static fn ($v) => is_array($v) && $v[0] instanceof ClassLoader
	)[0][0];

	new StaticConstructorLoader($composerClassLoader);

	expect(class_exists('NotExistingClass'))->toBeFalse();
});
