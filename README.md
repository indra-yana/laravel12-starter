# Docker x Laravel + Vue Starter Kit From Official Laravel Starter Kit

## Introduction

Our Vue starter kit provides a robust, modern starting point for building Laravel applications with a Vue frontend using [Inertia](https://inertiajs.com).

Inertia allows you to build modern, single-page Vue applications using classic server-side routing and controllers. This lets you enjoy the frontend power of Vue combined with the incredible backend productivity of Laravel and lightning-fast Vite compilation.

This Vue starter kit utilizes Vue 3 and the Composition API, TypeScript, Tailwind, and the [shadcn-vue](https://www.shadcn-vue.com) component library.

## Roadmap
- ✅ Docker Setup
- ✅ Authentication
- ✅ User Management
- 🚧 Landing Page
- 🚧 Role Management
- 🚧 Permission Management
- 🚧 Audit Log
- 🚧 Multi Lang Support
- 🚧 Modular System

## Official Documentation

Documentation for all Laravel starter kits can be found on the [Laravel website](https://laravel.com/docs/starter-kits).

## Contributing

Thank you for considering contributing to our starter kit! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Docker
Follow this documentation [docker/README.md](https://github.com/indra-yana/laravel12-starter/blob/main/docker/README.md)

Attention:

- If you encounter a permission error inside the container, check the user privileges on the host machine, try to lowering the user privilege to a non-root user, such as www or www-data in host machine when run docker compose.

- Hint: adjust the `UID` and `GID` variables in the `.env` file to match your user ID and group ID. You can find these by running `id -u` and `id -g` in the terminal.

## License

The Laravel + Vue starter kit is open-sourced software licensed under the MIT license.
