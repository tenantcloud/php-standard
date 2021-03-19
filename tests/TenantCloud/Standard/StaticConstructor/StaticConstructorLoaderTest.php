<?php

namespace Tests\TenantCloud\Standard\StaticConstructor;

use Composer\Autoload\ClassLoader;
use Mockery\Exception\BadMethodCallException;
use Mockery\MethodCall;
use Mockery\ReceivedMethodCalls;
use PHPUnit\Framework\Constraint\Exception as ConstraintException;
use PHPUnit\Framework\Constraint\ExceptionCode;
use PHPUnit\Framework\Constraint\ExceptionMessage;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExceptionWrapper;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestFailure;
use Prophecy\Exception\Prediction\AggregateException;
use TenantCloud\Standard\StaticConstructor\StaticConstructorInvalidUsageException;
use TenantCloud\Standard\StaticConstructor\StaticConstructorLoader;
use TenantCloud\Standard\StaticConstructor\StaticConstructorLoaderRegisterer;
use Tests\TenantCloud\Standard\StaticConstructor\Stubs\SingletonStub;

uses(StaticConstructorLoaderState::class);

beforeEach(function () {
	StaticConstructorLoaderRegisterer::unregister();
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
	class_exists(ExceptionWrapper::class);
	class_exists(TestFailure::class);
	class_exists(ReceivedMethodCalls::class);
	class_exists(MethodCall::class);
	class_exists(BadMethodCallException::class);
});

afterEach(function () {
	$this->restoreAutoloaders();
	StaticConstructorLoaderRegisterer::unregister();
	StaticConstructorLoaderRegisterer::register();
});

test('throws when zero autoloaders are registered', function () {
	$this->clearAutoloaders();

	StaticConstructorLoaderRegisterer::register();
})->expectExceptionObject(new StaticConstructorInvalidUsageException('Composer\Autoload\ClassLoader was not found in registered autoloaders'));

test('throws when some autoloaders are registered but composer is not', function () {
	$this->clearAutoloaders();
	spl_autoload_register(function () {
	});

	StaticConstructorLoaderRegisterer::register();
})->expectExceptionObject(new StaticConstructorInvalidUsageException('Composer\Autoload\ClassLoader was not found in registered autoloaders'));

test('does not re-register when calling more than once', function () {
	StaticConstructorLoaderRegisterer::register();

	$loaders = spl_autoload_functions();

	StaticConstructorLoaderRegisterer::register();

	expect(spl_autoload_functions())->toBe($loaders);
});

test('injects itself after the composer autoloader', function () {
	$composerClassLoader = array_values(ClassLoader::getRegisteredLoaders())[0];

	foreach (spl_autoload_functions() as $function) {
		spl_autoload_unregister($function);
	}

	spl_autoload_register($firstCallback = function () {
	});
	spl_autoload_register([$composerClassLoader, 'loadClass']);
	spl_autoload_register($lastCallback = function () {
	});

	StaticConstructorLoaderRegisterer::register();

	$autoLoaders = spl_autoload_functions();

	$this->restoreAutoloaders();

	expect($autoLoaders)->toBe(
		[
			$firstCallback,
			[StaticConstructorLoaderRegisterer::loader(), 'loadClass'],
			$lastCallback,
		]
	);
});

test('calls static constructor', function () {
	StaticConstructorLoaderRegisterer::register();

	class_exists(SingletonStub::class);

	expect(SingletonStub::$instance)->toBeInstanceOf(SingletonStub::class);
});

test('ignores non-existent classes', function () {
	StaticConstructorLoaderRegisterer::register();

	expect(class_exists('NotExistingClass'))->toBeFalse();
});
