# Laravel

## Stack

- Laravel 5.8.3

## Completed

- [x] Init Laravel
- [x] Init Docker development environment

## Prerequisites

To install the development dependencies you will need:

- [Install Docker](https://docs.docker.com/install/linux/docker-ce/ubuntu/) & [Post-installation steps for Linux](https://docs.docker.com/install/linux/linux-postinstall/)
- [Install Docker compose](https://docs.docker.com/compose/install/)

## Development

```terminal
$ chmod a+x ./docker.sh
$ ./docker.sh start
```

- Web: https://localhost:8443 `[Basic Authenticate] user:web password:123456`
- PhpMyAdmin: https://localhost:8444

## Testing

## Linting

## Contribute

- Fork the repository and make changes on your fork in a feature branch.
- Commit messages must start with a capitalized and short summary.
- After every commit, make sure the test suite passes.
- Contributor sends pull request to release/develop branch, ask another contributor to check if possible.
- Don't push logs or any unnecessary files to git repository
- Merge when pull request got 2 OK from contributors and CI build is green.
- Merge develop to master to release final version.
