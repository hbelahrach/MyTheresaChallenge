#!/bin/bash

set -e

# Wait for the database container to be ready
until nc -z my_db 5432; do
  echo "Waiting for the database container to be ready..."
  sleep 1
done

# Run the schema update command or any other commands
symfony console doctrine:schema:update --force
symfony console doctrine:fixtures:load --no-interaction

# Execute the command provided as arguments
exec "$@"
