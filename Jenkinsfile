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
                    script {
                        // Ensure File Operations plugin is available
                        if (fileOperations) {
                            fileOperations([fileCopyOperation(excludes: '', flattenFiles: true, includes: '.env', targetLocation: "${env.WORKSPACE}")])
                        } else {
                            error "File Operations plugin is not available."
                        }
                    }
                }
            }
        }
    

        stage("Run Tests") {
            steps {
                bat 'echo running unit-tests'
                bat 'docker-compose run --rm artisan test'
            }
        }
    }

}