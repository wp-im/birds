#!/bin/sh
set -eu

root=$(CDPATH= cd -- "$(dirname "$0")/.." && pwd)
cd "$root"

find . -name '*.php' -not -path './build/*' -print0 | xargs -0 -n1 php -l >/dev/null
node --check assets/js/interactions.js
node --check assets/demo/palettes.js
jq empty theme.json
git diff --check

if rg -n -i 'kakiji|ofblog|google|site kit|google-only' . \
  -g '*.php' -g '*.css' -g '*.js' -g '*.json' \
  -g '!build/**' -g '!prototype/**' -g '!assets/demo/**' -g '!scripts/validate.sh'; then
  echo 'site-specific identifiers found in the public Theme runtime source' >&2
  exit 1
fi

echo 'BIRDS_THEME_VALIDATE_OK'
