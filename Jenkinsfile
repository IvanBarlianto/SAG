pipeline {
    agent any
    environment {
        SSH_CREDENTIALS_ID = 'aws-ec2' // Replace with your actual SSH credential ID
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
                        bat 'docker volume rm -f'
                    } catch (Exception e) {
                        echo 'No running container to clear up...'
                    }
                }
            }
        }
        stage('Run SSH Command') {
            steps {
                script {
                    def sshCommand = "ssh -o StrictHostKeyChecking=no ubuntu@13.211.134.87 whoami"
                    withCredentials([sshUserPrivateKey(credentialsId: SSH_CREDENTIALS_ID, keyFileVariable: 'SSH_KEY')]) {
                        bat "ssh -i ${SSH_KEY} -o StrictHostKeyChecking=no ubuntu@13.211.134.87 whoami"
                    }
                }
            }
        }
        stage("Start Docker") {
            steps {
                bat 'make up'
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
    }
}