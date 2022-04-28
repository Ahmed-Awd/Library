@servers(['local' => '127.0.0.1', 'staging' => 'root@18.202.102.29'])

@story('deploy', ['on' => 'staging'])
    update-code
	install-dependencies
    refresh-database
    optimize
    build-js
@endstory

@task('test', ['on' => 'local'])
    cp .env.example .env
    php artisan key:generate
    php artisan migrate --force --no-interaction
    php artisan test
@endtask

@task('lint', ['on' => 'local'])
    ./vendor/bin/phplint ./ --exclude=vendor;
@endtask

@task('code-style-check', ['on' => 'local'])
    ./vendor/bin/phpcs;
@endtask

@task('code-style-fix', ['on' => 'local'])
    ./vendor/bin/phpcbf;
@endtask


@task('update-code')
    cd /var/www/minpizza
    git pull origin develop
@endtask

@task('install-dependencies')
    cd /var/www/minpizza
    composer install --optimize-autoloader --no-dev
@endtask

@task('refresh-database')
    cd /var/www/minpizza
    php artisan migrate
    php artisan DB:seed --class=PermissionSeeder
    php artisan DB:seed --class=RoleSeeder
    php artisan DB:seed --class=GeneralSettingsSeeder

@endtask

@task('optimize')
    cd /var/www/minpizza
    php artisan optimize
@endtask

@task('build-js')
    cd /var/www/minpizza

    export NVM_DIR="$([ -z "${XDG_CONFIG_HOME-}" ] && printf %s "${HOME}/.nvm" || printf %s "${XDG_CONFIG_HOME}/nvm")"
    [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh" # This loads nvm

    npm run prod
@endtask
