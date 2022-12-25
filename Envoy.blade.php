echo 'connect to server ';
@servers(['web' => 'u643120521@82.180.172.204 -p65002'])
echo 'connect to server successfely';
@setup
$repository= 'git@github.com:TouradGithub/School_system.git';
$branch = isset($branch) ? $branch : "testing";

$app_dir = "/home/u643120521";
$public_folder_path="/home/u643120521/domains/rimfoot.com/public_html/school/";

$releases_dir = "$app_dir/touradmedlemin/releases";
$release = date('YmdHis');
$new_release_dir ="$releases_dir/$release";
$branch_path="$app_dir/$branch";

$keep = 1;
$new_release_dir ="/home/u643120521/domains/rimfoot.com/public_html/school/";
@endsetup
{{-- $server_dir=$branch; --}}


@story('deploy')
clone_repository
run_composer
setup_app
clean
succeed
@endstory



@task('clone_repository')
echo 'begin repository'
free -g -h -t && sync && free -g -h
echo 'Cloning repository'
echo 'Cloning  branch {{ $branch }} from rep {{ $repository }} in {{ $new_release_dir }}'



git clone --depth 1 --branch {{ $branch }} {{ $repository }} {{ $new_release_dir }}
echo 'clone successefily'
cd {{ $new_release_dir }}
@endtask

@task('run_composer')
echo "Starting deployment ({{ $release }})"
pwd
echo {{ $new_release_dir }}
cd {{ $new_release_dir }}
echo "moved succes".{{ $new_release_dir }}
composer install
echo "composer installed  succefuly"
@endtask







@task('setup_app')
echo "Setting up the app"
cd {{ $new_release_dir }}
pwd
free -g -h -t && sync && free -g -h
echo "Run migrate"
cp .env.example .env
php artisan key:generate --force
echo " test key ok"
php artisan optimize:clear
echo " test optimize ok"
php artisan migrate:fresh --seed
echo " test migrate ok"
php artisan optimize
echo " test optimize ok"
php artisan view:clear
echo " test view ok"
free -h
echo " test free -h"

@endtask



@task('clean')
echo "Clean old releases";
@endtask

@task('succeed')
free -g -h -t && sync && free -g -h -t
echo 'Deployment completed successfully. the new {{$branch}} releases {{$release}} is live now :) '
@endtask














