pipeline {
    agent any
    stages {
        stage("Verify tooling") {
            steps {
                bat '''
                    docker info
                    docker version
                    docker-compose version
                '''
            }
        }
        stage("Clear all running docker containers") {
            steps {
                script {
                    try {
                        bat 'docker rm -f $(docker ps -a -q)'
                    } catch (Exception e) {
                        echo 'No running container to clear up...'
                    }
                }
            }
        }
        stage("Start Docker") {
            steps {
                bat 'docker-compose up -d'
                bat 'docker-compose ps'
            }
        }
        stage("Run Composer Install") {
            steps {
                bat 'docker-compose run --rm composer install'
            }
        }
        stage("Populate .env file") {
            steps {
                    dir("C:/ProgramData/Jenkins/.jenkins/workspace/envs/sag") {
                    fileOperations([fileCopyOperation(excludes: '', flattenFiles: true, includes: '.env', targetLocation: "${WORKSPACE}")])
                }
            }
        }
        stage("Run PHPUnit Tests") {
            steps {
                // Jalankan PHPUnit di dalam container PHP
                script {
                    sh "docker-compose exec php bash -c 'cd /var/www/html && php artisan test'"
                }
            }
        }
        stage("Run PHPUnit Tests Locally") {
            steps {
                // Jalankan PHPUnit secara lokal
                sh "composer install"
                sh "./vendor/bin/phpunit"
            }
        }
    }
    post {
        always {
            bat 'docker-compose down --remove-orphans -v'
            bat 'docker-compose ps'
        }
    }
}
