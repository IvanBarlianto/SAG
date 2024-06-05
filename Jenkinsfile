pipeline {
    agent any
    stages {
        stage("Verify tooling") {
            steps {
                bat '''
                    docker info
                    docker version
                    docker compose version
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
                bat 'make up'
                bat 'docker compose ps'
            }
        }
        stage("Run Composer Install") {
            steps {
                bat 'docker compose run --rm composer install'
            }
        }
        stage("Populate .env file") {
            steps {
                script {
                    bat 'cp C:/var/jenkins_home/workspace/envs/SAG/.env ${WORKSPACE}/.env'
                }
            }
        }
        stage("Run Tests") {
            steps {
                bat 'docker compose run --rm artisan test'
            }
        }
    }
    post {
        always {
            bat 'docker compose down --remove-orphans -v'
            bat 'docker compose ps'
        }
    }
}
