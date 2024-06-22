pipeline {
    agent any

    environment {
        PATH = "C:/Program Files/7-Zip:$PATH"
    }

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
                        bat 'for /f "tokens=*" %%i in (\'docker ps -aq\') do docker rm -f %%i'
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

        stage("Run Tests") {
            steps {
                bat 'echo running unit-tests'
                bat 'docker-compose run --rm artisan test'
            }
        }
         stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('sonar_sag') {
                    sh 'sonar-scanner'
                }
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
