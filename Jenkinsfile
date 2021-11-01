pipeline {
    agent none
    stages {
        stage('Docker up') {
            agent any
            steps {
                label 'Subir containers'
                sh 'curl -L "https://github.com/docker/compose/releases/download/1.24.1/docker-compose-$(uname -s)-$(uname -m)" -o docker-compose'
                sh 'chmod +x docker-compose'
                sh './docker-compose -f docker-compose-tests.yml down --remove-orphans -v'
                sh 'docker network remove ramias || true'
                sh 'docker network create ramias'
                sh './docker-compose -f docker-compose-tests.yml up -d'
                sh 'mkdir -p /tmp/composer'
            }
        }
        stage('PHP 7.4') {
            agent {
                docker {
                    image 'composer.insis.com.br:8083/inovadora-dev/php-cli:7.4-latest'
                    registryUrl 'https://composer.insis.com.br:8083'
                    registryCredentialsId 'franklin-inovadorahub'
                    args '-v ${PWD}:/home/inovadora/workspace -u 1000 -v /tmp/composer:/home/inovadora/.composer:rw --network ramias'
                    alwaysPull true
                }
            }
            steps {
                sh 'php -v'
                sshagent(credentials: ['jenkins-private-key']){
                    sh 'ssh-keyscan -H bitbucket.org >> ~/.ssh/known_hosts'
                    sh 'composer update -vvv'
                }
                sh 'php artisan migrate'
                sh 'php artisan db:seed --class=UserSeeder'
                sh 'php artisan db:seed --class=CatSeeder'
                sh 'php artisan db:seed --class=CatSeeder'
                sh 'php artisan test'
            }
        }
        stage('Docker down') {
            agent any
            steps {
                label 'Matar containers de cache'
                sh './docker-compose -f docker-compose-tests.yml down --remove-orphans -v'
                sh 'docker network remove ramias'
            }
        }
        stage('Sonar') {
            agent {
                docker {
                        image 'composer.insis.com.br:8083/inovadora-dev/php-cli:7.4-latest'
                        registryUrl 'https://composer.insis.com.br:8083'
                        registryCredentialsId 'franklin-inovadorahub'
                        args '-v ${PWD}:/home/inovadora/workspace -u 1000 -v /tmp/composer:/home/inovadora/.composer:rw'
                        alwaysPull true
                    }
                }
                steps {
                    sh '~/sonar-scanner/bin/sonar-scanner'
                }
        }
    }
    post {
        always {
            emailext body: "${currentBuild.currentResult}: Job ${env.JOB_NAME} build ${env.BUILD_NUMBER}\n More info at: ${env.BUILD_URL}",
                     recipientProviders: [developers(), requestor()],
                     subject: "Jenkins Build ${currentBuild.currentResult}: Job ${env.JOB_NAME}"
        }
    }
}
