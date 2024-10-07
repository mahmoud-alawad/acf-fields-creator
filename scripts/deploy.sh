#!/bin/bash

# Exit if any command fails
set -e
cd docs

yarn 
yarn build

cd dist

git init
git add -A
git commit -m "deploy"

git push -f git@github.com:mahmoud-alawad/acf-fields-creator.git master:gh-pages

cd -

echo "Deployed successfully!"
