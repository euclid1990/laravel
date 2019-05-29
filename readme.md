# Laravel

## Stack

- Laravel 5.8.3

## TO DO List

- [x] Init Laravel
- [x] Init Docker development environment
- [ ] Create base directory structure
- [ ] Implement SSR Sign In | Sign Up | Sign Out + Unit Test
- [ ] Implement SPA Sign In | Sign Up | Sign Out + Unit Test
- [ ] Implement user's role base authorization + Unit Test
- [ ] Implement upload file + Unit Test
- [ ] Implement resize/rotate/crop image + Unit Test
- [ ] Implement read/write CSV + Unit Test
- [ ] Implement read/write Excel + Unit Test
- [ ] Implement Logger + Unit Test

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

#### Client
- Serving client
```
$ ./docker.sh exec client bash
$ npm run serve
```

- Access: http://localhost:3030

## Build

- For re-build docker images and re-create containers
```terminal
$ ./docker.sh test-build
```
- Attach to a running container
```terminal
$ ./docker.sh exec {service_name}
```

## Testing

## Linting

- PHP code
```terminal
$ ./docker.sh exec php
### List coding standard rules
$ vendor/bin/phpcs -i
### Run phpcs check
$ vendor/bin/phpcs --standard=SunOs . --encoding=utf-8
```
- Javascript code
```terminal
$ TBD
```

## Contribute

- Fork the repository and make changes on your fork in a feature branch.
- Commit messages must start with a capitalized and short summary.
- After every commit, make sure the test suite passes.
- Contributor sends pull request to release/develop branch, ask another contributor to check if possible.
- Don't push logs or any unnecessary files to git repository
- Merge when pull request got 2 OK from contributors and CI build is green.
- Merge develop to master to release final version.
