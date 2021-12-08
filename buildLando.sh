printf "Nom du projet : "
read -r name

# Initialize a symfony recipe
lando init \
  --source cwd \
  --recipe symfony \
  --webroot public \
  --name "$name"

# Install symfony
lando composer create-project symfony/website-skeleton tmp && cp -r tmp/. . && rm -rf tmp

# Install other Symfony dependencies you may like
lando composer require annotations asset doctrine encore form logger maker profiler security security-guard stof/doctrine-extensions-bundle twig validator var-dumper mailer intl

# Start it up
lando start

# List information about this app.
lando info

# Run bin/console commands with: lando console
# Here is how to clear cache;
lando console cache:clear
lando console make:user
lando console make:auth
lando yarn add tailwindcss postcss-loader purgecss-webpack-plugin glob-all path autoprefixer
lando yarn add sass-loader@^12.0.0 sass --dev
lando npx tailwindcss init
lando yarn build
