# Swoole or Openswoole Runtime with Hotrelaod

A Symfony Runtime package that provides OpenSwoole integration for high-performance PHP applications. This package extends Symfony's Runtime component to support OpenSwoole server and hot reloading, enabling you to run your Symfony applications with improved performance and concurrent request handling.

## Features

- OpenSwoole server integration for Symfony applications
- Support for both HTTP Kernel and callable applications
- Hot reload capability for development (optional)
- Configurable server settings
- Environment variable support for configuration

## Requirements

- PHP 8.1 or higher
- OpenSwoole extension
- Symfony 7.2 or higher

## Installation

You can install the package via Composer:

```bash
composer require cydrickn/runtime
```

## Usage

### Basic Usage

To use the runtime in your Symfony application, create a `runtime.php` file in your project root:

```bash
APP_RUNTIME=\\Cydrickn\\Runtime\\Runtime php ./public/index.php
```

### Configuration Options

The runtime supports various configuration options that can be set either in the options array or through environment variables:

| Option | Environment Variable | Default | Description |
|--------|---------------------|---------|-------------|
| host | SWOOLE_HOST | 127.0.0.1 | Server host address |
| port | SWOOLE_PORT | 8000 | Server port |
| mode | SWOOLE_MODE | 2 | Server mode (SWOOLE_PROCESS) |
| sock_type | SWOOLE_SOCK_TYPE | 1 | Socket type (SWOOLE_SOCK_TCP) |
| hot_reload | SWOOLE_HOT_RELOAD | 0 | Enable hot reload (1) or disable (0) |
| base_path | SWOOLE_BASE_PATH | __DIR__ | Base path for hot reload |

### Hot Reload

For development, you can enable hot reload functionality by installing the optional `cydrickn/php-watcher` package:

```bash
composer require --dev cydrickn/php-watcher
```
in your `.env`

```env
SWOOLE_HOT_RELOAD=1

```

### Using .env File

You can also add the runtime configuration to your `.env` file:

```env
APP_RUNTIME=\\Cydrickn\\Runtime\\Runtime
```

Then run your application normally:

```bash
php ./public/index.php
```

## Development

### Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

### License

This package is open-sourced software licensed under the [MIT License](LICENSE).

## Author

- Cydrick Nonog (cydrick.dev@gmail.com)

## Credits

This package is built on top of:
- [Symfony Runtime](https://github.com/symfony/runtime)
- [OpenSwoole](https://github.com/openswoole/core)
- [Runtime Swoole](https://github.com/runtime/runtime-swoole) 