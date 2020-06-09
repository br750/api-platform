<?php

use EasyCorp\Bundle\EasyDeployBundle\Deployer\DefaultDeployer;

return new class extends DefaultDeployer
{
    public function configure()
    {

        return $this->getConfigBuilder()
            // SSH connection string to connect to the remote server (format: user@host-or-IP:port-number)
            ->server('root@51.254.120.133')
            // the absolute path of the remote server directory where the project is deployed
            ->deployDir('/home/formemploi/public_html/')
            // the URL of the Git repository where the project code is hosted
            ->repositoryUrl('git@gitlab.com:7Cinquante/formemploi-api.git')
            // the repository branch to deploy
            ->repositoryBranch('develop')
            ->symfonyEnvironment('dev')
            ->remoteComposerBinaryPath('/usr/bin/composer')
            //->composerInstallFlags('--no-interaction --quiet')
            ->composerInstallFlags('--no-interaction')
            ->composerOptimizeFlags('--optimize --quiet')
            ->keepReleases(3)
            ->fixPermissionsWithChmod('0777')
            ->fixPermissionsWithChown("root")
            ->fixPermissionsWithChgrp("formemploi")
            //->sharedFilesAndDirs(['.env'])
            ->sharedFilesAndDirs([])
            ->webDir('public')
        ;
    }
    //->writableDirs(["var/cache/","var/logs/"])
    //->sharedFilesAndDirs(array('web/codebarres','web/photoCharges','web/photosavaries','web/documents','web/certificats','web/bordereauxLivraisons','web/cr_dosages'))

    // run some local or remote commands before the deployment is started
    public function beforeStartingDeploy()
    {
        // $this->runLocal('./vendor/bin/simple-phpunit');
    }

    // run some local or remote commands after the deployment is finished
    public function beforeFinishingDeploy()
    {
        // $this->runRemote('{{ console_bin }} app:my-task-name');
        // $this->runLocal('say "The deployment has finished."');// chmod -R 777 var/cache/
        $this->log('Running change permission on cache/logs.');
        $this->runRemote('chmod -R 777 var/*');
        /*$this->log('Running change permission on cache/logs.');
        $this->runRemote('chmod -R 777 {{ cache_dir }}');
        $this->runRemote('chmod -R 777 var/logs');
        $this->runRemote('chmod -R 777 var/sessions');*/

        /*$this->runRemote('chown root:www-data web/codebarres');
        $this->runRemote('chown root:www-data web/photoCharges');
        $this->runRemote('chown root:www-data web/photosavaries');
        $this->runRemote('chown root:www-data web/pdf_blocages');
        $this->runRemote('chown root:www-data web/certificats');
        $this->runRemote('chown root:www-data web/cr_dosages');
        $this->runRemote('chown root:www-data web/bordereauxLivraisons');
        $this->runRemote('chown root:www-data web/documents');*/

        /*$this->runRemote('chmod -R 775 web/codebarres');
        $this->runRemote('chmod -R 775 web/photoCharges');
        $this->runRemote('chmod -R 775 web/photosavaries');
        $this->runRemote('chmod -R 775 web/pdf_blocages');
        $this->runRemote('chmod -R 775 web/certificats');
        $this->runRemote('chmod -R 775 web/cr_dosages');
        $this->runRemote('chmod -R 775 web/bordereauxLivraisons');
        $this->runRemote('chmod -R 775 web/documents');*/
        // yarn encore dev => install yarn dependencies
        $this->runRemote('yarn install');
        $this->runRemote('yarn encore dev');
        $this->runRemote('cp -rf assets/js/workers_*.js public/build');
        //$this->log('Running update dataBase.');
        //$this->runRemote('{{ console_bin }}  doctrine:schema:update --force');
    }
};
