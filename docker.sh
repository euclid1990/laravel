#!/bin/bash

set -eu
set -o pipefail

if [ -z "${1-}" ]; then
  echo "Please input {action} to execute command"
  echo "Example: [start|stop|restart|build|logs|ps|clean|destroy]"
  echo "Usage: $ docker.sh {action}"
  exit;
else
  case "${1-}" in
    build|pull|exec)
      if [ -z "${2-}" ]; then
        echo "Input {service} to execute command if you dont want perform all"
        echo "Example: [php|nginx|mysql|cert]"
        echo "Usage: $ docker.sh {action} {service}"
      fi
      ;;
  esac
fi

echo "✹✹✹ Executing command ✹✹✹"
echo "UTC Time: $(TZ=UTC date '+%Y-%m-%d %H:%M:%S')"
echo "VN Time: $(TZ=Asia/Bangkok date '+%Y-%m-%d %H:%M:%S')"
echo "JST Time: $(TZ=Asia/Tokyo date '+%Y-%m-%d %H:%M:%S')"

command_action=${1-}
command_service=${2-}
docker_compose="/usr/local/bin/docker-compose"
docker_dev_yml="docker-compose.dev.yml"

change_context_dir() {
  echo "------ Change exec command context dir ------"
  context_dir=$(dirname "$0")
  # Change execute context to project's directory
  cd $context_dir
}

create_volume() {
  name=${1-}
  volume_existing=$(docker volume ls --filter name=${name} --format '{{ .Name }}')
  echo "------ Create $name volume ------"
  if [ -z "$volume_existing" ]; then
    docker volume create $name
  else
    echo "$name volume existing"
  fi
}

remove_volume() {
  name=${1-}
  volume_existing=$(docker volume ls --filter name=${name} --format '{{ .Name }}')
  echo "------ Remove $name volume ------"
  if [ -z "$volume_existing" ]; then
    docker volume rm $name
  else
    echo "$name volume is not existing"
  fi
}

run_each_command() {
  service=${1:-""}
  echo $2
  shift
  cmds=("$@")
  for (( i = 0; i < ${#cmds[@]}; i++ )); do
    ${cmds[$i]} ${service}
  done
}

docker_wrapper() {
  message=${1:-}
  service=${2:-}
  shift 2
  cmds=("$@")
  case "$service" in
    "")
      echo "------ $message [ALL] ------"
      run_each_command "" "${cmds[@]}"
      ;;
    *)
      echo "------ $message [$service] ------"
      run_each_command "$service" ${cmds[@]}
      ;;
  esac
}

docker_build() {
  message="Re-build"
  service=${1:-}
  cmds=("$docker_compose -f $docker_dev_yml build --force-rm")
  docker_wrapper "$message" "$service" "${cmds[@]}"
}

docker_up() {
  message="Up"
  service=${1:-}
  cmds=("$docker_compose -f $docker_dev_yml up")
  docker_wrapper "$message" "$service" "${cmds[@]}"
}

docker_stop() {
  message="Stop"
  service=${1:-}
  cmds=("$docker_compose -f $docker_dev_yml stop")
  docker_wrapper "$message" "$service" "${cmds[@]}"
}

docker_down() {
  message="Down"
  service=${1:-}
  cmds=("$docker_compose -f $docker_dev_yml down")
  docker_wrapper "$message" "$service" "${cmds[@]}"
}

docker_restart() {
  message="Restart"
  service=${1:-}
  cmds=("$docker_compose -f $docker_dev_yml restart")
  docker_wrapper "$message" "$service" "${cmds[@]}"
}

docker_remove_dangling_images() {
  echo "------ Removing all dangling images ------"
  dangling_images=$(docker images -f "dangling=true" -q)
  if [ -n "$dangling_images" ]; then
    docker rmi $(docker images -f "dangling=true" -q)
  fi
}

docker_logs() {
  message="Log"
  service=${1:-}
  cmds=("$docker_compose -f $docker_dev_yml logs")
  docker_wrapper $message $service $cmds
}

docker_ps() {
  message="Process Status"
  service=${1:-}
  cmds=("$docker_compose -f $docker_dev_yml ps")
  docker_wrapper "$message" "$service" "${cmds[@]}"
}

action_start() {
  change_context_dir
  create_volume "mysql_data"
  create_volume "composer_cache"
  docker_up $command_service
}

action_stop() {
  change_context_dir
  docker_down $command_service
}

action_restart() {
  change_context_dir
  docker_restart $command_service
}

action_ps() {
  change_context_dir
  docker_ps $command_service
}

action_build() {
  change_context_dir
  docker_build
}

action_logs() {
  change_context_dir
  docker_logs
}

action_clean() {
  change_context_dir
  docker_remove_dangling_images
}

action_destroy() {
  change_context_dir
  docker_down $command_service
  remove_volume "mysql_data"
  remove_volume "composer_cache"
}

case "$command_action" in
  "start")
    action_start
    ;;
  "stop")
    action_stop
    ;;
  "restart")
    action_restart
    ;;
  "build")
    action_build
    ;;
  "logs")
    action_logs
    ;;
  "ps")
    action_ps
    ;;
  "clean")
    action_clean
    ;;
  "destroy")
    action_destroy
    ;;
  *)
    echo "Unknown action: $command_action"
esac

