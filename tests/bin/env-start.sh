#!/usr/bin/env bash

set -u

max_attempts=3
attempt=1

while [ "$attempt" -le "$max_attempts" ]; do
  echo "Starting wp-env (attempt $attempt/$max_attempts)..."

  if npm run env -- start; then
    exit 0
  fi

  if [ "$attempt" -lt "$max_attempts" ]; then
    echo "wp-env start failed, retrying in 10 seconds..."
    sleep 10
  fi

  attempt=$((attempt + 1))
done

echo "wp-env start failed after $max_attempts attempts."
exit 1
