<?php
namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'reactday.pl');

// Project repository
set('repository', 'git@gitlab.com:AndrewKiri/reactday.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

$ip = '68.183.75.44';
$user = 'gringo';
$port = 22;
$private_key = '~/.ssh/id_rsa';
$deploy_path = '~/{{application}}/{{stage}}';


// Hosts
host('production')
    ->hostname($ip)
    ->stage('production')
    ->set('deploy_path', $deploy_path)
    ->user($user)
    ->port($port)
    ->identityFile($private_key)
    ->forwardAgent(true)
    ->multiplexing(true)
;

host('staging')
    ->hostname($ip)
    ->stage('staging')
    ->set('deploy_path', $deploy_path)
    ->user($user)
    ->port($port)
    ->identityFile($private_key)
    ->forwardAgent(true)
    ->multiplexing(true)
;

desc('Installing npm dependencies and running production build');
task('npm', function () {
    cd('~/{{application}}/{{stage}}/current');
    run('yarn install');
    run('yarn build');
    run('pm2 restart {{stage}}.config.json');
});

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);

after('deploy:unlock', 'npm');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

