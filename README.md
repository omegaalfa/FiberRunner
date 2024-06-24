# FiberRunner
FiberDriver is a PHP package designed for managing and executing concurrent tasks using PHP fibers. This package provides a robust and efficient way to handle asynchronous operations, allowing developers to create and control multiple fibers with context management. Key features include:

Fiber Creation and Management: Easily create and manage fibers with context.
Context Handling: Store and manage context data and exceptions.
Concurrency: Execute multiple fibers concurrently, enhancing performance for I/O-bound and CPU-bound tasks.
Ideal for tasks like asynchronous HTTP requests, database queries, and parallel processing.

# Features
- Fiber Creation and Management: Easily create and manage fibers with context.
- Context Handling: Store and manage context data and exceptions.
- Concurrency: Execute multiple fibers concurrently, enhancing performance for I/O-bound and CPU-bound tasks.

#Installation
You can install the package via Composer:
```bash
composer require omegaalfa/fiber-driver
```

# Creating a Fiber
```php
use src\fibers\FiberDriver;
use src\fibers\DefaultContext;

$driver = new FiberDriver();
$context = new DefaultContext(['data' => 'initial']);

$fiber = $driver->createFiber(function() {
    // Fiber logic here
    echo "Fiber started\n";
}, $context);

```

# Starting|Stoping a Fiber
```php
$driver->start($fiber);
$driver->stop($fiber);

```

# Running a Fiber with Automatic Management
```php
$context = new DefaultContext(['data' => 'initial']);

$result = $driver->run(function() {
    // Fiber logic here
    echo "Fiber started\n";
}, [], $context);

print_r($result);

```

# Handling Context Data
```php
$context = new DefaultContext(['data' => 'initial']);

$driver->run(function() use ($context) {
    // Modify context data
    $context->setContextData(['data' => 'modified']);
}, [], $context);

print_r($context->getContextData()); // Outputs: ['data' => 'modified']

```

# Handling Exceptions

```php
$context = new DefaultContext();

$driver->run(function() {
    throw new Exception("An error occurred");
}, [], $context);

if ($exception = $context->getContextException()) {
    echo "Exception: " . $exception->getMessage();
}

```


# Contributing
Contributions are welcome! Please submit a pull request or open an issue to discuss your ideas.

# License
This project is licensed under the MIT License.
